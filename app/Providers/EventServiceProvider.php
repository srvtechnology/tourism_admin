<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Jobs\DzonkhagCreated;
use App\Jobs\RegionCreated;
use App\Jobs\TestJob;


class EventServiceProvider extends ServiceProvider
{
   
    public function boot()
    {
        \App::bindMethod(TestJob::class . '@handle' , function($job) {
             return $job->handle();
        });
        \App::bindMethod(DzonkhagCreated::class . '@handle' , function($job) {
             return $job->handle();
        });

        \App::bindMethod(RegionCreated::class . '@handle' , function($job) {
             return $job->handle();
        });
    }
}
