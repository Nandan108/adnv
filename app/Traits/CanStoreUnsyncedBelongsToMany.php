<?php

namespace App\Traits;

use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait CanStoreUnsyncedBelongsToMany
 *
 * This trait extends Eloquent models to handle unsynced (yet-unpersisted) BelongsToMany relationships.
 * It allows for managing and retrieving both persisted and unsynced related models, ensuring that
 * temporary relationship changes are accessible before they are saved to the database.
 *
 * @author Samuel de Rougemont <nandan108@gmail.com>
 * @copyright 2024 Samuel de Rougemont <nandan108@gmail.com>
 * @license MIT
 */
trait CanStoreUnsyncedBelongsToMany
{
    protected $_unsavedPivots = [];

    /**
     * Load BelongsToMany relationships via unsaved pivots.
     *
     * @param BelongsToMany $relation The name of the relation to load.
     * @return Collection The loaded models
     */
    public function loadUnsynced(BelongsToMany $relation): Collection
    {
        // Get the name of the relation
        $relationName = $relation->getRelationName();

        // Quit if there are no unsaved pivots for the given relation name
        $pivots = collect($this->unsavedPivots[$relationName] ?? []);
        if ($pivots->isEmpty()) return new Collection();

        // Get the key name for the related models
        $relatedKey = $relation->getRelatedKeyName();

        // Get the keys of loaded and not-yet loaded models directly from the relation
        $loadedKeys   = $relation->pluck($relatedKey);
        $unloadedKeys = $pivots->pluck($relatedKey);

        // Get the keys from the unsaved pivots and exclude already loaded keys
        $keysToLoad = $unloadedKeys->diff($loadedKeys);

        // Quit if there are no keys left to load
        if ($keysToLoad->isEmpty()) return new Collection();

        // Fetch the related models using the unsaved pivot keys
        $models = $relation->getRelated()::whereIn($relatedKey, $keysToLoad)->get();

        // Match the loaded models to the current model
        $relation->match([$this], $models, $relatedKey);

        // Return the combined relation
        return $models;
    }

    /**
     * Get a relationship.
     * This overrides the parent class to allow return models from
     * yet-unpersisted relationships.
     *
     * @param  string  $key
     * @return void
     */
    #[\Override] // see https://www.php.net/manual/en/class.override.php
    public function getRelationValue($relationName)
    {
        // Retrieve the relation from the parent model
        $models = parent::getRelationValue($relationName);

        // If the relation is an instance of BelongsToMany...
        if (($relation = $this->$relationName()) instanceof BelongsToMany) {
            // Load unsynced models
            $unsyncedModels = $this->loadUnsynced($relation);

            if ($unsyncedModels->isNotEmpty()) {
                // Merge persisted and unsynced models
                $models = $models->merge($unsyncedModels);

                // Set the merged models as the relation
                $this->setRelation($relationName, $models);
            }
        }

        // If the relation is not BelongsToMany, return the default relation value
        return $models;
    }

    public function setUnsyncedPivots(string $relationName, BaseCollection $models)
    {
        $this->_unsavedPivots[$relationName] = $models;
    }

    public function getUnsyncedPivots(string $relationName): BaseCollection|null
    {
        return $this->_unsavedPivots[$relationName] ?? null;
    }

    /**
     * Overrides parent::save() to also save unsaved pivots
     * @param array $options
     * @return bool
     */
    #[\Override]
    public function save(array $options = [])
    {
        $saved = parent::save($options);

        // after saving the model, save pending pivots
        foreach ($this->_unsavedPivots as $relationName => $pivots) {
            // get
            $relation = $this->$relationName();
            $relation->sync($pivots);

            unset($this->_unsavedPivots[$relationName]);
        }

        return $saved;
    }

}
