<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use WeakMap;

trait HasPersonTypes
{
    /** This function define airline industry standard ages limits for babies and children.
     * That is, once a baby is 2 years of age, they're no longer considered a "baby"
     * passenger. Similarely, a child is considered an adulte starting at their 12th birthday.
     * This function should overriden by any class that needs different age limits.
     */
    public function getPersonTypeMaxAges()
    {
        return [
            'bebe'   => 1,
            'enfant' => 11,
        ];
    }

    /** This method should return the number of admitted clients for each type
     * as an array of [$personType => $maxAllowedNumber], or null if there's no
     * restriction.
     * This data will allow bumping excess passengers of one type to the mext
     * higher type. E.g. if there's two babies, but only one baby slot, then
     * the second "baby" should be bumped up to "child".
     */
    public function getPersonSlots(): array|null
    {
        return null;
    }

    /**
     * Returns the person type (adulte, enfant or bebe) according to birthdate,
     *  trip date and age_max_<person type> rules of the object using the trait.
     * This trait assumes the presence of properties age_max_bebe and age_max_enfant.
     *  If this is not the case, you may override the function getPersonTypeMaxAges().
     * @param string|null $date_naissance Traveler birthdate
     * @param string $debut_voyage Trip start date
     * @return string person type (adulte, enfant or bebe)
     */
    public function getPersonType(int|null $age, &$slots = null): string
    {
        //if (is_null($age)) return 'adulte';
        // Situation where a baby is bumped to a child slot:
        // For a reservation for 1 baby and 1 child (2yo), once birthdates are entered, the
        // hotel might have an age limit such that a 2yo is a baby, so we have 2*baby, 0*child.
        // However, the room may only accept 1 baby, 1 child and 2 adults. So the 2yo should
        // get bumped to take the child slot. However, does the hotel accept a baby in a child slot?
        // Situation where a child is bumped to teen or adult ?
        // That might be if we have more children that available child slots.
        foreach ($this->getPersonTypeMaxAges() as $type => $maxAge) {
            if (($age ?? 99) <= $maxAge && ($slots === null || $slots[$type]--)) {
                return $type;
            }
        }

        if ($slots && ($slots['adulte'] ?? 0) === -1) {
            throw new \Exception("Passenger overflow!");
        }
        ;

        return 'adulte';
    }

    public function getVoyageurPersonTypesIdx(Collection $voyageurs): WeakMap
    {
        $slots        = $this->getPersonSlots();
        $idxByType    = [];
        $personIdxMap = new WeakMap();

        $voyageurs
            ->sortBy(['idx', 'age'])
            ->each(function ($v) use (&$slots, &$idxByType, &$personIdxMap) {
                $type             = $this->getPersonType($v->age, $slots);
                $idx              = $idxByType[$type] = ($idxByType[$type] ?? 0) + 1;
                $personIdxMap[$v] = [$type, $idx];
            });

        return $personIdxMap;
    }

    public function getVoyageursByPersonTypes(Collection $voyageurs): Collection
    {
        return $voyageurs->groupBy(
            fn($v) => $this->getPersonType($v->age)
        );
    }

    public function getPersonCounts(Collection $voyageurs): Collection
    {
        return $this->getVoyageursByPersonTypes($voyageurs)
            ->map(fn($voyageurs) => count($voyageurs));
    }
}
