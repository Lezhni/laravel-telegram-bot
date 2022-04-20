<?php

namespace App\Http\Controllers\Api\Admin\Telegram;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Telegram\Node\ReorderRequest;
use App\Http\Requests\Api\Telegram\Node\StoreUpdateRequest;
use App\Http\Resources\Telegram\Node as NodeResource;
use App\Http\Resources\Telegram\NodeCollection as NodeCollectionResource;
use App\Models\Telegram\Node;
use App\Services\ReorderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * Class NodesController
 * @package App\Http\Controllers\Api\Telegram
 */
class NodesController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $nodes = Cache::remember('telegram-nodes', 3600 * 30, function () {
            return Node::all();
        });

        $resource = new NodeCollectionResource($nodes);
        return $resource->response();
    }

    /**
     * @param \App\Http\Requests\Api\Telegram\Node\StoreUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUpdateRequest $request): JsonResponse
    {
        $nodeData = $request->validated();

        try {
            $node = new Node($nodeData);
            $node->saveOrFail();
        } catch (Throwable $e) {
            report($e);
            return Response::json([
                'errors' => true,
                'message' => 'Произошла непредвиденная ошибка, попробуйте еще раз',
            ], 500);
        }

        $resource = new NodeResource($node);
        return $resource->response()->setStatusCode(201);
    }

    /**
     * @param int $node
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $node)
    {
        $node = Cache::remember("telegram-nodes-{$node}", 3600 * 30, function () use ($node) {
            return Node::find($node);
        });
        if (! $node instanceof Node) {
            return Response::json([
                'errors' => true,
                'message' => 'Пункт не найден',
            ], 404);
        }

        $resource = new NodeResource($node);
        return $resource->response();
    }

    /**
     * @param \App\Http\Requests\Api\Telegram\Node\StoreUpdateRequest $request
     * @param int $node
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreUpdateRequest $request, int $node): JsonResponse
    {
        $node = Cache::remember("telegram-nodes-{$node}", 3600 * 30, function () use ($node) {
            return Node::find($node);
        });
        if (! $node instanceof Node) {
            return Response::json([
                'errors' => true,
                'message' => 'Пункт не найден',
            ], 404);
        }

        $newNodeData = $request->validated();

        try {
            $node->fill($newNodeData);
            $node->saveOrFail();
        } catch (Throwable $e) {
            return Response::json([
                'errors' => true,
                'message' => 'Произошла непредвиденная ошибка, попробуйте еще раз',
            ], 500);
        }

        $resource = new NodeResource($node);
        return $resource->response()->setStatusCode(204);
    }

    /**
     * @param int $node
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $node): JsonResponse
    {
        $node = Node::find($node);
        if (! $node instanceof Node) {
            return Response::json([
                'errors' => true,
                'message' => 'Пункт не найден',
            ], 404);
        }

        try {
            $node->delete();
        } catch (Throwable $e) {
            return Response::json([
                'errors' => true,
                'message' => 'Произошла непредвиденная ошибка, попробуйте еще раз',
            ], 500);
        }

        $resource = new NodeResource($node);
        return $resource->response()->setStatusCode(202);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReorderedList(): JsonResponse
    {
        $nodes = Node::all(['id', 'title as text', 'parent_id']);
        $reorderedNodes = ReorderService::buildTree($nodes);

        return Response::json([
            'errors' => false,
            'data' => ['items' => $reorderedNodes],
        ]);
    }

    /**
     * @param \App\Http\Requests\Api\Telegram\Node\ReorderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReorder(ReorderRequest $request): JsonResponse
    {
        $nestedItems = $request->getContent();
        if (! is_string($nestedItems)) {
            abort(422);
        }

        $parsedNodes = ReorderService::parseNestedItems($nestedItems);
        foreach ($parsedNodes as $parsedNode) {
            Node::where('id', $parsedNode['id'])
                ->update(['parent_id' => $parsedNode['parent_id']]);
        }

        return Response::json([
            'errors' => false,
            'data' => ['items' => $parsedNodes],
        ]);
    }
}
