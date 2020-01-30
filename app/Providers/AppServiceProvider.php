<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Doctrine\Repositories as DoctrineRepositories;
use WeatherPredictor\Repositories\BasicPersistRepository;
use WeatherPredictor\Repositories\ForecastRepository;

class AppServiceProvider extends ServiceProvider
{

    /**
     *  Implementation bindings
     */
    public $implementations = [
        BasicPersistRepository::class => DoctrineRepositories\DoctrineBasicPersistRepository::class,
        ForecastRepository::class => DoctrineRepositories\DoctrineForecastRepository::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->implementations as $abstract => $concrete) {
            if (is_array($concrete)) {
                $concrete = $concrete[$this->app->environment()] ?? $concrete['default'];
            }

            $this->app->bind($abstract, $concrete);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
