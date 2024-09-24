<?php

namespace App\Contracts;
use Illuminate\Support\Collection;
use WeakMap;

interface PersonTypeManagerInterface {
    public function getPersonCounts(Collection $voyageurs): Collection;
    public function getVoyageurPersonTypesIdx(Collection $voyageurs, $slots = null): WeakMap;
    public function getVoyageursByPersonTypes(Collection $voyageurs, $slots = null): Collection;
}
