<?php

namespace App\Providers;

use App\Comment;
use App\design;
use App\Invitation;
use App\Message;
use App\Policies\CommentPolicy;
use App\Policies\DesignPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\MessagePolicy;
use App\Policies\TeamPolicy;
use App\Team;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        design::class => DesignPolicy::class,
        Comment::class => CommentPolicy::class,
        Team::class => TeamPolicy::class,
        Invitation::class => InvitationPolicy::class,
        Message::class => MessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
