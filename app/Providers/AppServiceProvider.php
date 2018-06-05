<?php

namespace App\Providers;


use App\Services\DTO\Point;
use App\Services\DTO\Ship;

use App\Services\GameService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(Ship::class, function($app){

            return (new Ship())->map($app->request);

        });

        $this->app->bind(Point::class, function($app){

            return (new Point())->map($app->request);

        });

        $this->app->bind(GameService::class, function ($app){

            return (new GameService())->session($app->request->session()->getId());

        });

    }
}
