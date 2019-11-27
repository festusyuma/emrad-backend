<?php

namespace Emrad\Repositories;

use Emrad\Models\Category;
use Emrad\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface {

    public $model;

    /**
     * CategoryRepository Constructor
     *
     * @param Emrad\Models\Category $category
      */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function findBySlug($categorySlug)
    {
        return $this->model->where('slug', $categorySlug)->first();
    }

}
