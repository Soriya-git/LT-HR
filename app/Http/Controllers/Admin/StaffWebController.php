<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\AssignManagerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * StaffWebController
 * - Uses Breeze layouts/components for consistent look
 * - Screens: index, create, edit, assign manager
 * - Enforces visibility & permissions via middleware/routes
 */
class StaffWebController extends Controller
{
    public function index(Request $request)
    {
        $viewer = $request->user();

        $staff = User::with('manager')
            ->visibleTo($viewer)
            ->orderBy('name')
            ->paginate(12);

        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        // Managers selectable for reporting line (list all managers/leaders/admins by role)
        $managers = User::role(['manager','leader','admin','super-admin'])->orderBy('name')->get(['id','name']);
        $roles = ['user','leader','manager','admin','super-admin'];

        return view('staff.create', compact('managers','roles'));
    }

    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();

        $user = new User();
        $user->fill(collect($data)->except(['password','role'])->toArray());
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.staff.index')->with('status', 'Staff created successfully');
    }

    public function edit(Request $request, $id)
    {
        $viewer = $request->user();
        $user = User::visibleTo($viewer)->findOrFail($id);

        $managers = User::role(['manager','leader','admin','super-admin'])->orderBy('name')->get(['id','name']);
        $roles = ['user','leader','manager','admin','super-admin'];

        return view('staff.edit', compact('user','managers','roles'));
    }

    public function update(UpdateStaffRequest $request, $id)
    {
        $viewer = $request->user();
        $user = User::visibleTo($viewer)->findOrFail($id);

        $data = $request->validated();
        $user->fill(collect($data)->except(['password','role'])->toArray());
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        if (array_key_exists('role', $data)) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()->route('admin.staff.index')->with('status', 'Staff updated successfully');
    }

    public function managerForm(Request $request, $id)
    {
        $viewer = $request->user();
        $user = User::visibleTo($viewer)->findOrFail($id);
        $managers = User::role(['manager','leader','admin','super-admin'])->orderBy('name')->get(['id','name']);

        return view('staff.manager', compact('user','managers'));
    }

    public function assignManager(AssignManagerRequest $request, $id)
    {
        $viewer = $request->user();
        $user = User::visibleTo($viewer)->findOrFail($id);

        $user->manager_id = $request->validated()['manager_id'];
        $user->save();

        return redirect()->route('admin.staff.index')->with('status', 'Manager assigned successfully');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->user()->can('reset passwords') || abort(403);

        $user = \App\Models\User::findOrFail($id);

        // Do not allow resetting password of a super-admin
        if ($user->hasRole('super-admin')) {
            abort(403, 'Cannot reset password for a super-admin.');
        }

        // Generate a strong random password (Laravel 11 has Str::password())
        // Fallback to random 16-char if older helper is needed.
        $newPassword = method_exists(Str::class, 'password')
            ? Str::password(16)                 // strong, includes variety
            : Str::random(16);                  // fallback

        $user->password = \Illuminate\Support\Facades\Hash::make($newPassword);
        $user->save();

        // Flash the new password (one-time display)
        return redirect()
            ->route('admin.staff.index')
            ->with('status', "Password reset for {$user->email}")
            ->with('reset_password_for', $user->email)
            ->with('reset_password_value', $newPassword);
    }
}