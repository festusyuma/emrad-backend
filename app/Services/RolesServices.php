<?php
namespace Emrad\Services;

use Emrad\Models\Role;
use Emrad\Repositories\Contracts\RoleRepositoryInterface;

class RolesServices
{
    /**
     * @var $roleRepositoryInterface
     */

    public $roleRepositoryInterface;

    public function __construct(RoleRepositoryInterface $roleRepositoryInterface)
    {
        $this->roleRepositoryInterface = $roleRepositoryInterface;
    }

    /**
     * Create a new role
     *
     * @param Request $request
     *
     * @return \Spatie\Permission\Models\Role $role
     */
    public function createRole($request)
    {
        $role = new Role;
        $role->name = $request->name;
        $role->guard_name = 'api';
        $role->save();

        return $role;
    }

    /**
     * Get all roles
     *
     * @param \Collection $role
     */
    public function getRoles()
    {
        return $this->roleRepositoryInterface->all();
    }

    /**
     * Get all roles
     *
     * @param \Collection $roleName
     */
    public function findByName($roleName)
    {
        return $this->roleRepositoryInterface->findByName($roleName);
    }

    /**
     * Get all roles
     *
     * @param \Collection $roleName
     */
    public function find($id)
    {
        return $this->roleRepositoryInterface->find($id);
    }

    /**
     * Delete the requested role
     *
     * @param Int|String $id
     *
     * @return void
     */
    public function delete($id)
    {
        $role = $this->roleRepositoryInterface->find($id);

        $role->delete();
    }

    /**
     * Fine the requested role by Id
     * Then Update the role with the $request
     *
     * @param Object $request
     * @param Int|String $id
     *
     * @return \Spatie\Permission\Models\Role
     */
    public function updateRole($request, $role)
    {
        $role->name = $request->name;
        $role->guard_name = 'api';
        $role->save();

        return $role;

    }

    /**
     * AttachPermissions
     * @param \Spatie\Permission\Models\Role $role
     * @param Array $permissions
     *
     * @return
     */
    public function attachPermissions($role,  $permissions)
    {
        return $role->syncPermissions($permissions);
    }

    /**
     * Get array of the specified role permissions
     *
     * @param \Spatie\Permission\Models\Role $role
     *
     * @return Array $permissionsId
     */
    public function getActivePermissions($role)
    {
        $collection = collect($role->permissions);

        $plucked = $collection->pluck('id');

        return $plucked->all();
    }
}
