<?php

namespace App\Providers;
use App\Models\AssignLead;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->resolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('app:send-task-reminder')->everySecond();
        });
    }

}
