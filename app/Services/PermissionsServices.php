<?php
namespace FlexiCreative\Services;

use FlexiCreative\Models\Permission;
use FlexiCreative\Repositories\Contracts\PermissionRepositoryInterface;

class PermissionsServices
{
    /**
     * @var $permissionRepositoryInterface
     */

    public $permissionRepositoryInterface;

    public function __construct(PermissionRepositoryInterface $permissionRepositoryInterface)
    {
        $this->permissionRepositoryInterface = $permissionRepositoryInterface;
    }

    /**
     * create a new permission
     *
     * @param Request $request
     *
     * @return FlexiCreative\Models\Permission $permission
     */
    public function createPermission($request)
    {
        $permission = new Permission;
        try {
            $permission->name = $request->name;
            $permission->guard_name = 'api';
            $permission->save();

            return $permission;
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * return all permissions
     *
     * @param \Collection $permission
     */
    public function getPermissions()
    {
        return $this->permissionRepositoryInterface->all();
    }

    /**
     * Delete the requested permission
     *
     * @param Int|String $id
     *
     * @return void
     */
    public function deletePermission($id)
    {
        $permission = $this->permissionRepositoryInterface->find($id);
        return $permission;
        $permission->delete();
    }

    /**
     * Find the requested permission by Id
     *
     * @param Illuminate\Http\Request $request
     * @param Int|String $id
     *
     * @return FlexiCreative\Models\Permission
     */
    public function updatePermission($request, $permission)
    {
        $permission->name = $request->name;
        $permission->guard_name = 'api';
        $permission->save();

        return $permission;


    }
}
