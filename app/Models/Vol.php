<?php
namespace App\Models;

use App\Traits\HasPersonTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

class Vol extends Model
{
    use HasPersonTypes;

    public $timestamps = false;

    protected $table = 'vols_new';
    protected $fillable = [
        'vol_seul', // tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT 'Indique si le vol est à utiliser comme vol seul plutôt que dans un séjour',
        'titre', // varchar(100) NOT NULL,
        'id_company', // int(10) NOT NULL,
        'class_reservation', // enum('Airline','Charter','Contingent') NOT NULL,
        'sans_bagage', // tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT 'pour les vols seul',
        'code_monnaie', // char(3) DEFAULT NULL,
        'taux_change', // float NOT NULL DEFAULT 0,
        'jours_depart', // set('1','2','3','4','5','6','7') NOT NULL DEFAULT '1,2,3,4,5,6,7',
        'code_apt_depart', // char(3) NOT NULL,
        'code_apt_transit', // char(3) DEFAULT NULL,
        'code_apt_arrive', // char(3) NOT NULL,
        'arrive_next_day', // tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = arrivée même jour',
        'debut_vente', // date NOT NULL,
        'fin_vente', // date NOT NULL,
        'debut_voyage', // date NOT NULL,
        'fin_voyage', // date NOT NULL,
    ];

    const CLASS_RESERVATION = [
        "eco"      => "Classe économique",
        "premium"  => "Classe prémium",
        "business" => "Business Classe",
        "first"    => "Première Classe",
    ];

    const FLIGHT_TYPE_COMMERCIAL = 'Airline';
    const FLIGHT_TYPE_CHARTER    = 'Charter';
    const FLIGHT_TYPE_CONTIGENT  = 'Contingent';
    const FLIGHT_TYPES           = [
        'Airline', // TODO: rename to "Commercial". Sold seat-by-seat
        'Charter', // Privatized airplane
        'Contingent', // Group of seats reserved at once
    ];

    // needed for HasPersonType::getPersonType()
    public function getDebutValiditeAttribute()
    {
        return $this->debut_voyage;
    }

    public function prix()
    {
        return $this->hasMany(VolPrix::class, 'id_vol', 'id')
            ->orderBy('adulte_net');
    }

    public function getPrixClasse($surclassement): VolPrix|Collection
    {
        return $this->prix()->where('surclassement', $surclassement)->first();
    }

    // in DB, departure weekdays are stored as comma-separated
    // in php, ->jours_depart returns int[]: [1,3,4] means Mon,Wed,Thu
    protected function joursDepart(): Attribute
    {
        return Attribute::make(
            get: fn($value) => explode(',', $value),
            set: fn($value) => implode(',', $value),
        );
    }
    // TODO: le champ devrait s'appeller code_monnaie
    // TODO: fix Monnaie set CHF for all vols in DB
    // TODO: fix admin/vol.php bouton Enregistrer doesn't seem to work
    // TODO: check create form (admin/vol.php?id=) make sure CHF is default

    protected $with = ['monnaieObj'];
    public function monnaieObj()
    {
        return $this->belongsTo(Monnaie::class, 'monnaie', 'code');
    }
    public function apt_depart()
    {
        return $this->belongsTo(Aeroport::class, 'code_apt_depart', 'code_aeroport');
    }
    public function apt_transit()
    {
        return $this->belongsTo(Aeroport::class, 'code_apt_transit', 'code_aeroport');
    }
    public function apt_arrive()
    {
        return $this->belongsTo(Aeroport::class, 'code_apt_arrive', 'code_aeroport');
    }
    public function airline()
    {
        return $this->belongsTo(Airline::class, 'id_company');
    }
    public function scopeValidDatePeriod($query, $dateDepart)
    {
        $query->whereRaw('? BETWEEN debut_voyage AND fin_voyage', [$dateDepart]);
    }
    public function scopeValidWeekDay($query, $dateDepart)
    {
        $query->whereRaw('FIND_IN_SET(WEEKDAY(?) + 1, jours_depart)', [$dateDepart]);
    }

    public function scopeFlightType($query, $flightType)
    {
        $query->where('class_reservation', $flightType);
    }

    public function jourDepartEstValide($dateDepart)
    {
        $validPeriod  = $dateDepart >= $this->debut_voyage && $dateDepart <= $this->fin_voyage;
        $validWeekDay = in_array(date('N', strtotime($dateDepart)), $this->jours_depart);
        return $validPeriod && $validWeekDay;
    }

    /**
     * Returns list of timestamps corresponding to the dates from -3 to +3
     * days from $dateDepart, which are valid weekdays for this flight's departure.
     *
     * @param string $dateDepart
     * @return array
     */
    public function departsAlteratifs(string $dateDepart)
    {
        $timeBounds = [strtotime($this->debut_voyage), strtotime($this->fin_voyage)];
        foreach (range(-3, 3) as $diff) {
            $alterTime = strtotime("$dateDepart " . ($diff >= 0 ? "+$diff" : $diff) . ' days');
            if (
                in_array(date('N', $alterTime), $this->jours_depart) &&
                $timeBounds[0] <= $alterTime && $alterTime <= $timeBounds[1]
            ) {
                $alterDates[$diff] = (object)[
                    'diff'    => $diff,
                    'date'    => date('Y-m-d', $alterTime),
                    'display' => fmtDate('E d', $alterTime),
                ];
            }
        }
        return $alterDates ?? [];
    }

    public function calcTotal($prix, $person)
    {
        $net  = $prix->{$person . '_net'};
        $taux = $this->monnaieObj->taux;
        $comm = $prix->{$person . '_comm'};
        return [
            'brut'  => $brut = ceil($net * $taux) + $comm,
            'taxe'  => $taxe = $prix->{$person . '_taxe'},
            'total' => $brut + $taxe,
        ];
    }

    public function getInfoVol(
        Collection $personCounts,
        VolPrix $volPrix,
        string $dateDepart,
    ) {
        $totals = collect([
            'adulte' => $this->calcTotal($volPrix, 'adulte'),
            'enfant' => $this->calcTotal($volPrix, 'enfant'),
            'bebe'   => $this->calcTotal($volPrix, 'bebe'),
        ]);
        $total  = $totals->reduce(fn($acc, $tot, $person) => $acc + $tot['total'] * $personCounts[$person], 0);

        $infoVol = (object)[
            'id'            => $volPrix->id_prix_vol,
            'airline'       => $this->airline->company,
            'airline_logo'  => $this->airline->photo,
            'classReserv'   => $this->class_reservation,
            'surclassement' => $volPrix->surclassement,
            'aeroports'     => [
                'depart'  => $this->code_apt_depart,
                'transit' => $this->code_apt_transit,
                'arrive'  => $this->code_apt_arrive,
            ],
            //'prixVol'       => $volPrix,
            'totals'        => $totals,
            'total'         => $total,
            'nomJourDepart' => fmtDate('cccc', $dateDepart),
            'departValide'  => $this->jourDepartEstValide($dateDepart),
            'datesDeparts'  => $this->departsAlteratifs($dateDepart),
            'next_day_arr'   => $this->arrive_next_day,
        ];

        return $infoVol;
    }
}

/*
select '2024-04-1' date, 1

*/