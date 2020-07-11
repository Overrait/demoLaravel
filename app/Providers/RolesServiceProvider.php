<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class RolesServiceProvider extends ServiceProvider
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
        Blade::directive('notModeratorAndPath', function (){
            return "<?php if(!(auth()->check() && auth()->user()->hasRole('moderator') && Route::currentRouteName() === 'moderate_links')) : ?>";
        });
        Blade::directive('moderatorAndPath', function (){
            return "<?php if(auth()->check() && auth()->user()->hasRole('moderator') && Route::currentRouteName() === 'moderate_links') : ?>";
        });
        Blade::directive('endModeratorAndPath', function ($role){
            return "<?php endif; ?>";
        });
        Blade::directive('moderator', function (){
            return "<?php if(auth()->check() && auth()->user()->hasRole('moderator')) : ?>";
        });
        Blade::directive('endmoderator', function ($role){
            return "<?php endif; ?>";
        });
        Blade::directive('admin', function (){
            return "<?php if(auth()->check() && auth()->user()->hasRole('admin')) : ?>";
        });
        Blade::directive('endadmin', function ($role){
            return "<?php endif; ?>";
        });
    }
}
