<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialdocItem extends Model
{
    use HasFactory;

    protected $table = 'commercialdoc_items';
    protected $fillable = [
        'description', // string(255)
        'unitprice', // decimal(7,2)
        'qtty', // tinyint
        'discount_pct', // tinyint
        'section', // enum ['primary', 'options']
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
}
