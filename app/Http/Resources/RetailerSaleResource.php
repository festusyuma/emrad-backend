<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RetailerSaleResource extends JsonResource
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
            'saleId' => $this->id,
            'saleProduct' => new ProductsResource($this->product),
            'quantity' => $this->quantity,
            'unitPrice' => $this->unit_price,
            'saleAmount' => $this->sale_amount,
            'createdBy' => $this->created_by,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
        // return parent::toArray($request);
    }
}
