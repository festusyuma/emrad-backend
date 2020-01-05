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
            'inventory_id' => $this->id,
            'product_id' => $this->product_id,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'is_in_stock' => $this->is_in_stock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
        // return parent::toArray($request);
    }
}
