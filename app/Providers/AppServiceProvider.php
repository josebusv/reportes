<?php

namespace App\Providers;

use App\Models\Report;
use App\Observers\ReportObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Registra el ReportObserver
        Report::observe(ReportObserver::class);

        // Configura el número de inicio para reportes
        // Asegúrate de que esta configuración se cargue correctamente
        config(['app.report_starting_number' => env('REPORT_STARTING_NUMBER', 1000)]);
    }
}
