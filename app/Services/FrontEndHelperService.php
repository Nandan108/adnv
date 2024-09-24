<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection as BaseCollection;

class FrontEndHelperService
{
    /**
     * Array of regex patterns and replacements for class names.
     *
     * @var array
     */
    protected $classnameToFrontendTransforms = [
        '/^App.Models./'         => '',
        '/^Chambre/'             => 'Room',
        '/^Assurance/'           => 'Insurance',
        '/^Commercialdoc.Quote/' => 'Commercialdoc',
        '/^Lieu/'                => 'Location',
        '/^Monnaie/'             => 'Currency',
        '/^Pays/'                => 'Country',
        '/Reservation/'          => 'Booking',
        '/^TypePrestation/'      => 'HotelServiceType',
        '/Prestation/'           => 'HotelService',
        '/^Vol/'                 => 'Flight',
        '/^FlightPrix/'          => 'FlightFare',
        '/^Voyageur/'            => 'Traveler',
    ];

    protected function classnameToFrontend($classname)
    {
        return preg_replace(
            array_keys($this->classnameToFrontendTransforms),
            array_values($this->classnameToFrontendTransforms),
            $classname,
        );
    }

    /**
     * Applies the class name replacements to the extracted relations.
     *
     * @param \Illuminate\Support\Collection $extractedRelations The collection of extracted relations.
     * @return \Illuminate\Support\Collection The transformed collection.
     */
    protected function transformClassNames(BaseCollection $extractedRelations): BaseCollection
    {
        return $extractedRelations
            // Convert to indexed arrays
            ->map(fn($collection) => collect($collection)->values())
            // Convert class name to front-end naming
            ->mapWithKeys(fn($collection, $classname) =>
                [$this->classnameToFrontend($classname) => $collection]);
    }

    public function convertToEntityName(string $frontEndClassname): string
    {
        // Special handling for specific models
        $specialCases = [
            //'Commercialdoc\\Quote'   => 'commercialdoc',
            //'Commercialdoc\\Invoice' => 'commercialdoc',
        ];

        return $specialCases[$frontEndClassname] ??
            // Default conversion: base class name in lowercase
            strtolower(class_basename($frontEndClassname));
    }

    /**
     * Extracts and normalizes related models from a root Eloquent collection based on the
     * provided relationship paths. The function traverses the specified relationships,
     * flattens any "hasMany" relationships, and aggregates the related models into a
     * collection grouped by their class names, preserving the primary keys to prevent duplicates.
     *
     * @param \Illuminate\Database\Eloquent\Collection $rootCollection The root collection to extract relations from.
     * @return \Illuminate\Support\Collection A collection of extracted related models,
     *  stripped of their relations and grouped by class name.
     */
    public function extractNormalizedRelations(
        BaseCollection $rootCollection,
    ): BaseCollection {
        $extractedRelations = collect();
        $morphFieldsByClass = [];


        // Closure to add a stripped model to the extractedRelations collection
        $addStripped = function ($model) use (&$extractedRelations) {
            $class = get_class($model);
            $key = $model->getKey();

            // If the model has already been added, return false to prevent reprocessing
            if (isset($extractedRelations[$class][$key])) return false;

            // Add the model to the collection, without relations
            $extractedRelations[$class] ??= collect();
            $extractedRelations[$class][$key] = $model->withoutRelations();

            return true;
        };

        $processRelations = function (Model $model) use (&$processRelations, $addStripped, &$morphFieldsByClass) {
            // attempt to add this model to the collection of relations, and stop here if we couldn't
            if (!$addStripped($model)) return;

            // If it worked (meaning it wasn't already there) then add all its relations as well
            $modelRelations = collect($model->getRelations())->filter();
            if ($modelRelations->isEmpty()) return;
            foreach ($modelRelations as $relationName => $relatedModels) {
                if (method_exists($model, $relationName)) {
                    $relation = $model->$relationName();
                    if ($relation instanceof MorphOneOrMany) {
                        $mt = $relation->getMorphType();
                        $morphFieldsByClass[get_class($relation->getRelated())][$mt] ??= $mt;
                    }
                }

                $relatedModels = $relatedModels instanceof BaseCollection ? $relatedModels : collect([$relatedModels]);
                $relatedModels->each($processRelations);
            }
        };

        $rootCollection->each($processRelations);

        // convert backend class names to front-end
        foreach ($morphFieldsByClass as $classname => $morphFields) {
            foreach ($morphFields as $morphField) {
                foreach ($extractedRelations[$classname] ?? [] as $model) {
                    $model->$morphField = $this->classnameToFrontend($model->$morphField);
                    $model->$morphField = $this->convertToEntityName($model->$morphField);
                    continue;
                }
            }
        }

        return $this->transformClassNames($extractedRelations);
    }

}
