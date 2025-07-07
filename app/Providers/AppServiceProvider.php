<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // OR force HTTPS in all environments except local/development
        // if (!app()->environment('local', 'development')) {
        //     URL::forceScheme('https');
        // }
        
        View::composer(['layouts.navigation', 'layouts.app'], function ($view) {
            $project = null;
            
            // Try to get project from current route parameter
            if (request()->route() && request()->route()->hasParameter('project')) {
                $project = request()->route()->parameter('project');
            }
            
            // If no project in route, get user's latest project
            if (!$project && auth()->check()) {
                $project = auth()->user()->projects()->latest()->first();
            }
            
            $view->with('currentProject', $project);
        });
    }
}