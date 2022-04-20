<?php

namespace App\Services;

use App\Models\Testimonial;

/**
 * Class TestimonialsService
 * @package App\Services
 */
final class TestimonialsService
{
    /**
     * @param string $author
     * @param string $text
     * @return \App\Models\Testimonial|null
     * @throws \Throwable
     */
    public function createTestimonial(string $author, string $text): ?Testimonial
    {
        $testimonial = new Testimonial();
        $testimonial->fill(['author' => $author, 'text' => $text]);
        $testimonial->saveOrFail();

        return $testimonial;
    }
}