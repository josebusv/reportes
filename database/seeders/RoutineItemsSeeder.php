<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoutineItem;

class RoutineItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rutinas para Reporte Técnico Endoscopia (Rutinas de VDRL)
        $endoscopyRoutines = [
            'Limpieza Interna', 'Limpieza Externa', 'Lubricación',
            'Ajuste de Angulación', 'Prueba de Fugas', 'Limpieza de Lentes',
            'Medición de ID Chip', 'Accesorios', 'Verificación Funcional',
            'Revisión Válvulas', 'Limpieza y Desinfección', 'Verificación General',
        ];

        foreach ($endoscopyRoutines as $item) {
            RoutineItem::firstOrCreate(['name' => $item, 'type' => 'endoscopy']);
        }

        // Rutinas para Reporte Técnico Electrónica (Rutinas de Mantenimiento)
        $electronicRoutines = [
            'Limpieza Interna', 'Limpieza Externa', 'Lubricación',
            'Ajuste General', 'Revisión de Insumos', 'Cables de Paciente',
            'Verificación de software', 'Calibración', 'Pruebas Funcionales',
            'Filtros', 'Baterías', 'Verificación',
        ];

        foreach ($electronicRoutines as $item) {
            RoutineItem::firstOrCreate(['name' => $item, 'type' => 'electronic']);
        }
    }
}
