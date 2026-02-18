<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Helpers
     */
    private function myRole(): string
    {
        return strtolower(optional(auth()->user()->role)->role_name ?? 'user');
    }

    private function isAdmin(): bool
    {
        return in_array($this->myRole(), ['super admin', 'admin']);
    }

    private function isManager(): bool
    {
        return $this->myRole() === 'manager';
    }

    private function abortIfNotAdminOrManager(): void
    {
        if (!in_array($this->myRole(), ['super admin', 'admin', 'manager'])) {
            abort(403, 'Unauthorized');
        }
    }

    private function targetRole(User $user): string
    {
        return strtolower(optional($user->role)->role_name ?? '');
    }

    /**
     * Display a listing of users
     * admin  -> all
     * manager-> all (but cannot manage admin accounts)
     */
    public function index()
    {
        $this->abortIfNotAdminOrManager();

        $query = User::with('role')->orderByDesc('id');

        // Manager: optionally hide admin users from listing (recommended)
        if ($this->isManager()) {
            $query->whereHas('role', function ($q) {
                $q->whereNotIn('role_name', ['admin', 'super admin']);
            });
        }

        $users = $query->get();

        return view('admin.user.all', compact('users'));
    }

    /**
     * Show add form
     * admin -> allowed
     * manager -> allowed (but cannot assign admin role)
     */
    public function add()
    {
        $this->abortIfNotAdminOrManager();

        if ($this->isAdmin()) {
            $roles = Role::orderBy('role_name')->get();
        } else {
            // manager: can't create admin
            $roles = Role::where('role_name', '!=', 'admin')->orderBy('role_name')->get();
        }

        return view('admin.user.add', compact('roles'));
    }

    /**
     * Store user
     */
    public function store(Request $request)
    {
        $this->abortIfNotAdminOrManager();

        $rules = [
            'name'     => 'required|string|max:255',
            'phone'    => 'required|max:15',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // admin can choose any role, manager can choose non-admin only
        if ($this->isAdmin()) {
            $rules['role_id'] = 'required|exists:roles,id';
        } else {
            $rules['role_id'] = 'required|exists:roles,id';
        }

        $request->validate($rules);

        // Role validation for manager (cannot set admin)
        if ($this->isManager()) {
            $roleName = Role::where('id', $request->role_id)->value('role_name');
            if (strtolower($roleName ?? '') === 'admin') {
                return back()->with('error', 'Manager cannot create Admin account.')->withInput();
            }
        }

        $photoName = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $photoName = 'user_' . time() . '_' . uniqid(30) . '.' . $extension;
            $file->move(public_path('uploads/users'), $photoName);
        }

        $slug = 'user_' . uniqid(20);
        $creator = Auth::id();

        $user = User::create([
            'name'       => $request->name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'username'   => $request->username,
            'password'   => Hash::make($request->password),
            'role_id'    => $request->role_id,
            'status'     => 1,
            'slug'       => $slug,
            'creator'    => $creator,
            'photo'      => $photoName,
            'created_at' => Carbon::now(),
        ]);

        // Notification (optional)
        if (Auth::check()) {
            Auth::user()->notify(new AdminActivityNotification(
                title: "New user added: {$user->name}",
                subtitle: "User created successfully",
                url: url("dashboard/user/view/" . $user->slug),
                icon: "🆕"
            ));
        }

        return redirect()->route('dashboard.user.index')->with('success', 'User added successfully!');
    }

    /**
     * Show single user
     */
    public function show($slug)
    {
        $this->abortIfNotAdminOrManager();

        $user = User::with('role')->where('slug', $slug)->firstOrFail();

        // manager cannot view admin user (optional but recommended)
    if ($this->isManager() && in_array($this->targetRole($user), ['admin','super admin'])) {
        abort(403, 'Unauthorized');
    }

        return view('admin.user.view', compact('user'));
    }

    /**
     * Edit form
     */
    public function edit($slug)
    {
        $this->abortIfNotAdminOrManager();

        $data = User::with('role')->where('slug', $slug)->firstOrFail();

        // manager cannot edit admin
        if ($this->isManager() && in_array($this->targetRole($user), ['admin','super admin'])) {
            return redirect()->route('dashboard.users.index')->with('error', 'Manager cannot edit Admin account.');
        }

        // roles list: admin sees all, manager cannot set admin
        if ($this->isAdmin()) {
            $roles = Role::orderBy('role_name')->get();
        } else {
            $roles = Role::where('role_name', '!=', 'admin')->orderBy('role_name')->get();
        }

        return view('admin.user.edit', compact('data', 'roles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, $slug)
    {
        $this->abortIfNotAdminOrManager();

        $user = User::with('role')->where('slug', $slug)->firstOrFail();

        // manager cannot update admin
        if ($this->isManager() && in_array($this->targetRole($user), ['admin','super admin'])) {
            return redirect()->route('dashboard.users.index')->with('error', 'Manager cannot update Admin account.');
        }

        $rules = [
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|max:15',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'status' => 'required|in:0,1',
            'photo'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Only admin can change role
        if ($this->isAdmin()) {
            $rules['role_id'] = 'required|exists:roles,id';
        }

        $request->validate($rules);

        // Photo upload
        if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path('uploads/users/' . $user->photo))) {
                @unlink(public_path('uploads/users/' . $user->photo));
            }

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $photoName = 'user_' . time() . '_' . uniqid(30) . '.' . $extension;
            $file->move(public_path('uploads/users'), $photoName);

            $user->photo = $photoName;
        }

        $user->name   = $request->name;
        $user->phone  = $request->phone;
        $user->email  = $request->email;
        $user->status = $request->status;
        $user->editor = Auth::id();

        // admin can change role (manager cannot)
        if ($this->isAdmin()) {
            $user->role_id = $request->role_id;
        }

        $user->save();

        return redirect()->route('dashboard.user.index')->with('success', 'User Updated Successfully');
    }

    /**
     * Delete user (admin only)
     */
    public function destroy($id)
    {
        $this->abortIfNotAdminOrManager();

        // admin only delete
        if (!$this->isAdmin()) {
            abort(403, 'Only Admin can delete users.');
        }

        $user = User::with('role')->where('id', $id)->firstOrFail();

        if (auth()->id() == $user->id) {
            return back()->with('error', 'You cannot delete your own account');
        }

        // admin should not delete another admin (optional, but safer)
        if ($this->targetRole($user) === 'admin') {
            return back()->with('error', 'You cannot delete an Admin account.');
        }

        $user->delete();

        Auth::user()->notify(new AdminActivityNotification(
            title: "User deleted: {$user->name}",
            subtitle: "User deleted successfully",
            url: url("dashboard/users/view/" . $user->slug),
            icon: "🗑️"
        ));

        return redirect()->route('dashboard.user.index')->with('success', 'User deleted successfully');
    }
}
