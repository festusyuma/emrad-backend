<?php

namespace Emrad\Services;

use Emrad\Models\Category;
use Emrad\Repositories\Contracts\CategoryRepositoryInterface;
use Emrad\Util\CustomResponse;

class CategoriesServices
{
    /**
     * @var $categoryRepositoryInterface
     */
    public $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    /**
     * Create a new category Instance
     *
     * @param String $categoryName
     * @param String $categoryDescription
     * @param String $categoryLogo
     *
     * @return Emrad\Models\Category
     */

    public function createCategory(
        $categoryName,
        $categoryDescription,
        $categoryLogo
    )
    {
        // instatiate a new class
        $category = new Category();

        try {
            $category->name = $categoryName;
            $category->slug = str_slug($categoryName, '-');
            $category->description = $categoryDescription;
            $category->logo = $categoryLogo;
            $category->save();

            return $category;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Update an existing category Instance
     *
     * @param Emrad\Models\Category $category
     * @param String $categoryName
     * @param String $categoryDescription
     * @param String $categoryLogo
     *
     * @return Emrad\Models\Category
      */

      public function updatecategory(
        Category $category,
        $categoryName,
        $categoryDescription,
        $categoryLogo
    )
    {
        try {
            $category->name = $categoryName;
            $category->slug = str_slug($categoryName, '-');
            $category->description = $categoryDescription;
            $category->logo = $categoryLogo;
            $category->save();

            return $category;

        } catch (\Exception $e) {
            return $e;
        }

    }

    public function getCategories(): CustomResponse
    {
        try {
            $categories = $this->categoryRepositoryInterface->all();
            return CustomResponse::success($categories);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    /**
     * return all find category
     *
     * @param \Collection $category
     */
    public function getSingleCategory($slug)
    {
        return $this->categoryRepositoryInterface->findBySlug($slug);

    }

    /**
     * return category by name
     *
     * @param \Collection $category
     */
    public function getByName($name)
    {
        return $this->categoryRepositoryInterface->getByName($name);
    }
}


