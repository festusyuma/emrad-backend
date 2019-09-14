<?php  

namespace Emrad\Repositories;

use Emrad\Models\Permission;
use Emrad\Repositories\Contracts\PermissionRepositoryInterface;


class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface {

    public $model;
    
    /**
     * PermissionRepository Constructor
     * 
     * @param Spatie\Permission\Models\Permission $permission
      */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }
}