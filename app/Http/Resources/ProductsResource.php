<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'productId' => $this->id,
            'productName' => $this->name,
            'productCategory' => $this->category,
            'productDescription' => $this->description,
            'productPrice' => $this->price,
            'productImages' => $this->image,
            'productSize' => $this->size,
            'createdAt' => $this->created_at,
            'deletedAt' => $this->deleted_at,
        ];
    }
}
