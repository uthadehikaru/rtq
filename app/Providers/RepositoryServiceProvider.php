<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\MemberRepositoryInterface;
use App\Interfaces\CourseRepositoryInterface;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\BatchMemberRepositoryInterface;
use App\Interfaces\PeriodRepositoryInterface;
use App\Interfaces\PaymentRepositoryInterface;
use App\Repositories\MemberRepository;
use App\Repositories\CourseRepository;
use App\Repositories\BatchRepository;
use App\Repositories\BatchMemberRepository;
use App\Repositories\PeriodRepository;
use App\Repositories\PaymentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() 
    {
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(BatchRepositoryInterface::class, BatchRepository::class);
        $this->app->bind(BatchMemberRepositoryInterface::class, BatchMemberRepository::class);
        $this->app->bind(PeriodRepositoryInterface::class, PeriodRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
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
