<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    /**
     * Un ítem de checklist puede tener muchos estados en reportes.
     */
    public function reportChecklistStatuses()
    {
        return $this->hasMany(ReportChecklistStatus::class);
    }
}
