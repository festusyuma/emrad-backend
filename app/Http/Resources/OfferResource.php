<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            "offerId" => $this->id,
            "offerTitle" => $this->title,
            "offerImage" => $this->image,
            "offerDescription" => $this->description,
            "offerStartDate" => $this->start_date,
            "offerEndDate" => $this->end_date,
            "offerProduct" => new ProductsResource($this->product),
        ];
    }
}
