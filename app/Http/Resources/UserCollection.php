<?php

namespace App\Http\Resources;

/**
 * Class UserCollection
 * @package App\Http\Resources
 */
class UserCollection extends AbstractResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'users' => $this->collection->map->only(['id', 'name', 'email']),
        ];
    }
}