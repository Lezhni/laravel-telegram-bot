<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Testimonial as TestimonialResource;
use App\Http\Resources\TestimonialCollection as TestimonialCollectionResource;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * Class TestimonialsController
 * @package App\Http\Controllers\Api\Admin
 */
class TestimonialsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = Cache::remember('testimonials', 3600 * 30, function () {
            return Testimonial::all();
        });

        $resource = new TestimonialCollectionResource($users);
        return $resource->response();
    }

    /**
     * @param int $testimonial
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $testimonial): JsonResponse
    {
        $testimonial = Testimonial::find($testimonial);
        if (! $testimonial instanceof Testimonial) {
            return Response::json([
                'errors' => true,
                'message' => 'Отзыв не найден',
            ], 404);
        }

        try {
            $testimonial->delete();
        } catch (Throwable $e) {
            return Response::json([
                'errors' => true,
                'message' => 'Произошла непредвиденная ошибка, попробуйте еще раз',
            ], 500);
        }

        $resource = new TestimonialResource($testimonial);
        return $resource->response()->setStatusCode(202);
    }
}
