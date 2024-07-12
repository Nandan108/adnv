<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sejour extends Model
{
    protected $table = 'sejours';
    public $timestamps = false;
    protected $fillable = [
        // 'id' int(10) NOT NULL AUTO_INCREMENT,
        'titre', // varchar(250) NOT NULL,
        'id_vol', // int(10) NOT NULL,
        'id_transfert', // int(10) NOT NULL,
        'id_chambre', // int(10) NOT NULL,
        'debut_vente', // date NOT NULL,
        'fin_vente', // date NOT NULL,
        'debut_voyage', // date NOT NULL,
        'fin_voyage', // date NOT NULL,
        'nb_nuit', // tinyint(4) NOT NULL DEFAULT 1,
        'promo', // tinyint(1) NOT NULL DEFAULT 0,
        'avant', // tinyint(1) NOT NULL DEFAULT 0,
        'coup_coeur', // tinyint(1) NOT NULL DEFAULT 0,
        'manuel', // tinyint(1) NOT NULL DEFAULT 0,
        'photo', // varchar(100) NOT NULL DEFAULT '',
        'inclu', // text NOT NULL DEFAULT '',
        'noninclu', // text NOT NULL DEFAULT '',

        // should be auto-calculated
        'simple_adulte_1', // decimal(6,0) DEFAULT NULL,
        'double_enfant_1a', // decimal(6,0) DEFAULT NULL,
        'double_enfant_1b', // decimal(6,0) DEFAULT NULL,
        'simple_enfant_1a', // decimal(6,0) DEFAULT NULL,
        'simple_enfant_1b', // decimal(6,0) DEFAULT NULL,
        'simple_enfant_2', // decimal(6,0) DEFAULT NULL,
        'simple_bebe', // decimal(6,0) DEFAULT NULL,
        'double_adulte_1', // decimal(6,0) DEFAULT NULL,
        'double_adulte_2', // decimal(6,0) DEFAULT NULL,
        'double_enfant_2', // decimal(6,0) DEFAULT NULL,
        'double_bebe', // decimal(6,0) DEFAULT NULL,
        'triple_adulte_1', // decimal(6,0) DEFAULT NULL,
        'triple_adulte_2', // decimal(6,0) DEFAULT NULL,
        'triple_adulte_3', // decimal(6,0) DEFAULT NULL,
    ];

    protected $with = ['monnaieObj'];
    public function monnaieObj() {
        return $this->belongsTo(Monnaie::class, 'monnaie', 'code');
    }

    public function vol() {
        return $this->belongsTo(Vol::class, 'id_vol');
    }

    public function transfert() {
        return $this->belongsTo(Transfert::class, 'id_transfert');
    }

    public function chambre() {
        return $this->belongsTo(Chambre::class, 'id_chambre');
    }

    // TODO: rename field monaieObj -> code_monaie and relation monaieObj() -> monaie()

}