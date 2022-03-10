<?php

namespace Emrad\Repositories\Contracts;


interface OrderRepositoryInterface extends BaseRepositoryInterface {
    public function fetchByProductOwner($user_id, $limit, $filters);
    public function countByProductOwner($user_id, $filters);
}
