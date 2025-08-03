<?php

namespace App\Observers;

use App\Models\Report;
use Illuminate\Support\Facades\Log; // Para depuración

class ReportObserver
{
    /**
     * Handle the Report "creating" event.
     * Genera el número de reporte antes de que se cree el registro.
     */
    public function creating(Report $report): void
    {
        // Si el número de reporte ya está establecido (ej. manualmente), no lo sobrescribimos
        if ($report->report_number) {
            return;
        }

        // Obtiene el último número de reporte de la base de datos
        $lastReport = Report::latest('id')->first(); // O latest('report_number') si es numérico y se incrementa

        $startingNumber = config('app.report_starting_number', 1000); // Obtiene de .env o usa 1000 por defecto

        if ($lastReport) {
            // Extrae el número del último reporte y lo incrementa
            // Asume que el número de reporte es puramente numérico.
            // Si tiene prefijos (ej. "REP-001"), necesitarás una lógica más compleja para extraer el número.
            $lastNumber = (int) filter_var($lastReport->report_number, FILTER_SANITIZE_NUMBER_INT);
            $newNumber = max($lastNumber + 1, $startingNumber);
        } else {
            // Si no hay reportes, usa el número de inicio
            $newNumber = $startingNumber;
        }

        // Formatea el número de reporte (ej. con ceros a la izquierda)
        // Puedes ajustar el formato según tus necesidades (ej. 'REP-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT))
        $report->report_number = str_pad($newNumber, 5, '0', STR_PAD_LEFT); // Ejemplo: 01000, 01001
        Log::info("Generando número de reporte: " . $report->report_number);
    }
}
