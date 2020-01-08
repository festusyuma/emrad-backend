<?php

namespace Emrad\Http\Resources;

use Emrad\Http\Resources\ProductsResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return
        [
            'status' => 'success',
            'message' => 'list of products',
            'data' => ProductsResource::collection($this->collection)
        ];
    }
}
