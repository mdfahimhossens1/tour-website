<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Notifications\AdminActivityNotification;

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

        $staff = User::with('role')
            ->whereHas('role', function ($q) {
                $q->whereIn('role_name', ['admin', 'super admin', 'manager']);
            })
            ->latest()
            ->get();

        return view('admin.user.staff', compact('staff'));
    }

    /* =====================================================
        CREATE FORM
    ===================================================== */

public function add()
{
    $this->abortAccess();

    $roles = $this->isAdmin()
        ? Role::all()
        : Role::where('role_name', '!=', 'admin')->get();

    return view('admin.user.add', compact('roles'));
}

    /* =====================================================
        STORE USER
    ===================================================== */

    public function store(Request $request)
    {
        $this->abortAccess();

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'phone'    => 'required',
            'password' => 'required|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'photo'    => 'nullable|image|max:2048',
        ]);

        // manager cannot create admin
        if ($this->isManager()) {
            $roleName = Role::where('id', $request->role_id)->value('role_name');

            if (strtolower($roleName) === 'admin') {
                return back()->with('error', 'Manager cannot create admin')->withInput();
            }
        }

        $photo = null;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photo = 'user_' . time() . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $photo);
        }

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

        Auth::user()?->notify(new AdminActivityNotification(
            title: "New user added: {$user->name}",
            subtitle: "User created successfully",
            url: route('admin.users.index'),
            icon: "🆕"
        ));

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    /* =====================================================
        SHOW USER
    ===================================================== */

    public function show($slug)
    {
        $this->abortAccess();

        $user = User::with('role')->where('slug', $slug)->firstOrFail();

        if ($this->isManager() && in_array($this->targetRole($user), ['admin', 'super_admin'])) {
            abort(403);
        }

        return view('admin.user.view', compact('user'));
    }

    /* =====================================================
        EDIT USER
    ===================================================== */

    public function edit($slug)
    {
        $this->abortAccess();

        $data = User::with('role')->where('slug', $slug)->firstOrFail();

        if ($this->isManager() && in_array($this->targetRole($data), ['admin', 'super_admin'])) {
            abort(403);
        }

        $roles = $this->isAdmin()
            ? Role::all()
            : Role::where('role_name', '!=', 'admin')->get();

        return view('admin.user.edit', compact('data', 'roles'));
    }

    /* =====================================================
        UPDATE USER
    ===================================================== */

    public function update(Request $request, $slug)
    {
        $this->abortAccess();

        $user = User::with('role')->where('slug', $slug)->firstOrFail();

        if ($this->isManager() && in_array($this->targetRole($user), ['admin', 'super_admin'])) {
            abort(403);
        }

        $request->validate([
            'name'   => 'required',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'phone'  => 'nullable',
            'status' => 'required|in:0,1',
        ]);

        if ($this->isAdmin()) {
            $request->validate([
                'role_id' => 'required|exists:roles,id'
            ]);
        }

        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->phone  = $request->phone;
        $user->status = $request->status;
        $user->editor = Auth::id();

        if ($this->isAdmin()) {
            $user->role_id = $request->role_id;
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }
}