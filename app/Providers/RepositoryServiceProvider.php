<?php

namespace App\Providers;

use App\Repositories\Contracts\ITeam;
use App\Repositories\Eloquent\TeamRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\IUser;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Contracts\IComment;
use App\Repositories\Eloquent\DesignRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\CommentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IDesign::class,DesignRepository::class);
        $this->app->bind(IUser::class,UserRepository::class);
        $this->app->bind(IComment::class,CommentRepository::class);
        $this->app->bind(ITeam::class,TeamRepository::class);
    }
}
