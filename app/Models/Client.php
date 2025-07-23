<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Define los campos que pueden ser asignados masivamente
    protected $fillable = [
        'name',
        'contact_person_name',
        'contact_person_position',
        'address',
        'phone',
        'email',
    ];

    /**
     * Un cliente puede tener muchos equipos.
     */
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
