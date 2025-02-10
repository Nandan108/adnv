<?php

namespace App\Repositories;

class RoomRepository
{
    public function findNextRoomPeriod(string $finValidite, int $hotelId, string $roomName): ?Chambre
    {
        $nextDay = date('Y-m-d', strtotime('+1 day', strtotime($finValidite)));
        return Chambre::where([
            'id_hotel'       => $hotelId,
            'nom_chambre'    => $roomName,
            'debut_validite' => $nextDay,
        ])->first();
    }
}
