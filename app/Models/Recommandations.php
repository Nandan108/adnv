<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Recommandations extends Model
{
    protected $table = 'recommandations';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'description',
        'icone',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('description_ASC', function (Builder $builder) {
            $builder->orderBy('description');
        });
    }
}