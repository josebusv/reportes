<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportRoutinePerformed extends Model
{
    use HasFactory;

    protected $table = 'report_routine_performed'; // Nombre de la tabla
    protected $fillable = [
        'report_id',
        'routine_item_id',
        'performed',
    ];

    protected $casts = [
        'performed' => 'boolean',
    ];

    /**
     * Una rutina realizada pertenece a un reporte.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Una rutina realizada pertenece a un ítem de rutina.
     */
    public function routineItem()
    {
        return $this->belongsTo(RoutineItem::class);
    }
}
