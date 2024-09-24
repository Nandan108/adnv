<?php
namespace App\Managers;

use App\Contracts\PersonTypeManagerInterface;
use App\Models\Chambre;
use App\Traits\HasPersonTypes;

class HotelRoomOccupancyPersonTypeManager implements PersonTypeManagerInterface
{
    use HasPersonTypes;

    public function __construct(
        public Chambre $hotelRoom,
    ) {}

    // Override or extend methods specific to tarif logic
    public function getPersonSlots(): array
    {
        return [
            'adulte'       => $this->hotelRoom->_nb_max_adulte,
            'enfant'       => $this->hotelRoom->_nb_max_enfant,
            'bebe'         => $this->hotelRoom->_nb_max_bebe,
        ];
    }

    public function getPersonTypeMaxAges(): array
    {
        return array_filter([
            'bebe'         => $this->hotelRoom->_age_max_bebe,
            'enfant'       => $this->hotelRoom->_age_max_enfant,
            //'teen'       => $this->hotelRoom->max_age_teen,
        ]);
    }
}
