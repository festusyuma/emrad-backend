<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockHistoryResource extends JsonResource
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
            // 'userId' => $this->user_id,
            'Product' => new ProductsResource($this->product),
            'inventoryId' => $this->inventory_id,
            'stockBalance' => $this->stock_balance,
            'newStockBalance' => $this->new_stock_balance,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
        // return parent::toArray($request);
    }
}
