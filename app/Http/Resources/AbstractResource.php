<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AbstractResource
 * @package App\Http\Resources
 */
abstract class AbstractResource extends JsonResource
{
    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request): array
    {
        return ['errors' => false];
    }
}