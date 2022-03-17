<?php

namespace Emrad\Repositories\Contracts;


interface ProductRepositoryInterface extends BaseRepositoryInterface {
    public function paginateAllByUser($user_id, $limit, $relations);
    public function countAllByUser($user_id, $filters);
    public function countAllStockByUser($user_id, $filters);
}
