<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Models\Permission;
use Emrad\Http\Controllers\Controller;
use Emrad\Services\PermissionsServices;
use Emrad\Http\Requests\PermissionRequest;
use Emrad\Http\Resources\PermissionsResource;

class PermissionsController extends Controller
{
    public $permissionsServices;

    public function __construct(PermissionsServices $permissionsServices)
    {
        $this->permissionsServices = $permissionsServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermissions()
    {
        $permissions = $this->permissionsServices->getPermissions();
        return response()->json([
            'status' => 'success',
            'message' => 'Permission listed successfully ',
            'data' => PermissionsResource::collection($permissions),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Emrad\Http\Requests\PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createPermission(PermissionRequest $request)
    {
        $permission = $this->permissionsServices->createPermission($request);
        return response()->json([
            'status' => 'success',
            'message' => 'Permission created successfully ',
            'data' => new PermissionsResource($permission),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $permission = $this->permissionsServices->updatePermission($request, $permission);
        return response()->json([
            'status' => 'success',
            'message' => 'Permission updated successfully ',
            'data' => new PermissionsResource($permission),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Emrad\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function deletePermission(Permission $permission)
    {
        $status = $permission->delete();
        return response()->json([
            'status' => 'success',
            'message' => $status ? 'Permission Deleted!' : 'Error Deleting Permission',
            'data' => $status,
        ]);
    }
}
