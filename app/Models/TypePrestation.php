<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypePrestation extends Model
{
    protected $table = 'prestation_types';
    protected $fillable = [
        'name', // varchar
        'is_meal', // bool
        'photo', // text
    ];
}