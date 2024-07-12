<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circuit extends Model
{
    protected $table = 'circuits_new';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function prestations() {
        return $this->morphMany(Prestation::class, 'prestationable');
    }
}