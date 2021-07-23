<?php

namespace Jota\AntecedentesProcuraduria\Providers;

use Jota\AntecedentesProcuraduria\Classes\AntecedentesProcuraduria;
use Illuminate\Support\ServiceProvider;

class AntecedentesProcuraduriaProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->bind('Antecedentes', function () {
            return new AntecedentesProcuraduria();
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
