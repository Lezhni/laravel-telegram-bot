<?php

namespace App\Http\Resources;

/**
 * Class Testimonial
 * @package App\Http\Resources
 */
class Testimonial extends AbstractResource
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
            'testimonial' => $this->resource->only(['id', 'author', 'text', 'created_at']),
        ];
    }
}
