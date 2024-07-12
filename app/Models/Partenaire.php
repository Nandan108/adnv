<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Partenaire extends Model
{
    public $timestamps = false;
    protected $table = 'partenaire';
    protected $primaryKey = 'id_partenaire';

    protected $fillable = [
        'nom_partenaire', // varchar(50)
        'id_lieu', // int(3) [0]
        'adresse', // varchar(100)
        'codepostal', // varchar(10)
        'telephone_hotel', // varchar(20)
        'adresse_reservation', // varchar(50)
    ];

    public function lieu() {
        return $this->belongsTo(Lieu::class, 'id_lieu', 'id_lieu');
    }
}