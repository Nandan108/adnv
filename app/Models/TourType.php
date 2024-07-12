<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class TourType extends Model
{
    protected $table = 'type_tour';
    protected $primaryKey = 'id_type';
    public $timestamps = false;

    protected $fillable = [
        // 'id' int(15) NOT NULL AUTO_INCREMENT,
        'nom_type', // varchar(50) COLLATE latin1_general_ci NOT NULL,
    ];

    public function tours() {
        return $this->hasMany(Tour::class, 'id_type_tour', 'id_type');
    }

}