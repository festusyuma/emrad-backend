<?php  

namespace FlexiCreative\Repositories;

use FlexiCreative\Models\Role;
use FlexiCreative\Repositories\Contracts\RoleRepositoryInterface;


class RoleRepository extends BaseRepository implements RoleRepositoryInterface {

    public $model;
    
    /**
     * RoleRepository Constructor
     * 
     * @param Spatie\Permission\Models\Role $role
      */
    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    /**
     * Find a role by its Role Name 
     */
    public function findByName($roleName)
    {
        return $this->model->findByName($roleName);
    }
}