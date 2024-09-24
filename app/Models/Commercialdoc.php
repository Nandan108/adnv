<?php

namespace App\Models;

use App\Enums\CommercialdocEventType;
use App\Enums\CommercialdocStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Commercialdoc extends Model
{
    use HasFactory;
    use HasHashid, HashidRouting;

    protected $table = 'commercialdocs';

    protected $fillable = [
        'doc_id',
        'type', // enum('quote','invoice')
        'currency_code',
        'reservation_id', // TODO: rename to booking_id
        'deadline',
        'object_type', // enum ('trip', 'circuit', 'cruise')
        'status', // enum CommercialdocStatus::
        'client_remarques',
        'title',
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
    protected $appends = [
        'hashId',
        'created_lclzed',
        'deadline_lcl',
        'header_address_lines',
        'header_specific_lines',
        'travelers'
    ];
    protected $casts = [
        'status'     => CommercialdocStatus::class,
        'created_at' => 'datetime',
        'deadline'   => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // // Append the additional attribute
        // $this->appends[] = 'hashId';
        // $this->appends[] = 'header_specific_lines';
        // $this->appends[] = 'travelers';
    }

    public function formatDate(Carbon $date)
    {
        // Set the locale to French
        Carbon::setLocale('fr');

        // // Parse the date string and format it
        // $date = Carbon::parse($dateString);

        // Format the date to "11 juillet 2024"
        return $date ? $date->translatedFormat('d F Y') : $date;
    }

    public static function getNewDocID()
    {
        $docCount = self::whereDate('created_at', Carbon::today())->count();
        return date('ymd.') . str_pad($docCount + 1, 2, '0', STR_PAD_LEFT);
    }

    // Getter for localized creation date
    public function getCreatedLclzedAttribute()
    {
        $createAt = $this->created_at;
        return $this->created_at?->translatedFormat('d F Y');
    }

    // Getter for localized deadline
    public function getDeadlineLclAttribute()
    {
        return $this->deadline ? $this->deadline->translatedFormat('d F Y') : null;
    }

    public function currency()
    {
        return $this->belongsTo(Monnaie::class, 'currency_code');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function country()
    {
        return $this->belongsTo(Pays::class, 'country_code', 'code');
    }

    public function items()
    {
        return $this->hasMany(CommercialdocItem::class, 'commercialdoc_id');
    }

    public function infos()
    {
        return $this->hasMany(CommercialdocInfo::class, 'commercialdoc_id');
    }

    public function events()
    {
        return $this->hasMany(CommercialdocEvent::class, 'commercialdoc_id');
    }

    public function getInfo(string $type)
    {
        return $this->infos
            ->filter(fn($info) => $info->type === $type)
            ->map(fn($info) => $info->toSpecificType());
    }

    public function getEventByType($eventType)
    {
        return $this->events()->where('type', $eventType)->first();
    }

    public function getDepositAmount()
    {
        return $this->getEventByType('invoice_sent')?->data['depositAmount'] ?? null;
    }

    public function getTotalAmount()
    {
        $itemPrice = fn($item) => $item->unitprice * $item->qtty * (1 - $item->discount_pct / 100);
        return $this->items->map($itemPrice)->sum();
    }

    public function getHeaderAddressLinesAttribute()
    {
        return array_filter([
            config('settings.company_name'),
            config('settings.company_address_1'),
            config('settings.company_address_2'),
        ]);
    }

    public function getLongTitleAttribute()
    {
        return ['Mr' => 'Monsieur', 'Mme' => 'Madame'][$this->title];
    }

    public function logEvent(CommercialdocEventType $type, array $data = [], ?User $user = null)
    {
        return $this->events()->create([
            'type'  => $type,
            'data'  => $data,
            'admin' => ($user ??= Auth::user()) ? $user->id : null,
        ]);
    }

    public function getHeaderSpecificLinesAttribute()
    {
        $events       = $this->events;
        $docSentEvent = $fqValidatedEvent = null;

        if ($this->type === 'invoice') {
            $docNumLabel  = 'Facture n°';
            $docSentEvent =
                $events->where('type', CommercialdocEventType::INVOICE_SENT)->first() ??
                $events->where('type', CommercialdocEventType::FINAL_QUOTE_SENT)->first();
        } else {
            $docNumLabel = 'Devis n°';
            if ($this->status->finalQuoteWasSent()) {
                $docSentEvent     = $events->where('type', CommercialdocEventType::FINAL_QUOTE_SENT)->first();
                $fqValidatedEvent = $events->where('type', CommercialdocEventType::QUOTE_VALIDATED)->first();
            }
        }

        return array_values(array_filter([
            ['code' => 'QN', 'label' => $docNumLabel, 'value' => $this->doc_id],
            ['code' => 'DC', 'label' => 'Date', 'value' => $docSentEvent?->created_lclzed ?? $this->created_lclzed],
            ['code' => 'DV', 'label' => 'Validé', 'value' => $fqValidatedEvent?->created_lclzed],
            ['code' => 'DL', 'label' => 'Echéance', 'value' => $this->deadline_lcl],
            ['code' => 'CR', 'label' => 'Monnaie', 'value' => "{$this->currency->code} ({$this->currency->nom_monnaie})"],
        ], fn($line) => $line['value']));
    }

    public function getTravelersAttribute()
    {
        return $this->reservation->participants->sortBy('idx')->sortByDesc('adulte')->values();
    }

    /*
        // get status() {
        //     switch (this.status) {
        //       case 1: return { label: 'Nouveau', color: "#f9b3b3" }; // devis initial envoyé
        //       case 2: return { label: 'En cours', color: "#e1cd62" }; // devis finial envoyé (TODO: message de rappel après 2j)
        //       case 3: return { label: 'Validé', color: "#7eca49" };
                    // devis final validé par client
                    // quote becomes invoice before next step
        //       case 3B: mail sent with amount of deposit and choice of payment method:
                    //    bank transfer, cache, CC (with link to pay online)
        //       case 4: return { label: 'Attente paiement', color: "#ffda6c" };
                    // deposit received
        //       case 5: return { label: 'Accompte payé', color: "#00dff7" };
        //        // send finial invoice indicating payment already done
        //       case 6: return { label: 'Annulé', color: "#f00" };
        //       case 7: annulé par client
        //       case 8: annulé par admin
        //   };
        // }
    */
}
