<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aeroport extends Model
{
    protected $table = 'aeroport';
    public $timestamps = false;
    protected $primaryKey = 'id_aeroport';
    protected $fillable = [
        'code_aeroport', // UNIQUE KEY
        'aeroport',
        'id_lieu',
        'pays',
        'ville',
        'taxe',
        'taxe_enfant',
        'taxe_bebe',
    ];

    public function lieu() {
        return $this->belongsTo(Lieu::class, 'id_lieu', 'id_lieu');
    }
    public function vols_depart() {
        return $this->hasMany(Vol::class, 'code_apt_depart', 'code_aeroport');
    }
    public function vols_transit() {
        return $this->hasMany(Vol::class, 'code_apt_transit', 'code_aeroport');
    }
    public function vols_arrive() {
        return $this->hasMany(Vol::class, 'code_apt_arrive', 'code_aeroport');
    }
 }