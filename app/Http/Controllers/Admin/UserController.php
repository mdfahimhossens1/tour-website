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

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'phone'    => 'required',
            'password' => 'required|min:6|confirmed',
            'role_id'  => 'required|exists:roles,id',
            'photo'    => 'nullable|image|max:2048',
        ]);

$selectedRole = strtolower(
    str_replace(
        [' ', '-'],
        '_',
        Role::findOrFail($request->role_id)->role_name
    )
);

if ($this->role() === 'admin') {

    if ($selectedRole === 'super_admin') {

        return back()
            ->with('error', 'Admin cannot create Super Admin');
    }
}

if ($this->role() === 'manager') {

    if (in_array($selectedRole, [
        'super_admin',
        'admin'
    ])) {

        return back()
            ->with('error', 'Manager cannot create Admin or Super Admin');
    }
}
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
$targetRole = $this->targetRole($data);

if (
    $currentRole === 'admin' &&
    $targetRole === 'super_admin'
) {
    abort(403);
}

if (
    $currentRole === 'manager' &&
    in_array($targetRole, [
        'super_admin',
        'admin'
    ])
) {
    abort(403);
}
        $data = User::with('role')->where('slug', $slug)->firstOrFail();

        $currentRole = $this->role();

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
$currentRole = $this->role();

$targetRole = $this->targetRole($user);

if (
    $currentRole === 'admin' &&
    $targetRole === 'super_admin'
) {
    abort(403);
}

if (
    $currentRole === 'manager' &&
    in_array($targetRole, [
        'super_admin',
        'admin'
    ])
) {
    abort(403);
}

        if ($this->isManager() && in_array($this->targetRole($user), ['admin', 'super_admin'])) {
            abort(403);
        }

        $request->validate([
            'name'   => 'required',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'phone'  => 'nullable',
            'status' => 'required|in:0,1',
        ]);

        if ($request->filled('role_id')) {

    $newRole = strtolower(
        str_replace(
            [' ', '-'],
            '_',
            Role::findOrFail($request->role_id)->role_name
        )
    );

    if (
        $this->role() === 'admin' &&
        $newRole === 'super_admin'
    ) {
        return back()->with(
            'error',
            'Admin cannot assign Super Admin role'
        );
    }

    if (
        $this->role() === 'manager' &&
        in_array($newRole, [
            'super_admin',
            'admin'
        ])
    ) {
        return back()->with(
            'error',
            'Manager cannot assign Admin role'
        );
    }
}

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