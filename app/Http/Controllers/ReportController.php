<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\InitialVerification;
use App\Models\Report;
use App\Models\ReportChecklistStatus;
use App\Models\ReportRoutinePerformed;
use App\Models\ReportServiceType;
use App\Models\RoutineItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo reporte.
     * Pasa los datos necesarios a la vista de Inertia.
     */
    public function create()
    {
        // Obtener clientes y equipos existentes para los selects
        $clients = Client::all(['id', 'name']);
        $equipment = Equipment::all(['id', 'brand', 'model', 'inventory_number']);
        $engineers = User::where('role', 'ingeniero')->get(['id', 'name']); // Asumiendo un campo 'role' en la tabla users

        // Obtener ítems de checklist y rutinas para ambos tipos de reporte
        $endoscopyChecklistItems = ChecklistItem::where('type', 'endoscopy')->get(['id', 'name']);
        $electronicChecklistItems = ChecklistItem::where('type', 'electronic')->get(['id', 'name']);
        $endoscopyRoutineItems = RoutineItem::where('type', 'endoscopy')->get(['id', 'name']);
        $electronicRoutineItems = RoutineItem::where('type', 'electronic')->get(['id', 'name']);

        return Inertia::render('Reports/Create', [
            'clients' => $clients,
            'equipment' => $equipment,
            'engineers' => $engineers,
            'endoscopyChecklistItems' => $endoscopyChecklistItems,
            'electronicChecklistItems' => $electronicChecklistItems,
            'endoscopyRoutineItems' => $endoscopyRoutineItems,
            'electronicRoutineItems' => $electronicRoutineItems,
        ]);
    }

    /**
     * Almacena un nuevo reporte en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'client_name' => 'required|string|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_position' => 'nullable|string|max:255',
            'client_address' => 'nullable|string|max:255',
            'client_phone' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',

            'equipment_brand' => 'required|string|max:255',
            'equipment_model' => 'required|string|max:255',
            'equipment_inventory_number' => 'required|string|max:255|unique:equipment,inventory_number',
            'equipment_location' => 'nullable|string|max:255',
            'equipment_accessories' => 'nullable|string',

            'report_number' => 'required|string|max:255|unique:reports,report_number',
            'report_date' => 'required|date',
            'report_type' => 'required|in:endoscopy,electronic',
            'reported_failure' => 'nullable|string',
            'service_performed' => 'nullable|string',
            'visit_value' => 'nullable|numeric',
            'iva_value' => 'nullable|numeric',
            'billing_to' => 'nullable|string|max:255',
            'engineer_id' => 'nullable|exists:users,id',
            'service_provider_name' => 'nullable|string|max:255',

            'service_types' => 'array',
            'service_types.*' => 'in:corrective,warranty,preventive,control,courtesy',

            'initial_verification.normal' => 'boolean',
            'initial_verification.irregular' => 'boolean',
            'initial_verification.out_of_service' => 'boolean',
            'initial_verification.requested_by_client' => 'boolean',

            'checklist_status' => 'array',
            'checklist_status.*.id' => 'required|exists:checklist_items,id',
            'checklist_status.*.status' => 'required|in:good,regular,bad,not_applicable',
            'checklist_status.*.value_1' => 'nullable|integer',
            'checklist_status.*.value_2' => 'nullable|integer',
            'checklist_status.*.value_3' => 'nullable|integer',
            'checklist_status.*.value_4' => 'nullable|integer',
            'checklist_status.*.angulation_up' => 'boolean',
            'checklist_status.*.angulation_down' => 'boolean',
            'checklist_status.*.angulation_left' => 'boolean',
            'checklist_status.*.angulation_right' => 'boolean',

            'routine_performed' => 'array',
            'routine_performed.*.id' => 'required|exists:routine_items,id',
            'routine_performed.*.performed' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear o encontrar el cliente
            $client = Client::firstOrCreate(
                ['name' => $request->client_name],
                [
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_position' => $request->contact_person_position,
                    'address' => $request->client_address,
                    'phone' => $request->client_phone,
                    'email' => $request->client_email,
                ]
            );

            // 2. Crear o encontrar el equipo
            $equipment = Equipment::firstOrCreate(
                ['inventory_number' => $request->equipment_inventory_number],
                [
                    'client_id' => $client->id,
                    'brand' => $request->equipment_brand,
                    'model' => $request->equipment_model,
                    'location' => $request->equipment_location,
                    'accessories' => $request->equipment_accessories,
                ]
            );

            // 3. Crear el reporte
            $report = Report::create([
                'report_number' => $request->report_number,
                'report_date' => $request->report_date,
                'equipment_id' => $equipment->id,
                'report_type' => $request->report_type,
                'reported_failure' => $request->reported_failure,
                'service_performed' => $request->service_performed,
                'visit_value' => $request->visit_value,
                'iva_value' => $request->iva_value,
                'billing_to' => $request->billing_to,
                'engineer_id' => $request->engineer_id,
                'service_provider_name' => $request->service_provider_name,
            ]);

            // 4. Guardar tipos de servicio
            if ($request->has('service_types')) {
                foreach ($request->service_types as $serviceType) {
                    $report->serviceTypes()->create(['service_type' => $serviceType]);
                }
            }

            // 5. Guardar verificación inicial
            if ($request->has('initial_verification')) {
                $report->initialVerification()->create($request->initial_verification);
            }

            // 6. Guardar estado de checklist
            if ($request->has('checklist_status')) {
                foreach ($request->checklist_status as $itemStatus) {
                    $report->checklistStatuses()->create([
                        'checklist_item_id' => $itemStatus['id'],
                        'status' => $itemStatus['status'],
                        'value_1' => $itemStatus['value_1'] ?? null,
                        'value_2' => $itemStatus['value_2'] ?? null,
                        'value_3' => $itemStatus['value_3'] ?? null,
                        'value_4' => $itemStatus['value_4'] ?? null,
                        'angulation_up' => $itemStatus['angulation_up'] ?? false,
                        'angulation_down' => $itemStatus['angulation_down'] ?? false,
                        'angulation_left' => $itemStatus['angulation_left'] ?? false,
                        'angulation_right' => $itemStatus['angulation_right'] ?? false,
                    ]);
                }
            }

            // 7. Guardar rutinas realizadas
            if ($request->has('routine_performed')) {
                foreach ($request->routine_performed as $routine) {
                    $report->routinePerformed()->create([
                        'routine_item_id' => $routine['id'],
                        'performed' => $routine['performed'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('reports.create')->with('success', 'Reporte creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Puedes loggear el error para depuración
            \Log::error('Error al crear reporte: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al crear el reporte: ' . $e->getMessage());
        }
    }
}
