<?php

namespace Emrad\Repositories\Contracts;


interface OrderRepositoryInterface extends BaseRepositoryInterface {
    public function fetchByProductOwner($user_id, $limit);
}
