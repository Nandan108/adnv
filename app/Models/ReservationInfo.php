<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationInfo extends Model
{
    protected $table = 'reservation_info';

    protected $primaryKey = 'id_reservation_info';

    // TODO: add timestamp fields;
    public $timestamps = false;

    /*
    coordonees_client
    -------------------
    nom
    prenom
    titre
    nationalite
    nom
    prenom
    rue
    npa
    lieu
    pays
    email
    tel
    paiement
    newsletter
    created_at
    updated_at

    reservations
    ----------------

    // morphTo: reservation, reservation_circuit, reservation_croisiere,
    Table voyageurs:
    id
    travel_id
    travel_type
    id_offre
    type_offre
    nom
    prenom
    titre
    nationalite
    assurance
    adulte (boolean)
    date_naissance,
    id_assurance,

    reservation_prestations (id, id_reservation, id_prestation, nb_adulte, nb_enfant, nb_bebe)
    reservation_tours (id, id_reservation, id_tour, nb_adulte, nb_enfant, nb_bebe)

    prest: [{[id]: {type, total}}],
    tours: [{[id]: total}],
    visa: true,

*/


    protected $fillable = [
        'id_reservation_valeur', // int(15) [0]
        'titre_participant_1', // varchar(50) NULL [0]
        'nom_participant_1', // varchar(50) NULL [0]
        'prenom_participant_1', // varchar(50) NULL [0]
        'nationalite_participant_1', // varchar(50) NULL [0]
        'assurance_1', // varchar(100) [0]
        'titre_participant_2', // varchar(50) [0]
        'nom_participant_2', // varchar(50) NULL [0]
        'prenom_participant_2', // varchar(50) NULL [0]
        'nationalite_participant_2', // varchar(50) [0]
        'assurance_2', // varchar(100) [0]
        'titre_participant_3', // varchar(50) [0]
        'nom_participant_3', // varchar(50) [0]
        'prenom_participant_3', // varchar(50) [0]
        'nationalite_participant_3', // varchar(50) [0]
        'assurance_3', // varchar(100) [0]
        'titre_participant_4', // varchar(50) [0]
        'nom_participant_4', // varchar(50) [0]
        'prenom_participant_4', // varchar(50) [0]
        'nationalite_participant_4', // varchar(50) [0]
        'assurance_4', // varchar(100) [0]
        'titre_participant_enfant_1', // varchar(50) [0]
        'nom_participant_enfant_1', // varchar(50) [0]
        'prenom_participant_enfant_1', // varchar(50) [0]
        'date_naissance_participant_enfant_1', // varchar(50) [0]
        'nationalite_participant_enfant_1', // varchar(50) [0]
        'assurance_enfant_1', // varchar(50) [0]
        'titre_participant_enfant_2', // varchar(50) [0]
        'nom_participant_enfant_2', // varchar(50) [0]
        'prenom_participant_enfant_2', // varchar(50) [0]
        'date_naissance_participant_enfant_2', // varchar(50) [0]
        'nationalite_participant_enfant_2', // varchar(50) [0]
        'assurance_enfant_2', // varchar(50) [0]
        'titre_participant_bebe_1', // varchar(50) [0]
        'nom_participant_bebe_1', // varchar(50) [0]
        'prenom_participant_bebe_1', // varchar(50) [0]
        'date_naissance_participant_bebe_1', // varchar(50) [0]
        'nationalite_participant_bebe_1', // varchar(50) [0]
        'assurance_bebe_1', // varchar(50) [0]
        'titre_participant_bebe_2', // varchar(50) [0]
        'nom_participant_bebe_2', // varchar(50) [0]
        'prenom_participant_bebe_2', // varchar(50) [0]
        'date_naissance_participant_bebe_2', // varchar(50) [0]
        'nationalite_participant_bebe_2', // varchar(50) [0]
        'assurance_bebe_2', // varchar(50) [0]
        'titre_coordonnee', // varchar(50) [0]
        'nom', // varchar(50) [0]
        'prenom', // varchar(80) [0]
        'rue', // varchar(50) [0]
        'npa', // varchar(50) [0]
        'lieu', // varchar(50) [0]
        'pays', // varchar(50) [0]
        'email', // varchar(50) [0]
        'reemail', // varchar(50) [0]
        'tel', // varchar(50) [0]
        'paiement', // varchar(50) [0]
        'cgcv', // int(10) [0]
        'document', // int(10) [0]
        'newsletter', // int(10) [0]
        'prix_chambre_a1', // varchar(50) [0]
        'prix_chambre_a2', // varchar(50) [0]
        'prix_chambre_a3', // varchar(50) [0]
        'prix_chambre_a4', // varchar(50) [0]
        'prix_chambre_e1', // varchar(50) [0]
        'prix_chambre_e2', // varchar(50) [0]
        'prix_chambre_b1', // varchar(50) [0]
        'prix_transfert_a1', // varchar(50) [0]
        'prix_transfert_a2', // varchar(50) [0]
        'prix_transfert_a3', // varchar(50) [0]
        'prix_transfert_a4', // varchar(50) [0]
        'prix_transfert_e1', // varchar(50) [0]
        'prix_transfert_e2', // varchar(50) [0]
        'prix_transfert_b1', // varchar(50) [0]
        'prix_visa_a1', // varchar(50) [0]
        'prix_visa_a2', // varchar(50) [0]
        'prix_visa_a3', // varchar(50) [0]
        'prix_visa_a4', // varchar(50) [0]
        'prix_visa_e1', // varchar(50) [0]
        'prix_visa_e2', // varchar(50) [0]
        'prix_visa_b1', // varchar(50) [0]
        'prix_option_a1', // varchar(50) [0]
        'prix_option_a2', // varchar(50) [0]
        'prix_option_a3', // varchar(50) [0]
        'prix_option_a4', // varchar(50) [0]
        'prix_option_e1', // varchar(50) [0]
        'prix_option_e2', // varchar(50) [0]
        'prix_option_b1', // varchar(50) [0]
        'prix_repas_a1', // varchar(50) [0]
        'prix_repas_a2', // varchar(50) [0]
        'prix_repas_a3', // varchar(50) [0]
        'prix_repas_a4', // varchar(50) [0]
        'prix_repas_e1', // varchar(50) [0]
        'prix_repas_e2', // varchar(50) [0]
        'prix_repas_b1', // varchar(50) [0]
        'prix_tour_a1', // varchar(50) [0]
        'prix_tour_a2', // varchar(50) [0]
        'prix_tour_a3', // varchar(50) [0]
        'prix_tour_a4', // varchar(50) [0]
        'prix_tour_e1', // varchar(50) [0]
        'prix_tour_e2', // varchar(50) [0]
        'prix_tour_b1', // varchar(50) [0]
        'prix_total_a1', // varchar(50) [0]
        'prix_total_a2', // varchar(50) [0]
        'prix_total_a3', // varchar(50) [0]
        'prix_total_a4', // varchar(50) [0]
        'prix_total_e1', // varchar(50) [0]
        'prix_total_e2', // varchar(50) [0]
        'prix_total_b1', // varchar(50) [0]
        'prix_total_total', // varchar(50) [0]
        'id_prest1', // int(50) [0]
        'id_prest2', // int(50) [0]
        'id_prest3', // int(50) [0]
        'id_prest4', // int(50) [0]
        'id_prest5', // int(50) [0]

        'id_prix_repas_obligatoire', // varchar(50) [0]
        'option_autre_transfert', // varchar(50) [0]
        'option_autre_transfert_enfant', // varchar(50) [0]
        'option_autre_transfert_bebe', // varchar(50) [0]
        'id_option_autre_transfert', // varchar(50) [0]
        'id_option_autre_transfert_enfant', // varchar(50) [0]
        'id_option_autre_transfert_bebe', // varchar(50) [0]
        'id_prix_transfert_obligatoire', // varchar(50) [0]

        'devis', // int(11)
        'date_creation', // varchar(20)
        'status', // int(2)

        'num_rue', // varchar(50)
        'titre_ass_1', // varchar(100)
        'titre_ass_2', // varchar(100)
        'titre_ass_3', // varchar(100)
        'titre_ass_4', // varchar(100)
        'id_assurance_1', // varchar(10) [0]
        'id_assurance_2', // varchar(10) [0]
        'id_assurance_3', // varchar(10) [0]
        'id_assurance_4', // varchar(10) [0]
        'check_assurance_adulte_1', // varchar(5) [1]
        'check_assurance_adulte_2', // varchar(5) [1]
        'check_assurance_adulte_3', // varchar(5) [1]
        'check_assurance_adulte_4', // varchar(5) [1]
        'check_repas_adulte_101', // varchar(5) [1]
        'check_repas_enfant_101', // varchar(5) [1]
        'check_repas_bebe_101', // varchar(5) [1]
        'check_prestation_adulte_101', // varchar(5) [1]
        'check_prestation_enfant_101', // varchar(5) [1]
        'check_prestation_bebe_101', // varchar(5) [1]
        'check_excursion_adulte_101', // varchar(5) [1]
        'check_excursion_enfant_101', // varchar(5) [1]
        'check_excursion_bebe_101', // varchar(5) [1]
        'check_excursion_adulte_201', // varchar(5) [1]
        'check_excursion_enfant_201', // varchar(5) [1]
        'check_excursion_bebe_201', // varchar(5) [1]
        'remarque', // text NULL
        'commentaire', // text NULL
    ];

    public function reservation() {
        return $this->belongsTo(ReservationInfo::class, 'id_reservation_valeur');
    }
}