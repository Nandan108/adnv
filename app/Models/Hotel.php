<?php
namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hotel extends Model
{
    public $timestamps = false;
    protected $table = 'hotels_new';
    protected $primaryKey = 'id';

    protected $fillable = [
        'hotel', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'etoile', // varchar(10) COLLATE latin1_general_ci NOT NULL,
        'situation_hotel', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'pays', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'destination', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'lieu', // varchar(250) COLLATE latin1_general_ci NOT NULL,
        'ville', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'id_lieu', // int(10) DEFAULT NULL,
        'adresse', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'postal', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'tel', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'lien', // varchar(250) COLLATE latin1_general_ci NOT NULL,
        'mail', // varchar(50) COLLATE latin1_general_ci NOT NULL,
        'photo', // varchar(250) COLLATE latin1_general_ci NOT NULL,
        'adulte_only', // int(2) NOT NULL,
        'repas', // varchar(100) COLLATE latin1_general_ci NOT NULL,
        'slug', // varchar(100) COLLATE latin1_general_ci NOT NULL,
        'coup_coeur', // int(1) NOT NULL,
        'decouvrir', // int(1) NOT NULL DEFAULT 0,
    ];

    public function lieu(): BelongsTo {
        return $this->belongsTo(Lieu::class, 'id_lieu', 'id_lieu');
    }

    public function chambres(): HasMany {
        return $this->hasMany(Chambre::class, 'id_hotel');
    }

    public function transferts(): HasMany {
        return $this->hasMany(Transfert::class, 'arv_id_hotel');
    }

    public function allPrestations(): MorphMany {
        return $this->morphMany(Prestation::class, 'provider');
    }

    public function prestationsAutres(): MorphMany{
        return $this->morphMany(Prestation::class, 'provider')
            ->isNotRepas();
    }

    public function prestationsRepas(): MorphMany{
        return $this->morphMany(Prestation::class, 'provider')
            ->isRepas();
    }


    // public function prestations() {
    //     return $this->hasMany(HotelPrestation::class, 'id_hotel');
    // }

    public function scopeAgesValid(Builder $query, array $agesEnfants) {
        if ($agesEnfants) {
            $query->where('age_minimum', '<=', min($agesEnfants));
        }
    }

}