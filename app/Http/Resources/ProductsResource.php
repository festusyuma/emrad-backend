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
            'productSellingPrice' => $this->selling_price,
            'productSize' => $this->size,
            'productSku' => $this->sku,
            'productManufactorer' => $this->user->company->name,
            'createdAt' => $this->created_at,
            'deletedAt' => $this->deleted_at,
        ];
    }
}
