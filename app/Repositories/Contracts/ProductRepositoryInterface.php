<?php

namespace Emrad\Repositories\Contracts;


interface ProductRepositoryInterface extends BaseRepositoryInterface {
    public function paginateAllByUser($user_id, $limit, $relations);
}
