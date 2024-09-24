<?php
namespace App\Models;

use App\Casts\NullFloat;
use App\Traits\HasPersonTypes;
use DateTimeInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;

class Prestation extends Model
{
    use HasPersonTypes;

    protected $table = 'prestations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'provider_id', // int(10) unsigned
        'provider_type', // enum('App\\Models\\Hotel','App\\Models\\Circuit','App\\Models\\Croisiere')
        'id_type', // int(10) unsigned NULL
        'name', // varchar(100)
        'description', // mediumtext NULL
        'obligatoire', // boolean,
        'debut_validite', // date
        'fin_validite', // date
        'code_monnaie', // char(3)
        'taux_commission', // tinyint(3) unsigned [0]
        'adulte_net', // decimal(6,2) unsigned
        'enfant_net', // decimal(6,2) unsigned
        'bebe_net', // decimal(6,2) unsigned
        'photo', // text COLLATE latin1_general_ci NOT NULL,
    ];
    protected $with = ['monnaie', 'type'];

    protected $casts = [
        'taux_commission' => 'int',
        'adulte_net'      => NullFloat::class,
        'enfant_net'      => NullFloat::class,
        'bebe_net'        => NullFloat::class,
        'obligatoire'     => 'boolean',
        'debut_validite'  => 'datetime:Y-m-d',
        'fin_validite'    => 'datetime:Y-m-d',
    ];

    // /**
    //  * Prepare a date for array / JSON serialization.
    //  */
    // protected function serializeDate(DateTimeInterface $date): string {
    //     return $date->format('Y-m-d');
    // }

    public function type()
    {
        return $this->belongsTo(TypePrestation::class, 'id_type');
    }

    public function scopeIsNotRepas(Builder $query)
    {
        $query->whereHas($this->type(), fn($q) => $q->where('is_meal', '=', 0));
    }
    public function scopeIsRepas(Builder $query)
    {
        $query->whereHas($this->type(), fn($q) => $q->where('is_meal', '>', 0));
    }

    public function monnaie()
    {
        return $this->belongsTo(Monnaie::class, 'code_monnaie', 'code');
    }

    public function provider(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'provider_type', 'provider_id');
    }

    // first night must be within validity period
    public function scopeValidRepas($query, $date)
    {
        $query->whereRaw('? BETWEEN debut_validite AND fin_validite', [$date]);
    }
    // validity period must overlap with stay dates
    public function scopeValidPrestation($query, $date_depart, $date_retour)
    {
        $query->whereRaw('? < fin_validite AND date_sub(?, interval 1 day) >= debut_validite', [$date_depart, $date_retour]);
    }

    // For Prestations, personTypeMaxAges is copied from the provider
    // TODO: That is from the hotel's room at the moment, but should be moved to the hotel itself.
    // And the same should be done for circuits and croisieres
    private array $personTypeMaxAges = [];
    public function setPersonTypeMaxAges(array $personTypeMaxAges)
    {
        $this->personTypeMaxAges = $personTypeMaxAges;
    }
    public function getPersonTypeMaxAges()
    {
        return $this->personTypeMaxAges;
    }

    public function calcTotal($montant)
    {
        return ceil(
            $montant *
            $this->monnaie->taux *
            (1 + $this->taux_commission / 100)
        );
    }

    public function calcPersonTotal($person, $multiplier = 1)
    {
        $net = $this->{"{$person}_net"};
        return $net === null ? null : ceil(
            $net * $this->monnaie->taux *
            (1 + $this->taux_commission / 100)
        ) * $multiplier;
    }

    /**
     * Collect and return info for use on reservation page
     *
     * @param Collection $personCounts
     * @param array $datesVoyage
     * @return object
     */
    public function getInfo(
        Collection $personCounts,
        array $datesVoyage,
    ) {
        // TODO: handle case where the stay ends AFTER the validity limits of the meal services
        // TODO: adapt number of meals according to meal type ??
        $dates   = [
            max($datesVoyage[0], $this->debut_validite),
            min($datesVoyage[1], $this->fin_validite),
        ];
        $nbNuits = (strtotime($dates[1]) - strtotime($dates[0])) / (24 * 60 * 60);

        $brut = collect([
            'adulte' => $this->calcPersonTotal('adulte'),
            'enfant' => $this->calcPersonTotal('enfant'),
            'bebe'   => $this->calcPersonTotal('bebe'),
        ])->filter(fn($val, $key) => $val !== null);

        $info = (object)[
            'id'           => $this->id,
            'nom'          => $this->type->name,
            'prestation'   => $this,
            'nbNuits'      => $nbNuits, // need this to compute repas totals
            'personCounts' => $personCounts->intersectByKeys($brut),
            'obligatoire'  => $this->obligatoire,
            'totals'       => (object)$brut,
            'total'        => $brut->map(
                fn($brut, $person)        =>
                $brut * $personCounts[$person]
            )->sum(),
        ];

        return $info;
    }
}