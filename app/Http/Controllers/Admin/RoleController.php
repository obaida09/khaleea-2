<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function show($roleId)
    {
        $role = Role::findOrFail($roleId);

        return response()->json([
            'role' => $role,
            'permissions' => $role->permissions
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'sometimes',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Create the role
        $role = Role::create(['name' => $request->name]);

        // Get the permissions from the request
        $permissions = $request->input('permissions');

        // Assign the permissions to the role
        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Role created and permissions assigned successfully',
            'role' => $role,
            'permissions' => $role->permissions
        ]);
    }

    public function update(Request $request, $roleId)
    {
        // Validate the request
        $request->validate([
            'permissions' => 'required',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Find the role
        $role = Role::findOrFail($roleId);

        // Get the permissions from the request
        $permissions = $request->input('permissions');

        // Update the permissions for the role
        // $role->syncPermissions($permissions);
        $role->givePermissionTo($permissions);

        return response()->json([
            'message' => 'Permissions updated successfully',
            'role' => $role,
            'permissions' => $role->permissions
        ]);
    }

    public function removeRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);
        $role = $request->input('role');

        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return response()->json(['message' => 'Role removed successfully']);
        }

        return response()->json(['message' => 'User does not have the specified role'], 400);
    }

    public function getPermissions()
    {
        $Permissions = Permission::all();
        return response()->json($Permissions);
    }

    public function showUserPermissions($userId)
    {
        $user = User::findOrFail($userId);
        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions();

        return response()->json([
            'user' => $user->name,
            'roles' => $roles,
            'permissions' => $permissions->pluck('name'),
        ]);
    }

    public function addPermissionToRole(Request $request, $roleId)
    {
        // Validate the request
        $request->validate([
            'permission' => 'required|string|exists:permissions,name',
        ]);

        // Find the role by ID
        $role = Role::find($roleId);

        // Get the permission from the request
        $permission = Permission::findByName($request->permission);

        // Add the permission to the role
        $role->givePermissionTo($permission);

        return response()->json([
            'message' => 'Permission added successfully',
            'role' => $role,
            'permissions' => $permission
        ]);
    }



    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);
        $user->assignRole($request->role);

        return response()->json(['message' => 'Role assigned successfully']);
    }




}
