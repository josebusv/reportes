<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'service_type',
    ];

    /**
     * Un tipo de servicio pertenece a un reporte.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
