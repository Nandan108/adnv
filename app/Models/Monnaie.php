<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monnaie extends Model
{

    public $timestamps = false;
    protected $table = 'taux_monnaie';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $fillable = [
        // 'code', //  char(3) NOT NULL, // UNIQUE KEY
        'nom_monnaie', //  varchar(30) NOT NULL,
        'taux', //  float NOT NULL
    ];
}
