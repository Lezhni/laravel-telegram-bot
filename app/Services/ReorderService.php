<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class ReorderService
 * @package App\Services
 */
final class ReorderService
{
    /**
     * @param array|string $nestedItems
     */
    public static function parseNestedItems($nestedItems, $parentId = null)
    {
        $items = [];

        if (is_string($nestedItems)) {
            $nestedItems = json_decode($nestedItems, true);
        }

        foreach ($nestedItems as $nestedItem) {
            $items[] = [
                'id' => $nestedItem['id'],
                'parent_id' => $parentId,
            ];
            if (array_key_exists('children', $nestedItem) && count($nestedItem['children']) > 0) {
                $items = array_merge($items, self::parseNestedItems($nestedItem['children'], $nestedItem['id']));
            }
        }

        return $items;
    }

    /**
     * @param $items
     * @param null $parentId
     * @return array
     */
    public static function buildTree($items, $parentId = null): array
    {
        $branch = [];

        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        foreach ($items as $item) {
            if ($item['parent_id'] === $parentId) {
                $children = self::buildTree($items, $item['id']);
                $item['children'] = $children ?? [];
                $branch[] = $item;
            }
        }

        return $branch;
    }
}