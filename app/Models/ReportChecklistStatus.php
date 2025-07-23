<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportChecklistStatus extends Model
{
    use HasFactory;

    protected $table = 'report_checklist_status'; // Nombre de la tabla
    protected $fillable = [
        'report_id',
        'checklist_item_id',
        'status',
        'value_1',
        'value_2',
        'value_3',
        'value_4',
        'angulation_up',
        'angulation_down',
        'angulation_left',
        'angulation_right',
    ];

    protected $casts = [
        'angulation_up' => 'boolean',
        'angulation_down' => 'boolean',
        'angulation_left' => 'boolean',
        'angulation_right' => 'boolean',
    ];

    /**
     * Un estado de checklist pertenece a un reporte.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Un estado de checklist pertenece a un ítem de checklist.
     */
    public function checklistItem()
    {
        return $this->belongsTo(ChecklistItem::class);
    }
}
