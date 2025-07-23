<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'normal',
        'irregular',
        'out_of_service',
        'requested_by_client',
    ];

    protected $casts = [
        'normal' => 'boolean',
        'irregular' => 'boolean',
        'out_of_service' => 'boolean',
        'requested_by_client' => 'boolean',
    ];

    /**
     * Una verificación inicial pertenece a un reporte.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
