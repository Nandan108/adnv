<?php
namespace App\Models;

use App\Casts\CarbonParseToIsoDate;
use App\Traits\HasPersonTypes;
use Carbon\CarbonImmutable;
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
        'date_naissance' => CarbonParseToIsoDate::class,
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
        return (int)(!$this->adulte) . '-' . $this->idx;
    }

    public function getAgeAttribute(): int
    {
        return $this->getAgeAtDate(self::$debut_voyage ?? date('Y-m-d'));
    }

    public function getBirthdateMinMaxAttribute(): array
    {
        $tripStart         = new CarbonImmutable(self::$debut_voyage);
        [$minAge, $maxAge] = $this->age < 2 ? [0, 1] : [$this->age, $this->age];

        $minBirthdate = $tripStart->subYears($maxAge + 1)->addDay()->format('Y-m-d');
        $maxBirthdate = $tripStart->subYears($minAge)->format('Y-m-d');

        return [$minBirthdate, $maxBirthdate];
    }

    /**
     * This write-only attribute is an alternative way to set the birthdate. Given an age,
     * it will set the birthdate value to the earliest date that gives that age
     * at departure date.
     * @param mixed $age
     * @return void
     */
    public function setAgeAtTripStartAttribute($age)
    {
        // if age is zero years, set it to 1yo (maximum age for 'baby' tarif on airlines)
        if (!$age) $age = 1;
        // calculate the minimum birthdate for that age at trip start date
        $birthdate            = (new Carbon(self::$debut_voyage))->subYears($age + 1)->addDay();
        $this->date_naissance = $birthdate;
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