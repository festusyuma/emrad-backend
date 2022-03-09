<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Models\Category;
use Emrad\Services\CategoriesServices;
use Emrad\Http\Resources\CategoryResource;

class CategoriesController extends Controller
{
    /**
     * @var CategoriesServices $categoriesServices
     */
    public $categoriesServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoriesServices $categoriesServices)
    {
        $this->categoriesServices = $categoriesServices;
    }


    public function getCategories()
    {
        $result = $this->categoriesServices->getCategories();

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\Category  $categorySlug
     * @return \Illuminate\Http\Response
     */
    public function getSingleCategory($categorySlug)
    {
        $category = $this->categoriesServices->getSingleCategory($categorySlug);
        return response([
            'status' => 'success',
            'message' => 'category detail',
            'data' => new CategoryResource($category)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createCategory(Request $request)
    {
        $category = $this->categoriesServices->createCategory(  $request->categoryName,
                                                                $request->categoryDescription,
                                                                $request->categoryLogo
                                                            );
        return response()->json([
            'status' => 'success',
            'message' => 'category created successfully ',
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function updatecategory(Request $request, Category $category)
    {
        $category = $this->categoriesServices->updatecategory(  $category,
                                                                $request->categoryName,
                                                                $request->categoryDescription,
                                                                $request->categoryLogo
                                                            );
        return response()->json([
            'status' => 'success',
            'message' => 'category updated successfully ',
            'data' => new CategoryResource($category),
        ]);
    }
}
