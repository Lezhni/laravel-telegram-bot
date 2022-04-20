<?php

namespace App\Observers;

use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

/**
 * Class TestimonialObserver
 * @package App\Observers
 */
class TestimonialObserver
{
    /**
     * Handle the Testimonial "created" event.
     *
     * @param \App\Models\Testimonial $testimonial
     * @return void
     */
    public function saved(Testimonial $testimonial)
    {
        Cache::forget('testimonials');
    }

    /**
     * Handle the Testimonial "deleted" event.
     *
     * @param \App\Models\Testimonial $testimonial
     * @return void
     */
    public function deleted(Testimonial $testimonial)
    {
        Cache::forget('testimonials');
    }
}
