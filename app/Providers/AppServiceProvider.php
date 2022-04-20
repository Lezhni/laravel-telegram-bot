<?php

namespace App\Providers;

use App\Models\Telegram\Node;
use App\Models\Testimonial;
use App\Models\User;
use App\Observers\Telegram\NodeObserver;
use App\Observers\TestimonialObserver;
use App\Observers\UserObserver;
use App\Services\ReorderService;
use App\Services\TestimonialsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ReorderService::class);
        $this->app->singleton(TestimonialsService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Testimonial::observe(TestimonialObserver::class);
        Node::observe(NodeObserver::class);
    }
}
