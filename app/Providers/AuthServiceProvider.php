<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensCan([
            'can-edit' => 'Can edit event',
            'can-add' => 'Can add event',
            'can-delete' => 'Can delete event',
            'can-view' => 'Can view event',
            'can-buy' => 'Can buy ticket',
            'can-join' => 'Can join event',
        ]);
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addDay(1));
        Passport::personalAccessTokensExpireIn(Carbon::now()->addMinutes(15));
    }
}
