<?php

namespace Emrad\Repositories\Contracts;


interface OrderRepositoryInterface extends BaseRepositoryInterface {
    public function fetchByProductOwner($user_id, $limit, $filters);
    public function countByProductOwner($user_id, $filters);
    public function countStockByProductOwner($user_id, $filters);
    public function countAmountProductOwner($user_id, $filters);
    public function saleHistoryByProductOwner($user_id, $group);
    public function topRetailersByProductOwner($user_id, $filters);
}
