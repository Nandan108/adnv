<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CommercialdocInfo as DocInfo;

class CommercialdocInfo extends Model
{
    use HasFactory;

    protected $table = 'commercialdoc_infos';
    protected $fillable = [
        'commercialdoc_id',
        'type',
        'data',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
    ];

    public function doc()
    {
        return $this->belongsTo(Commercialdoc::class);
    }

    static protected function cloneFrom(self $source)
    {
        // Create a new instance of the calling class (Foo or any subclass)
        $newInstance = new static();
        // Copy attributes from the original object
        $newInstance->attributes = $source->getAttributes();

        // Mark the new instance as existing
        $newInstance->exists = $source->exists;

        // Copy original object's relations
        foreach ($source->getRelations() as $relation => $items) {
            $newInstance->setRelation($relation, $items);
        }

        // Copy the original object's original attributes
        $newInstance->original = $source->getOriginal();

        return $newInstance;
    }

    // Method to create the appropriate subclass instance
    public function toSpecificType()
    {
        $class = match ($this->type) {
            'header_res_id'      => DocInfo\HeaderResId::class,
            'flight_line'        => DocInfo\FlightLine::class,
            'transfert_line'     => DocInfo\TransfertLine::class,
            'hotel_line'         => DocInfo\HotelLine::class,
            'transfert_comments' => DocInfo\TransfertComments::class,
            'trip_info'          => DocInfo\Trip::class,
        };
        return $class::cloneFrom($this);
    }
}
