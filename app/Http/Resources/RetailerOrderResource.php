<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RetailerOrderResource extends JsonResource
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
            'order_id' => $this->id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'order_amount' => $this->order_amount,
            'created_by' => $this->created_by,
            'is_confirmed' => $this->is_confirmed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
        // return parent::toArray($request);
    }
}
