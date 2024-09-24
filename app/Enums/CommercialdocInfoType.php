<?php

namespace App\Enums;

use App\Models\CommercialdocInfo as DocInfo;

enum CommercialdocInfoType: string
{
    // !! NOTE: after modifying these enum values, make to to create a migration
    // to adjust the field definition in DB. Use ->change() to update the column :
    // $table->enum('type', collect(CommercialdocInfoType::cases())->pluck('value')->all())->change();
    // Also note: MariaDB disallows deleting or renaming an enum value that is currently in use.

    case HeaderResId       = 'header_res_id';
    case FlightLine        = 'flight_line';
    case TransferLine      = 'transfert_line';
    case HotelLine         = 'hotel_line';
    case TransfertComments = 'transfert_comments';
    case TravelerLine      = 'traveler_line';
    case TripInfo          = 'trip_info';

    public function className(): string
    {
        return match ($this) {
            self::HeaderResId       => DocInfo\HeaderResId::class,
            self::FlightLine        => DocInfo\FlightLine::class,
            self::TransferLine      => DocInfo\TransferLine::class,
            self::HotelLine         => DocInfo\HotelLine::class,
            self::TransfertComments => DocInfo\TransfertComments::class,
            self::TravelerLine      => DocInfo\TravelerLine::class,
            self::TripInfo          => DocInfo\TripInfo::class,
        };
    }
}
