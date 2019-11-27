<?php

namespace Emrad\Repositories\Contracts;


interface CategoryRepositoryInterface extends BaseRepositoryInterface {

    public function findBySlug($categorySlug);
}
