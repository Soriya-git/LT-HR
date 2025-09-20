<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\AssignManagerRequest;
use App\Http\Resources\StaffResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/**
 * Staff management:
 * - list staff visible to requester
 * - create staff with role
 * - show a staff
 * - update staff fields (and optional role)
 * - assign/change manager
 */
class StaffController extends Controller
{
    public function index(Request $request)
    {
        $viewer = $request->user();

        $query = User::with('manager')
            ->visibleTo($viewer)
            ->orderBy('name');

        if ($dept = $request->query('department')) {
            $query->where('department', $dept);
        }

        return StaffResource::collection($query->paginate(20));
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

        // assign role
        $user->syncRoles([$data['role']]);

        return (new StaffResource($user->load('manager')))
            ->additional(['message' => 'Staff created successfully']);
    }

    public function show(Request $request, $id)
    {
        $viewer = $request->user();
        $user = User::with('manager')->visibleTo($viewer)->findOrFail($id);
        return new StaffResource($user);
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

        return (new StaffResource($user->load('manager')))
            ->additional(['message' => 'Staff updated successfully']);
    }

    public function assignManager(AssignManagerRequest $request, $id)
    {
        $viewer = $request->user();
        $user = User::visibleTo($viewer)->findOrFail($id);

        $user->manager_id = $request->validated()['manager_id'];
        $user->save();

        return (new StaffResource($user->load('manager')))
            ->additional(['message' => 'Manager assigned successfully']);
    }
}