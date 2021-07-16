<?php

namespace Jota\OnuList\Providers;

use Illuminate\Support\ServiceProvider;
use Jota\OnuList\Class\OnuList;

class OnuListServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('Antecedentes', function () {
            return new OnuList();
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/antecedentesprocuraduria.php' => config_path('antecedentesprocuraduria.php'),
        ]);
    }
}
