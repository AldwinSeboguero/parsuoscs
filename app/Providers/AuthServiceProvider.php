<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Auth;
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
        // if(Auth::guest()){
        //     return redirect('login');
        // }
        $this->registerPolicies();
        Gate::define('manage-users', function($user){
            return $user->hasAnyRoles(['admin','pd','dean','cashier','osas','library','registrar','registrarstaff','stcouncil','adviser','principal']);
        });
        Gate::define('adviser-users', function($user){
            return $user->hasAnyRoles(['adviser','admin']);
        });
        Gate::define('principal-users', function($user){
            return $user->hasAnyRoles(['principal','admin']);
        });
        Gate::define('student-users', function($user){
            return $user->hasAnyRoles(['student','admin']);
        });
        Gate::define('admin', function($user){
            return $user->hasAnyRoles(['admin','admin']);
        });
        Gate::define('studentcouncil-user', function($user){
            return $user->hasAnyRoles(['stcouncil','admin']);
        });
        Gate::define('pd-users', function($user){
            return $user->hasAnyRoles(['pd','admin','pd','dean','cashier','osas','library','registrar','registrarstaff','stcouncil','adviser','principal']);
        });
        Gate::define('dean-users', function($user){
            return $user->hasAnyRoles(['dean','admin']);
        });
        Gate::define('osas-users', function($user){
            return $user->hasAnyRoles(['osas','osasstaff','admin']);
        });
        Gate::define('library-users', function($user){
            return $user->hasAnyRoles(['library','librarystaff','admin']);
        });
        Gate::define('cashier-users', function($user){
            return $user->hasAnyRoles(['cashier','admin']);
        });
        Gate::define('registrar-users', function($user){
            return $user->hasAnyRoles(['registrar','registrarstaff','admin']);
        });
        Passport::routes();
        //
    }
}
