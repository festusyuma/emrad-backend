<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RetailerInventoryResource extends JsonResource
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
            'inventoryId' => $this->id,
            'inventoryProduct' => new ProductsResource($this->product),
            'costPrice' => $this->cost_price,
            'sellingPrice' => $this->selling_price,
            'quantity' => $this->quantity,
            'isInStock' => $this->is_in_stock,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
        // return parent::toArray($request);
    }
}
