<?php  

namespace FlexiCreative\Repositories;

use FlexiCreative\Models\Permission;
use FlexiCreative\Repositories\Contracts\PermissionRepositoryInterface;


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