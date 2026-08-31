<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\NotificationService;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Allowed Admin Panel Roles
    |--------------------------------------------------------------------------
    |
    | User role intentionally excluded.
    |
    */

    private const ALLOWED_ROLES = [
        'super_admin',
        'admin',
        'manager',
        'vendor',
    ];


    /*
    |--------------------------------------------------------------------------
    | ROLE HELPER
    |--------------------------------------------------------------------------
    */

    private function normalizeRole(?string $roleName): string
    {
        return str($roleName ?? '')
            ->lower()
            ->replace([' ', '-'], '_')
            ->toString();
    }


    private function role(): string
    {
        return $this->normalizeRole(
            optional(auth()->user()->role)->role_name
        );
    }


    private function isSuperAdmin(): bool
    {
        return $this->role() === 'super_admin';
    }


    private function isAdmin(): bool
    {
        return in_array(
            $this->role(),
            [
                'admin',
                'super_admin',
            ],
            true
        );
    }


    private function isManager(): bool
    {
        return $this->role() === 'manager';
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    private function abortAccess(): void
    {
        if (!in_array(
            $this->role(),
            [
                'admin',
                'super_admin',
                'manager',
            ],
            true
        )) {
            abort(403);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | TARGET USER ROLE
    |--------------------------------------------------------------------------
    */

    private function targetRole(User $user): string
    {
        return $this->normalizeRole(
            optional($user->role)->role_name
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET ADMIN PANEL ROLES
    |--------------------------------------------------------------------------
    |
    | Only these 4 roles are allowed.
    |
    | Duplicate database roles are collapsed by normalized role name.
    |
    */

    private function getAllowedRoles()
    {
        $roles = Role::query()
            ->where(function ($query) {

                foreach (self::ALLOWED_ROLES as $index => $role) {

                    $displayName = match ($role) {
                        'super_admin' => 'super admin',
                        default => $role,
                    };

                    if ($index === 0) {

                        $query->whereRaw(
                            'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) = ?',
                            [$role]
                        );

                    } else {

                        $query->orWhereRaw(
                            'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) = ?',
                            [$role]
                        );
                    }
                }
            })
            ->get()
            ->unique(function ($role) {
                return $this->normalizeRole($role->role_name);
            })
            ->sortBy(function ($role) {

                return array_search(
                    $this->normalizeRole($role->role_name),
                    self::ALLOWED_ROLES,
                    true
                );
            })
            ->values();

        return $roles;
    }


    /*
    |--------------------------------------------------------------------------
    | GET ROLE BY NORMALIZED NAME
    |--------------------------------------------------------------------------
    */

    private function getRoleId(string $roleName): ?int
    {
        $normalized = $this->normalizeRole($roleName);

        $role = Role::query()
            ->get()
            ->first(function ($role) use ($normalized) {

                return $this->normalizeRole(
                    $role->role_name
                ) === $normalized;
            });

        return $role?->id;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK WHETHER ROLE IS ALLOWED
    |--------------------------------------------------------------------------
    */

    private function isAllowedRole(string $roleName): bool
    {
        return in_array(
            $this->normalizeRole($roleName),
            self::ALLOWED_ROLES,
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE ASSIGNMENT PERMISSION
    |--------------------------------------------------------------------------
    */

    private function canAssignRole(string $roleName): bool
    {
        $currentRole = $this->role();

        $roleName = $this->normalizeRole($roleName);


        /*
        |--------------------------------------------------------------------------
        | Invalid role
        |--------------------------------------------------------------------------
        */

        if (!$this->isAllowedRole($roleName)) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        |
        | Can assign all 4 roles.
        |
        */

        if ($currentRole === 'super_admin') {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        |
        | Cannot create/assign Super Admin.
        |
        */

        if ($currentRole === 'admin') {

            return in_array(
                $roleName,
                [
                    'admin',
                    'manager',
                    'vendor',
                ],
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        |
        | Can only create Manager and Vendor.
        |
        */

        if ($currentRole === 'manager') {

            return in_array(
                $roleName,
                [
                    'manager',
                    'vendor',
                ],
                true
            );
        }


        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Shows normal/customer users if your system still has the user role.
    |
    | This does NOT show staff roles.
    |
    */

    public function index()
    {
        $this->abortAccess();

        $users = User::with('role')
            ->whereHas('role', function ($query) {

                $query->whereRaw(
                    'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) = ?',
                    ['user']
                );

            })
            ->latest()
            ->get();

        return view(
            'admin.user.all',
            compact('users')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STAFF USERS
    |--------------------------------------------------------------------------
    |
    | Only:
    | Super Admin
    | Admin
    | Manager
    | Vendor
    |
    */

    public function staff()
    {
        $this->abortAccess();


        $query = User::with('role')
            ->whereHas('role', function ($q) {

                $q->where(function ($query) {

                    foreach (self::ALLOWED_ROLES as $index => $role) {

                        if ($index === 0) {

                            $query->whereRaw(
                                'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) = ?',
                                [$role]
                            );

                        } else {

                            $query->orWhereRaw(
                                'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) = ?',
                                [$role]
                            );
                        }
                    }

                });

            });


        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        |
        | Admin cannot see Super Admin.
        |
        */

        if ($this->role() === 'admin') {

            $query->whereHas('role', function ($q) {

                $q->whereRaw(
                    'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) != ?',
                    ['super_admin']
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        |
        | Manager can see only Manager and Vendor.
        |
        */

        if ($this->role() === 'manager') {

            $query->whereHas('role', function ($q) {

                $q->where(function ($query) {

                    $query->whereRaw(
                        'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) = ?',
                        ['manager']
                    )
                    ->orWhereRaw(
                        'LOWER(REPLACE(REPLACE(role_name, " ", "_"), "-", "_")) = ?',
                        ['vendor']
                    );

                });

            });
        }


        $staff = $query
            ->latest()
            ->get();


        return view(
            'admin.user.staff',
            compact('staff')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE USER FORM
    |--------------------------------------------------------------------------
    */

    public function add()
    {
        $this->abortAccess();


        $allRoles = $this->getAllowedRoles();


        /*
        |--------------------------------------------------------------------------
        | Filter roles according to logged-in user
        |--------------------------------------------------------------------------
        */

        $roles = $allRoles
            ->filter(function ($role) {

                return $this->canAssignRole(
                    $this->normalizeRole($role->role_name)
                );

            })
            ->values();


        return view(
            'admin.user.add',
            compact('roles')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE USER
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->abortAccess();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'password' => [
                'required',
                'min:6',
                'confirmed',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'photo' => [
                'nullable',
                'image',
                'max:2048',
            ],

            'commission_rate' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Selected Role
        |--------------------------------------------------------------------------
        */

        $selectedRoleModel = Role::findOrFail(
            $request->role_id
        );


        $selectedRole = $this->normalizeRole(
            $selectedRoleModel->role_name
        );


        /*
        |--------------------------------------------------------------------------
        | Only 4 roles allowed
        |--------------------------------------------------------------------------
        */

        if (!$this->isAllowedRole($selectedRole)) {

            return back()
                ->with(
                    'error',
                    'Invalid role selected.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Check permission
        |--------------------------------------------------------------------------
        */

        if (!$this->canAssignRole($selectedRole)) {

            return back()
                ->with(
                    'error',
                    'You are not allowed to assign this role.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | User Photo
        |--------------------------------------------------------------------------
        */

        $photo = null;


        if ($request->hasFile('photo')) {

            $file = $request->file('photo');


            $uploadPath = public_path(
                'uploads/users'
            );


            if (!is_dir($uploadPath)) {

                mkdir(
                    $uploadPath,
                    0755,
                    true
                );
            }


            $photo =
                'user_' .
                time() .
                '_' .
                uniqid() .
                '.' .
                $file->getClientOriginalExtension();


            $file->move(
                $uploadPath,
                $photo
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'username' => $request->username,

            'phone' => $request->phone,

            'password' => Hash::make(
                $request->password
            ),

            'role_id' => $selectedRoleModel->id,

            'status' => 1,

            'slug' => 'user_' . uniqid(),

            'creator' => Auth::id(),

            'photo' => $photo,

            'created_at' => Carbon::now(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Vendor Profile
        |--------------------------------------------------------------------------
        */

        if ($selectedRole === 'vendor') {

            $commissionRate = (float) $request->input(
                'commission_rate',
                10
            );


            $commissionRate = max(
                0,
                min(
                    100,
                    $commissionRate
                )
            );


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
        | Notification
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
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW USER
    |--------------------------------------------------------------------------
    */

    public function show($slug)
    {
        $this->abortAccess();


        $user = User::with('role')
            ->where('slug', $slug)
            ->firstOrFail();


        $targetRole = $this->targetRole($user);


        /*
        |--------------------------------------------------------------------------
        | Manager restriction
        |--------------------------------------------------------------------------
        */

        if (
            $this->isManager() &&
            in_array(
                $targetRole,
                [
                    'super_admin',
                    'admin',
                ],
                true
            )
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Admin restriction
        |--------------------------------------------------------------------------
        */

        if (
            $this->role() === 'admin' &&
            $targetRole === 'super_admin'
        ) {
            abort(403);
        }


        return view(
            'admin.user.view',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT USER
    |--------------------------------------------------------------------------
    */

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
        | Admin cannot edit Super Admin
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
        | Manager cannot edit Admin/Super Admin
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'manager' &&
            in_array(
                $targetRole,
                [
                    'super_admin',
                    'admin',
                ],
                true
            )
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Available Roles
        |--------------------------------------------------------------------------
        */

        $roles = $this->getAllowedRoles()
            ->filter(function ($role) {

                return $this->canAssignRole(
                    $this->normalizeRole(
                        $role->role_name
                    )
                );

            })
            ->values();


        return view(
            'admin.user.edit',
            compact(
                'data',
                'roles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $slug
    ) {
        $this->abortAccess();


        $user = User::with('role')
            ->where('slug', $slug)
            ->firstOrFail();


        $currentRole = $this->role();

        $targetRole = $this->targetRole($user);


        /*
        |--------------------------------------------------------------------------
        | Admin cannot update Super Admin
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
        | Manager cannot update Admin/Super Admin
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'manager' &&
            in_array(
                $targetRole,
                [
                    'super_admin',
                    'admin',
                ],
                true
            )
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' =>
                'required|email|unique:users,email,' .
                $user->id,

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'status' => [
                'required',
                'in:0,1',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | New Role
        |--------------------------------------------------------------------------
        */

        $newRoleModel = Role::findOrFail(
            $request->role_id
        );


        $newRole = $this->normalizeRole(
            $newRoleModel->role_name
        );


        /*
        |--------------------------------------------------------------------------
        | Only allowed roles
        |--------------------------------------------------------------------------
        */

        if (!$this->isAllowedRole($newRole)) {

            return back()
                ->with(
                    'error',
                    'Invalid role selected.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Role assignment permission
        |--------------------------------------------------------------------------
        */

        if (!$this->canAssignRole($newRole)) {

            return back()
                ->with(
                    'error',
                    'You are not allowed to assign this role.'
                )
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Update basic information
        |--------------------------------------------------------------------------
        */

        $user->name = $request->name;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->status = $request->status;

        $user->editor = Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Update Role
        |--------------------------------------------------------------------------
        */

        $oldRole = $this->targetRole($user);


        $user->role_id = $newRoleModel->id;

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Vendor Profile
        |--------------------------------------------------------------------------
        |
        | If user becomes Vendor, create vendor profile.
        |
        */

        if ($newRole === 'vendor') {

            $commissionRate = (float) $request->input(
                'commission_rate',
                10
            );


            $commissionRate = max(
                0,
                min(
                    100,
                    $commissionRate
                )
            );


            Vendor::firstOrCreate(

                [
                    'user_id' => $user->id,
                ],

                [
                    'business_name' =>
                        $user->name,

                    'phone' =>
                        $user->phone,

                    'status' =>
                        'pending',

                    'commission_rate' =>
                        $commissionRate,
                ]

            );
        }


        /*
        |--------------------------------------------------------------------------
        | If Vendor is changed to another role
        |--------------------------------------------------------------------------
        |
        | We don't automatically delete Vendor profile.
        | This keeps vendor data safe.
        |
        */


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $this->abortAccess();


        $user = User::with('role')
            ->findOrFail($id);


        $currentRole = $this->role();

        $targetRole = $this->targetRole($user);


        /*
        |--------------------------------------------------------------------------
        | Cannot delete own account
        |--------------------------------------------------------------------------
        */

        if ((int) $user->id === (int) auth()->id()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Admin cannot delete Super Admin
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
        | Manager cannot delete Admin/Super Admin
        |--------------------------------------------------------------------------
        */

        if (
            $currentRole === 'manager' &&
            in_array(
                $targetRole,
                [
                    'admin',
                    'super_admin',
                ],
                true
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
        | Delete User Photo
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
        | Delete User
        |--------------------------------------------------------------------------
        */

        $user->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
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