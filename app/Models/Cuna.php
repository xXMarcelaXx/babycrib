<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuna extends Model
{
    use HasFactory;
    protected $table = 'cuna';
    protected $fillable = [
        'name',
        'key',
        'usuario_id',
        'description',
        'sensor1',
        'sensor2',
        'sensor3',
        'sensor4',
        'sensor5',
        'sensor6',
    ];
}
