<?php

namespace App\Providers;

use App\Interfaces;
use App\Repositories;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Interfaces\MemberRepositoryInterface::class, Repositories\MemberRepository::class);
        $this->app->bind(Interfaces\CourseRepositoryInterface::class, Repositories\CourseRepository::class);
        $this->app->bind(Interfaces\BatchRepositoryInterface::class, Repositories\BatchRepository::class);
        $this->app->bind(Interfaces\BatchMemberRepositoryInterface::class, Repositories\BatchMemberRepository::class);
        $this->app->bind(Interfaces\PeriodRepositoryInterface::class, Repositories\PeriodRepository::class);
        $this->app->bind(Interfaces\PaymentRepositoryInterface::class, Repositories\PaymentRepository::class);
        $this->app->bind(Interfaces\TeacherRepositoryInterface::class, Repositories\TeacherRepository::class);
        $this->app->bind(Interfaces\ScheduleRepositoryInterface::class, Repositories\ScheduleRepository::class);
        $this->app->bind(Interfaces\PresentRepositoryInterface::class, Repositories\PresentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
