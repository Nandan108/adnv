<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservationPrestation extends Pivot
{
    protected $table = 'reservation_prestation';
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

    public function prestation(): BelongsTo
    {
        return $this->BelongsTo(Prestation::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->BelongsTo(Reservation::class);
    }
}