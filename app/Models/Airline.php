<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Airline extends Model
{
    public $timestamps = false;

    protected $table = 'company';
    protected $fillable = [
        'company', // varchar(50) NOT NULL,
        'commentaire', // text DEFAULT NULL,
        'photo', // text NOT NULL,
    ];
    protected $maps = [
        'company' => 'nom',
    ];

    public function vols() {
        return $this->hasMany(Vol::class, 'id_company');
    }

}