<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Commercialdoc extends Model
{
    use HasFactory;
    use HasHashid, HashidRouting;

    protected $table = 'commercialdocs';

    protected $fillable = [
        'doc_id',
        'deadline',
        'monnaie_id',
        'reservation_id',
        'quote_confirmed_at',
        'currency_code',
        'object_type', // enum ['trip', 'circuit', 'cruise']
        'client_remarques',
        'lastname',
        'firstname',
        'email',
        'phone',
        'street',
        'street_num',
        'zip',
        'city',
        'country_code',
    ];

    protected $with = ['items', 'infos', 'currency'];
    protected $appends = ['created_lcl', 'deadline_lcl', 'header_address_lines'];

    protected $casts = [
        'created_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    public function formatDate(Carbon $date)
    {
        // Set the locale to French
        Carbon::setLocale('fr');

        // // Parse the date string and format it
        // $date = Carbon::parse($dateString);

        // Format the date to "11 juillet 2024"
        return $date ? $date->translatedFormat('d F Y') : $date;
    }

    // Getter for localized creation date
    public function getCreatedLclAttribute()
    {
        return $this->created_at->translatedFormat('d F Y');
    }

    // Getter for localized deadline
    public function getDeadlineLclAttribute()
    {
        return $this->deadline ? $this->deadline->translatedFormat('d F Y') : null;
    }

    public function currency() {
        return $this->belongsTo(Monnaie::class, 'currency_code');
    }

    public function reservation() {
        return $this->belongsTo(Reservation::class);
    }

    public function items()
    {
        return $this->hasMany(CommercialdocItem::class, 'commercialdoc_id');
    }

    public function infos()
    {
        return $this->hasMany(CommercialdocInfo::class, 'commercialdoc_id');
    }

    public function getInfo(string $type)
    {
        return $this->infos
            ->filter(fn($info) => $info->type === $type)
            ->map(fn($info) => $info->toSpecificType());
    }

    public function getHeaderAddressLinesAttribute()
    {
        return array_filter([
            config('settings.company_name'),
            config('settings.company_address_1'),
            config('settings.company_address_2'),
        ]);
    }
}
