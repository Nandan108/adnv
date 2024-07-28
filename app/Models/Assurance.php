<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Assurance extends Model
{
    protected $table = 'assurance';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'titre_assurance', // varchar(50)
        'prix_assurance', // decimal(10,2) unsigned [0.00]
        'prestation_assurance', // text
        'couverture', // enum('par famille','par personne')
        'duree', // enum('annuelle','voyage')
        'pourcentage', // varchar(20) NULL [0]
        'prix_minimum', // decimal(5,2) unsigned NULL
        'frais_annulation', // text
        'assistance', // text
        'fraisderecherche', // text
        'volretarde', // text
    ];

    protected $casts = [
        'prix_assurance' => 'float',
        'prix_minimum' => 'float',
        'pourcentage' => 'float',
    ];

    public function prix($total) {
        if ($this->duree === 'voyage') {
            return max($this->prix_minimum, round(($total * $this->pourcentage / 100)));
        } else {
            return $this->prix_assurance;
        }
    }

    //max($assurance->prix_minimum, round(($total_adulte * $assurance->pourcentage / 100), 1))

    public static function toutesParPrix($titreSansAssurance = null): Collection {
        $assurances = Assurance::query()
            ->orderBy('prix_assurance')
            ->orderBy('pourcentage')
            ->get();

        if ($titreSansAssurance) {
            $assurances->prepend(new Assurance([
                'id' => 0,
                'titre_assurance' => $titreSansAssurance,
                'prix_assurance' => 0,
            ]));
        }

        return $assurances;
    }
}
