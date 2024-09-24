<?php
namespace App\Managers;

use App\Contracts\PersonTypeManagerInterface;
use App\Models\Chambre;
use App\Traits\HasPersonTypes;

class HotelRoomTarifPersonTypeManager implements PersonTypeManagerInterface
{
    use HasPersonTypes;

    public function __construct(
        public Chambre $hotelRoom,
    ) {}

    // Override or extend methods specific to tarif logic
    public function getPersonSlots(): array
    {
        $room = $this->hotelRoom;
        $ampe                = $room->_age_max_petit_enfant;
        $nb_max_petit_enfant = $room->_age_max_bebe < $ampe && $ampe < $room->age_max_enfant ? 1 : 0;

        return [
            'adulte'       => $room->_nb_max_adulte,
            'enfant'       => $room->_nb_max_enfant,
            'petit_enfant' => $nb_max_petit_enfant ?? 0,
            'bebe'         => $room->_nb_max_bebe,
        ];
    }

    public function getPersonTypeMaxAges(): array
    {
        return array_filter([
            'bebe'         => $this->hotelRoom->_age_max_bebe,
            'petit_enfant' => $this->hotelRoom->_age_max_petit_enfant,
            'enfant'       => $this->hotelRoom->_age_max_enfant,
        ]);
    }
}
