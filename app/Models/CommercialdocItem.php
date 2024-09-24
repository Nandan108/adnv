<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mavinoo\Batch\Traits\HasBatch;

class CommercialdocItem extends Model
{
    use HasFactory, HasBatch;

    protected $table = 'commercialdoc_items';
    protected $fillable = [
        'description', // string(255)
        'unitprice', // decimal(7,2)
        'qtty', // tinyint
        'discount_pct', // tinyint
        'section', // enum ['primary', 'options']
        'stage', // enum ['initial', 'final']
        'ord', // tinying unsigned
    ];

    protected $casts = [
        'qtt'          => 'int',
        'discount_pct' => 'int',
        'unitprice'    => 'float',
    ];


    public function doc()
    {
        return $this->belongsTo(Commercialdoc::class);
    }

    // Define a scope for initial items
    public function scopeInitial($query)
    {
        return $query->where('stage', 'initial');
    }

    // Define a scope for final items
    public function scopeFinal($query)
    {
        return $query->where('stage', 'final');
    }
}
