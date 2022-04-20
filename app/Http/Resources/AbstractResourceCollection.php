<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class AbstractResourceCollection
 * @package App\Http\Resources
 */
abstract class AbstractResourceCollection extends ResourceCollection
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