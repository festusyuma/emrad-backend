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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRoles()
    {
        $roles = $this->rolesServices->getRoles();
        return response()->json([
            'status' => 'success',
            'message' => 'Roles listed successfully ',
            'data' => RolesResource::collection($roles),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Emrad\Http\Requests\RoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createRole(RoleRequest $request)
    {
        $role = $this->rolesServices->createRole($request);
        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully ',
            'data' => new RolesResource($role),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $role
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request,Role $role)
    {
        $updatedRole = $this->rolesServices->updateRole($request, $role);
        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully',
            'data' =>  new RolesResource($updatedRole),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $status = $role->delete();
        return response()->json([
            'status' => $status,
            'message' => $status ? 'Role Deleted!' : 'Error Deleting Role'
        ]);
    }

    /**
     * Sync Role and the request permisions
     *
     * @param  int  Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function attachPermissions(Role $role, Request $request)
    {
        $permissions = $request->activePermissions;
        $this->rolesServices->attachPermissions($role, $permissions);
        return response()->json([
            "status" => "success",
            'message' => 'Permissions synced with role successfully'
        ]);
    }

    /**
     * get array of the specified role permissions
     *
     * @param \Spatie\Permission\Models\Role $role
     *
     * @param Array $permissionsId
     */
    public function getActivePermissions(Role $role)
    {
        $rolePermissions = $this->rolesServices->getActivePermissions($role);
        return response()->json([
            "status" => "success",
            'message' => 'list of Permissions synced with the role',
            'data' => ['rolePermissions'=> $rolePermissions],
        ]);
    }
}
