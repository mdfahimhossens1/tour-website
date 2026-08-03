<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Services\NotificationService;

class UserController extends Controller
{
    /* =====================================================
        ROLE HELPER
    ===================================================== */

    private function role(): string
    {
        return str(optional(auth()->user()->role)->role_name ?? 'user')
            ->lower()
            ->replace([' ', '-'], '_')
            ->toString();
    }

    private function isAdmin(): bool
    {
        return in_array($this->role(), ['admin', 'super_admin']);
    }

    private function isManager(): bool
    {
        return $this->role() === 'manager';
    }

    private function abortAccess()
    {
        if (!in_array($this->role(), ['admin', 'super_admin', 'manager'])) {
            abort(403);
        }
    }

    private function targetRole(User $user): string
    {
        return str(optional($user->role)->role_name ?? '')
            ->lower()
            ->replace([' ', '-'], '_')
            ->toString();
    }


    /* =====================================================
        INDEX (ALL USERS)
    ===================================================== */

    public function index()
    {
        $this->abortAccess();

        $users = User::with('role')
            ->whereHas('role', fn($q) => $q->where('role_name', 'user'))
            ->latest()
            ->get();

        return view('admin.user.all', compact('users'));
    }


    /* =====================================================
        STAFF USERS
    ===================================================== */

    public function staff()
    {
        $this->abortAccess();

        $query = User::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('role_name', [
                    'super admin',
                    'admin',
                    'manager'
                ]);
            });

        // Admin Super Admin দেখতে পাবে না
        if ($this->role() === 'admin') {

            $query->whereHas('role', function ($q) {
                $q->whereNotIn('role_name', [
                    'super admin',
                    'super_admin'
                ]);
            });
        }

        // Manager শুধু Manager দেখতে পাবে
        if ($this->role() === 'manager') {

            $query->whereHas('role', function ($q) {
                $q->where('role_name', 'manager');
            });
        }

        $staff = $query->latest()->get();

        return view('admin.user.staff', compact('staff'));
    }


    /* =====================================================
        CREATE FORM
    ===================================================== */

    public function add()
    {
        $this->abortAccess();

        if ($this->role() === 'super_admin') {

            $roles = Role::all();

        } elseif ($this->role() === 'admin') {

            $roles = Role::whereNotIn('role_name', [
                'super admin',
                'super_admin'
            ])->get();

        } else {

            $roles = Role::whereIn('role_name', [
                'manager'
            ])->get();
        }

        return view('admin.user.add', compact('roles'));
    }


    /* =====================================================
        STORE USER
    ===================================================== */

    public function store(Request $request)
    {
        $this->abortAccess();

        /*
        |--------------------------------------------------------------------------
        | Basic Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'phone'    => 'required',
            'password' => 'required|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'photo'    => 'nullable|image|max:2048',

            // Vendor commission
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Selected Role
        |--------------------------------------------------------------------------
        */

        $selectedRole = strtolower(
            str_replace(
                [' ', '-'],
                '_',
                Role::findOrFail($request->role_id)->role_name
            )
        );


        /*
        |--------------------------------------------------------------------------
        | ADMIN ROLE RESTRICTION
        |--------------------------------------------------------------------------
        */

        if ($this->role() === 'admin') {

            if ($selectedRole === 'super_admin') {

                return back()
                    ->with('error', 'Admin cannot create Super Admin')
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER ROLE RESTRICTION
        |--------------------------------------------------------------------------
        */

        if ($this->role() === 'manager') {

            if (in_array($selectedRole, [
                'super_admin',
                'admin'
            ])) {

                return back()
                    ->with('error', 'Manager cannot create Admin or Super Admin')
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER CANNOT CREATE ADMIN
        |--------------------------------------------------------------------------
        */

        if ($this->isManager()) {

            $roleName = Role::where(
                'id',
                $request->role_id
            )->value('role_name');

            if (strtolower($roleName) === 'admin') {

                return back()
                    ->with('error', 'Manager cannot create admin')
                    ->withInput();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | USER PHOTO
        |--------------------------------------------------------------------------
        */

        $photo = null;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $photo =
                'user_' .
                time() .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();

            $file->move(
                public_path('uploads/users'),
                $photo
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name'     => $request->name,

            'email'    => $request->email,

            'username' => $request->username,

            'phone'    => $request->phone,

            'password' => Hash::make($request->password),

            'role_id'  => $request->role_id,

            'status'   => 1,

            'slug'     => 'user_' . uniqid(),

            'creator'  => Auth::id(),

            'photo'    => $photo,

            'created_at' => Carbon::now(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE VENDOR PROFILE
        |--------------------------------------------------------------------------
        |
        | Only when selected role is Vendor.
        |
        */

        $role = strtolower(
            str_replace(
                [' ', '-'],
                '_',
                $user->role->role_name
            )
        );


        if ($role === 'vendor') {

            /*
            |--------------------------------------------------------------------------
            | Commission Rate
            |--------------------------------------------------------------------------
            |
            | Admin/Super Admin Blade থেকে rate পাঠাবে।
            |
            | Example:
            | 10 = 10%
            | 15 = 15%
            | 20 = 20%
            |
            | কিছু না দিলে default 10%.
            |
            */

            $commissionRate = $request->input(
                'commission_rate',
                10
            );


            /*
            |--------------------------------------------------------------------------
            | CREATE VENDOR
            |--------------------------------------------------------------------------
            */

            Vendor::create([

                'user_id' => $user->id,

                'business_name' => $user->name,

                'phone' => $user->phone,

                'status' => 'pending',

                'commission_rate' => $commissionRate,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SEND NOTIFICATION
        |--------------------------------------------------------------------------
        */

        NotificationService::send(

            Auth::id(),

            "New user added: {$user->name}",

            "User created successfully",

            "success"

        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User created successfully'
            );
    }


    /* =====================================================
        SHOW USER
    ===================================================== */

    public function show($slug)
    {
        $this->abortAccess();

        $user = User::with('role')
            ->where('slug', $slug)
            ->firstOrFail();

        if (
            $this->isManager() &&
            in_array(
                $this->targetRole($user),
                [
                    'admin',
                    'super_admin'
                ]
            )
        ) {
            abort(403);
        }

        return view(
            'admin.user.view',
            compact('user')
        );
    }


    /* =====================================================
        EDIT USER
    ===================================================== */

    public function edit($slug)
    {
        $this->abortAccess();

        $data = User::with('role')
            ->where('slug', $slug)
            ->firstOrFail();

        $currentRole = $this->role();

        $targetRole = $this->targetRole($data);


        /*
        |--------------------------------------------------------------------------
        | ADMIN CANNOT EDIT SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'admin' &&
            $targetRole === 'super_admin'
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER CANNOT EDIT ADMIN/SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'manager' &&
            in_array(
                $targetRole,
                [
                    'super_admin',
                    'admin'
                ]
            )
        ) {
            abort(403);
        }


        $roles = $this->isAdmin()

            ? Role::all()

            : Role::where(
                'role_name',
                '!=',
                'admin'
            )->get();


        return view(
            'admin.user.edit',
            compact(
                'data',
                'roles'
            )
        );
    }


    /* =====================================================
        UPDATE USER
    ===================================================== */

    public function update(Request $request, $slug)
    {
        $this->abortAccess();

        $user = User::with('role')
            ->where('slug', $slug)
            ->firstOrFail();

        $currentRole = $this->role();

        $targetRole = $this->targetRole($user);


        /*
        |--------------------------------------------------------------------------
        | ADMIN CANNOT UPDATE SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'admin' &&
            $targetRole === 'super_admin'
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER CANNOT UPDATE ADMIN/SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'manager' &&
            in_array(
                $targetRole,
                [
                    'super_admin',
                    'admin'
                ]
            )
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER RESTRICTION
        |--------------------------------------------------------------------------
        */

        if (
            $this->isManager() &&
            in_array(
                $this->targetRole($user),
                [
                    'admin',
                    'super_admin'
                ]
            )
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' => 'required',

            'email' =>
                'required|email|unique:users,email,' .
                $user->id,

            'phone' => 'nullable',

            'status' => 'required|in:0,1',

        ]);


        /*
        |--------------------------------------------------------------------------
        | ROLE UPDATE VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role_id')) {

            $newRole = strtolower(

                str_replace(

                    [' ', '-'],

                    '_',

                    Role::findOrFail(
                        $request->role_id
                    )->role_name

                )

            );


            /*
            |--------------------------------------------------------------------------
            | ADMIN CANNOT ASSIGN SUPER ADMIN
            |--------------------------------------------------------------------------
            */

            if (
                $this->role() === 'admin' &&
                $newRole === 'super_admin'
            ) {

                return back()->with(
                    'error',
                    'Admin cannot assign Super Admin role'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | MANAGER CANNOT ASSIGN ADMIN/SUPER ADMIN
            |--------------------------------------------------------------------------
            */

            if (
                $this->role() === 'manager' &&
                in_array(
                    $newRole,
                    [
                        'super_admin',
                        'admin'
                    ]
                )
            ) {

                return back()->with(
                    'error',
                    'Manager cannot assign Admin role'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN ROLE VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($this->isAdmin()) {

            $request->validate([
                'role_id' => 'required|exists:roles,id'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE USER
        |--------------------------------------------------------------------------
        */

        $user->name = $request->name;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->status = $request->status;

        $user->editor = Auth::id();


        /*
        |--------------------------------------------------------------------------
        | UPDATE ROLE
        |--------------------------------------------------------------------------
        */

        if ($this->isAdmin()) {

            $user->role_id = $request->role_id;
        }


        $user->save();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User updated successfully'
            );
    }


    /* =====================================================
        DELETE USER
    ===================================================== */

    public function destroy($id)
    {
        $this->abortAccess();

        $user = User::with('role')
            ->findOrFail($id);

        $currentRole = $this->role();

        $targetRole = $this->targetRole($user);


        /*
        |--------------------------------------------------------------------------
        | ADMIN CANNOT DELETE SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'admin' &&
            $targetRole === 'super_admin'
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'You cannot delete Super Admin.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | MANAGER CANNOT DELETE ADMIN/SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'manager' &&
            in_array(
                $targetRole,
                [
                    'admin',
                    'super_admin'
                ]
            )
        ) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'You are not allowed to delete this user.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CANNOT DELETE OWN ACCOUNT
        |--------------------------------------------------------------------------
        */

        if ($user->id == auth()->id()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE USER PHOTO
        |--------------------------------------------------------------------------
        */

        if (
            $user->photo &&
            file_exists(
                public_path(
                    'uploads/users/' .
                    $user->photo
                )
            )
        ) {

            unlink(
                public_path(
                    'uploads/users/' .
                    $user->photo
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE USER
        |--------------------------------------------------------------------------
        */

        $user->delete();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}