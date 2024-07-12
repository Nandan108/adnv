<?php
namespace App\Models;

use App\Traits\HasPersonTypes;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Voyageur extends Model
{
    protected $table = 'voyageurs';
    protected $fillable = [
        // 'booking_id', // int(10) unsigned
        // 'booking_type', // enum('App\\Models\\Reservation','App\\Models\\Circuit','App\\Models\\Croisiere')
        'adulte', //	tinyint(4)
        'idx', // tinyint -- zero-based index
        'nom', //	varchar(50)
        'prenom', //	varchar(50)
        'titre', //	varchar(10) NULL
        'code_pays_nationalite', //	varchar(2)
        'date_naissance', //	date NULL
        'id_assurance', //	int(11)
        'options',
    ];

    protected $casts = [
        'adulte'         => 'boolean',
        'date_naissance' => 'date:Y-m-d',
        'idx'            => 'int',
        'options'        => AsArrayObject::class,
    ];

    // used to hold the trip start date
    protected static $debut_voyage = null;
    public static function setDateDebutVoyage(string $debut_voyage)
    {
        static::$debut_voyage = $debut_voyage;
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'booking_id');
    }

    public function paysNationalite()
    {
        return $this->belongsTo(Pays::class, 'code_pays_nationalite');
    }

    public function assurance()
    {
        return $this->belongsTo(Assurance::class, 'id_assurance');
    }

    public function booking(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'booking_type', 'booking_id');
    }

    public function getIdxKeyAttribute(): string
    {
        return (int)$this->adulte . '-' . $this->idx;
    }

    public function getAgeAttribute(): int
    {
        return $this->getAgeAtDate(self::$debut_voyage ?? date('Y-m-d'));
    }

    public function getAgeAtDate(string $date, $adultAge = 99): int
    {
        $dateOfBirth = $this->date_naissance;
        if (empty($dateOfBirth)) return $adultAge;
        if (!$dateOfBirth instanceof Carbon) $dateOfBirth = new Carbon($dateOfBirth);

        return $dateOfBirth->diff(new \DateTime($date))->y;
    }

    public function getPersonTypeAt(HasPersonTypes $provider, $trip_date)
    {
        return $provider->getPersonType($this->getAgeAtDate($trip_date));
    }
}