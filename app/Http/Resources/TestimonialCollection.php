<?php

namespace App\Http\Resources;

/**
 * Class TestimonialCollection
 * @package App\Http\Resources
 */
class TestimonialCollection extends AbstractResourceCollection
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
            'testimonials' => $this->collection->map->only(['id', 'author', 'text', 'created_at']),
        ];
    }
}