<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Không sử dụng broadcast (phức tạp quá)
        /*
        Broadcast::routes();
        require base_path('routes/channels.php');
        */
    }
}
