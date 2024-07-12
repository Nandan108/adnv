<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lieu extends Model
{
    public $timestamps = false;
    protected $table = 'lieux';
    protected $primaryKey = 'id_lieu';
    protected $guarded = ['id_lieu'];

    // CREATE TABLE `lieux` (
    //   `id_lieu` int(10) unsigned NOT NULL AUTO_INCREMENT,
    //   `code_pays` char(2) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
    //   `pays` varchar(50) NOT NULL,
    //   `region` varchar(50) DEFAULT NULL,
    //   `ville` varchar(50) NOT NULL,
    //   `lieu` varchar(150) NOT NULL DEFAULT '',
    //   `photo_lieu` varchar(250) DEFAULT NULL,
    //   PRIMARY KEY (`id_lieu`)
    // ) ENGINE=MyISAM AUTO_INCREMENT=1636 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

    public function paysObj() {
        return $this->belongsTo(Pays::class, 'code_pays', 'code');
    }

    public function memeVilleLieux() {
        return $this->hasMany(Lieu::class, 'ville_key', 'ville_key');
    }
    public function memeRegionLieux() {
        return $this->hasMany(Lieu::class, 'region_key', 'region_key');
    }
    public function tours() {
        return $this->hasMany(Tour::class, 'id_lieu', 'id_lieu');
    }

    public function aeroports() {
        return $this->hasMany(Aeroport::class, 'id_lieu', 'id_lieu');
    }

    public function hotels() {
        return $this->hasMany(Hotel::class, 'id_lieu', 'id_lieu');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint){
        return $query->whereHas($relation, $constraint)
                     ->with([$relation => $constraint]);
      }
 }
