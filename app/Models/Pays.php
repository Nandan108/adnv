<?php
namespace App\Models;

use App\Traits\HasPersonTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pays extends Model
{
    use HasPersonTypes;

    public $timestamps = false;
    protected $table = 'pays';
    protected $guarded = ['id'];
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
       'code', // UNIQUE KEY
       'nom_en_gb',
       'nom_fr_fr',
       'continent',
       'visa_enfant', // price of visa for enfant, only filled if mandatory
       'visa_adulte', // price of visa for adulte, only filled if mandatory
       'visa_bebe', // price of visa for bebe, only filled if mandatory
       'obligatoire', // TODO: rename to `visa_obligatoire` or just remove (I don't think it's in use)
    ];

    protected function nom(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['nom_fr_fr'],
            set: fn ($value) => ['nom_fr_fr' => $value],
        );
    }
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['nom_en_gb'],
            set: fn ($value) => ['nom_en_gb' => $value],
        );
    }
    protected $append = ['nom', 'name'];


    public function lieux() {
        return $this->hasMany(Lieu::class, 'code_pays', 'code');
    }

 }
