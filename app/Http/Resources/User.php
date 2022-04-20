<?php

namespace App\Http\Resources;

/**
 * Class User
 * @package App\Http\Resources
 */
class User extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'user' => $this->resource->only(['id', 'name', 'email']),
        ];
    }
}
