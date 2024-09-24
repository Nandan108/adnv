<?php
namespace App\Traits;

use Illuminate\Support\Collection;
use WeakMap;

/**
 * Trait HasPersonTypes
 *
 * This trait extends Eloquent models to represent objects that have different
 * tarifs that depend on age classes, such as airline flights and hotel rooms.
 *
 * @author Samuel de Rougemont <nandan108@gmail.com>
 * @copyright 2024 Samuel de Rougemont <nandan108@gmail.com>
 * @license MIT
 */

trait HasPersonTypes
{
    /**
     * This function defines airline industry standard ages limits for babies and children.
     * That is, once a baby is 2 years of age, they're no longer considered a baby passenger.
     * Similarely, a child is considered an adulte starting at their 12th birthday.
     * This function should be overriden by any class that needs different age limits.
     */
    public function getPersonTypeMaxAges(): array|null
    {
        return [
            'bebe'   => 1,
            'enfant' => 11,
        ];
    }

    /**
     * This method should return the number of admitted clients for each type
     * as an array of [$personType => $maxAllowedNumber], or null if there's no
     * restriction.
     * This data will allow bumping excess passengers of one type to the mext
     * higher type. E.g. if there's two babies, but only one baby slot, then
     * the second "baby" may be bumped up to "child".
     */
    public function getPersonSlots(): array|null
    {
        return null;
    }

     /**
      * Check whether bumping is allowed.
      * Classes using HasPersonType should override this method.
      * @param mixed $fromType
      * @param mixed $toType
      * @return boolean
      */
     public function allowBumping($fromType, $toType) {
        return true;
     }

    /**
     * Returns the person type (adulte, enfant or bebe) according to birthdate,
     *  trip date and age limits by slot type, as defined by $ageLimits or $this->getPersonTypeMaxAges().
     *
     * Side effect: This function will decrease the corresponding $slot[type] by one.
     *
     * @param int|null $age Traveler age
     * @param array|null $slots
     * @param array|null $ageLimits
     * @throws \Exception
     * @return string
     */
    public function getPersonType(int|null $age, &$slots = null, $ageLimits = null): string
    {
        // if $age is NULL, consider it an adult but continue with the logic,
        // as there could still be a slot overflow.
        $age ??= 99;

        // Situation where a baby is bumped to a child slot:
        // A 2yo has a 'child' base type (Airline point of view), but is often considered a baby by hotels.
        // For a reservation for 1 baby and 1 2yo child, such a hotel would see 2 babies and 0 children.
        // However, the room may only accept maximum 1 baby, 1 child and 2 adults. So the 2yo should
        // get bumped to take the child slot. However, does the hotel accept a baby in a child slot?
        // Also, can a child be bumped to teen or adult ? That could happen if we have more children than
        // available child slots. This needs clarifying the policy with hotel manager.

        $ageLimits ??= $this->getPersonTypeMaxAges();
        $bumpedFrom = false;

        foreach ($ageLimits as $type => $maxAge) {
            if ($bumpedFrom && !$this->allowBumping($bumpedFrom, $type)) {
                throw new \Exception("Cannot bump from $bumpedFrom to $type due to policy restrictions!");
            }

            if ($age <= $maxAge) {
                if ($slots === null || $slots[$type]--) {
                    return $type;
                }
                $bumpedFrom = $type;
            }
        }

        if ($slots && ($slots['adulte'] ?? 0) === -1) {
            throw new \Exception("Person-type slot overflow!");
        }

        return 'adulte';
    }

    public function getVoyageurPersonTypesIdx(Collection $voyageurs, $slots = null): WeakMap
    {
        $slots ??= $this->getPersonSlots();
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

    public function getVoyageursByPersonTypes(Collection $voyageurs, $slots = null): Collection
    {
        return $voyageurs->groupBy(
            fn($v) => $this->getPersonType($v->age, $slots)
        );
    }

    public function getPersonCounts(Collection $voyageurs): Collection
    {
        return $this->getVoyageursByPersonTypes($voyageurs)
            ->map(fn($voyageurs) => count($voyageurs));
    }
}
