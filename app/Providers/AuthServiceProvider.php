<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\Action;
use App\Models\Comment;
use App\Models\Donation;
use App\Policies\CampaignPolicy;
use App\Policies\EventPolicy;
use App\Policies\ActionPolicy;
use App\Policies\DonationPolicy;
use App\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        Campaign::class => CampaignPolicy::class,
        Event::class => EventPolicy::class,
        Action::class => ActionPolicy::class,
        Comment::class => CommentPolicy::class,
        Donation::class => DonationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }
}
