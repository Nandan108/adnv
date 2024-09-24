<?php

namespace App\Http\Controllers;
use App\Services\FrontEndHelperService;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class Controller
{
    public function __construct(
        protected FrontEndHelperService $frontEndHelperService
    ) {}

    public function normalizeModels(Model|array|Arrayable $models): Collection {
        // make $models a Collection
        $FEHService = $this->frontEndHelperService ??= app(FrontEndHelperService::class);
        $models = collect($models instanceof Model ? [$models] : $models);
        $normalised = $FEHService->extractNormalizedRelations($models);
        return $normalised;
    }
}
