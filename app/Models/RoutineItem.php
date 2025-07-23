<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Un ítem de rutina puede tener muchas rutinas realizadas en reportes.
     */
    public function reportRoutinePerformed()
    {
        return $this->hasMany(ReportRoutinePerformed::class);
    }
}
