<?php

namespace App\Http\Resources\Telegram;

use App\Http\Resources\AbstractResourceCollection;

/**
 * Class NodeCollection
 * @package App\Http\Resources\Telegram
 */
class NodeCollection extends AbstractResourceCollection
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
            'nodes' => $this->collection->map->only(['id', 'title', 'text']),
        ];
    }
}
