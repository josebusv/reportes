<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'report_date',
        'equipment_id',
        'report_type',
        'reported_failure',
        'service_performed',
        'visit_value',
        'iva_value',
        'billing_to',
        'engineer_id',
        'service_provider_name',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    /**
     * Un reporte pertenece a un equipo.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Un reporte tiene un ingeniero (usuario).
     */
    public function engineer()
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    /**
     * Un reporte puede tener muchos tipos de servicio.
     */
    public function serviceTypes()
    {
        return $this->hasMany(ReportServiceType::class);
    }

    /**
     * Un reporte tiene una verificación inicial.
     */
    public function initialVerification()
    {
        return $this->hasOne(InitialVerification::class);
    }

    /**
     * Un reporte tiene muchos estados de ítems de checklist.
     */
    public function checklistStatuses()
    {
        return $this->hasMany(ReportChecklistStatus::class);
    }

    /**
     * Un reporte tiene muchas rutinas realizadas.
     */
    public function routinePerformed()
    {
        return $this->hasMany(ReportRoutinePerformed::class);
    }
}
