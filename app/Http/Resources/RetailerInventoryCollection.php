<?php

namespace Emrad\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RetailerInventoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => 'success',
            'message' => 'Inventories retrieved succesfully', 
            'data' => RetailerInventoryResource::collection($this->collection) 
        ];
    }
}
