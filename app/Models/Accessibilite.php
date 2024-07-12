<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Accessibilite extends Model
{
    protected $table = 'accessibilites';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('description_ASC', function (Builder $builder) {
            $builder->orderBy('description');
        });
    }
}