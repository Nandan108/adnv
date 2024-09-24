<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CommercialdocInfo as DocInfo;
use App\Enums\CommercialdocInfoType;
use Mavinoo\Batch\Traits\HasBatch;

class CommercialdocInfo extends Model
{
    use HasFactory, HasBatch;

    protected $table = 'commercialdoc_infos';
    protected $fillable = [
        'commercialdoc_id', // FK int
        'type', // enum
        'data', // json
        'ord', // tinying unsigned
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
        'type' => CommercialdocInfoType::class,
    ];

    protected static $type;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->type = static::$type;
    }

    public function doc()
    {
        return $this->belongsTo(Commercialdoc::class, 'commercialdoc_id');
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

    public function make($attributes) {
        $this->fill($attributes);
    }

    // Method to create the appropriate subclass instance
    public function toSpecificType()
    {
        $specificSubclass = $this->type->className();

        return $specificSubclass::cloneFrom($this);
    }
}
