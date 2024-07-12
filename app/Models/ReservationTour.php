<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Collection;

class ReservationTour extends Pivot
{
    protected $table = 'reservation_tour';
    public $timestamps = false;

    protected $fillable = [
        'adulte',
        'enfant',
        'bebe',
    ];

    protected $casts = [
        'adulte' => 'int',
        'enfant' => 'int',
        'bebe' => 'int',
    ];

    public function tour(): BelongsTo
    {
        return $this->BelongsTo(Tour::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->BelongsTo(Reservation::class);
    }

    // public function adjustCounts(string $debut_voyage, Tour $tour, Collection $participants) {
    //     $counts = array_fill_keys($this->fillable, 0);
    //     foreach ($participants as $p) {
    //         if ($p->date_naissance) {
    //             $age = (new \DateTime($p->date_naissance))->diff(new \DateTime($debut_voyage))->y;
    //             $type = $age <= $tour->age_max_bebe ? 'bebe' :
    //                 ($age <= $tour->age_max_enfant ? 'enfant' : )
    //         } else
    //     }
    // }
}