<?php

namespace Emrad\Http\Controllers;

use Emrad\Models\Role;
use Illuminate\Http\Request;
use Emrad\Services\RolesServices;
use Emrad\Http\Requests\RoleRequest;
use Emrad\Http\Controllers\Controller;
use Emrad\Http\Resources\RolesResource;

class RolesController extends Controller
{
    public $rolesServices;

    public function __construct(RolesServices $rolesServices)
    {
        $this->rolesServices = $rolesServices;
    }


    public function getRoles(): \Illuminate\Http\JsonResponse
    {
        $roles = $this->rolesServices->getRoles();
        return response()->json([
            'status' => 'success',
            'message' => 'Roles listed successfully ',
            'data' => RolesResource::collection($roles),
        ]);
    }


    public function createRole(RoleRequest $request): \Illuminate\Http\JsonResponse
    {
        $role = $this->rolesServices->createRole($request);
        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully ',
            'data' => new RolesResource($role),
        ]);
    }

    public function updateRole(Request $request,Role $role): \Illuminate\Http\JsonResponse
    {
        $updatedRole = $this->rolesServices->updateRole($request, $role);
        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'data' =>  new RolesResource($updatedRole),
        ]);
    }

    public function destroy(Role $role)
    {
        $status = $role->delete();
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Role Deleted!' : 'Error Deleting Role'
        ]);
    }

    public function attachPermissions(Role $role, Request $request): \Illuminate\Http\JsonResponse
    {
        $permissions = $request->activePermissions;
        $this->rolesServices->attachPermissions($role, $permissions);
        return response()->json([
            "status" => "success",
            'message' => 'Permissions synced with role successfully'
        ]);
    }

    public function getActivePermissions(Role $role): \Illuminate\Http\JsonResponse
    {
        $rolePermissions = $this->rolesServices->getActivePermissions($role);
        return response()->json([
            "status" => "success",
            'message' => 'list of Permissions synced with the role',
            'data' => ['rolePermissions'=> $rolePermissions],
        ]);
    }
}
