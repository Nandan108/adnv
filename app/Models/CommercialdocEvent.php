<?php

namespace App\Models;

use App\Enums\CommercialdocEventType;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class CommercialdocEvent extends Model
{
    protected $table = 'commercialdoc_events';

    // Types:
    // initial_quote_sent
    // final_quote_sent
    // quote_validated
    // invoice_sent
    // payment
    // client_canceled
    // quote_expired
    // admin_canceled
    protected $fillable = [
        'commercialdoc_id',
        'type',
        'data',
        'admin_id',
    ];

    protected $casts = [
        'type' => CommercialdocEventType::class,
        'data' => AsArrayObject::class,
        'created_at' => 'datetime',
    ];

    public function getCreatedLclzedAttribute()
    {
        return $this->created_at->translatedFormat('d F Y');
    }

    public function commercialdoc()
    {
        return $this->belongsTo(Commercialdoc::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
