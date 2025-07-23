<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'brand',
        'model',
        'inventory_number',
        'location',
        'accessories',
    ];

    /**
     * Un equipo pertenece a un cliente.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Un equipo puede tener muchos reportes.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
