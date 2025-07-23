<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistItem;

class ChecklistItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Items para Reporte Técnico Endoscopia
        $endoscopyItems = [
            'Imagen', 'Intensidad de Luz', 'Angulación', 'Lentes', 'Anillo Protector',
            'Pegantes', 'Tuerca de Inserción', 'Tubo Angulador', 'Boquilla de Control',
            'Boquilla de Lubricación', 'Guía de Lavado Distal', 'Manilla de Freno',
            'Up de la Imagen', 'Lente Objetiva', 'Balones', 'Pago de Pinza',
            'Lente del Anillo', 'Tubo Universal', 'Canal de Aspiración', 'Flujo de Succión',
            'Flujo de Aire e Irrigación', 'Obturador', 'Lente de Vástago', 'Eje Transmisión',
            'Pines del Conector', 'Manguera de Angulación',
        ];

        foreach ($endoscopyItems as $item) {
            ChecklistItem::firstOrCreate(['name' => $item, 'type' => 'endoscopy']);
        }

        // Items para Reporte Técnico Electrónica
        $electronicItems = [
            'Carcasa', 'Componentes', 'Conectores, conexiones y/o Cables',
            'Interruptores, perillas y/o Controladores', 'Pantalla y/o Display',
            'Selectores y Controladores del equipo', 'Rodamientos (Rodachines, Agujas, Ruedillas)',
            'Fuente de Voltaje / Adaptadores y/o Cargadores', 'Filtros y/o Tapas',
            'Sensores y Traductores', 'Batería', 'Sistema Eléctrico', 'Sistema Electrónico',
            'Sistema Hidráulico', 'Sistema Mecánico', 'Sistema Neumático', 'Sistema Óptico',
            'Mangueras y Manguera', 'Bombillos', 'Válvulas de control y/o Seguridad',
            'Orings y/o empaques', 'Otros',
        ];

        foreach ($electronicItems as $item) {
            ChecklistItem::firstOrCreate(['name' => $item, 'type' => 'electronic']);
        }
    }
}
