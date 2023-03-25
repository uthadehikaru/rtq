<?php

namespace App\Providers;

use App\Events\BatchChanged;
use App\Events\BiodataUpdated;
use App\Events\MemberActivated;
use App\Events\ProfileUpdated;
use App\Listeners\ActivityLogin;
use App\Listeners\LogBatchChanged;
use App\Listeners\LogMemberActivated;
use App\Listeners\LogProfileUpdated;
use App\Listeners\NotifyBiodataUpdated;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            ActivityLogin::class,
        ],
        MemberActivated::class => [
            LogMemberActivated::class,
        ],
        BatchChanged::class => [
            LogBatchChanged::class,
        ],
        BiodataUpdated::class => [
            NotifyBiodataUpdated::class,
        ],
        ProfileUpdated::class => [
            LogProfileUpdated::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
