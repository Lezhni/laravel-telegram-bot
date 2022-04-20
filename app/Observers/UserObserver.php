<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Class UserObserver
 * @package App\Observers
 */
class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function saved(User $user)
    {
        Cache::forget('users');
        Cache::forget("users-{$user->id}");
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        Cache::forget('users');
        Cache::forget("users-{$user->id}");
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        Cache::forget('users');
    }
}
