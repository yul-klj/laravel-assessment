<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

// @codingStandardsIgnoreStart
class BroadcastServiceProvider extends ServiceProvider
{
    // @codingStandardsIgnoreEnd
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
