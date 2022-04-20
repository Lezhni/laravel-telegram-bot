<?php

namespace App\Http\Resources\Telegram;

use App\Http\Resources\AbstractResource;

/**
 * Class Node
 * @package App\Http\Resources\Telegram
 */
class Node extends AbstractResource
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
            'node' => $this->resource->only(['id', 'title', 'text', 'links']),
        ];
    }
}
