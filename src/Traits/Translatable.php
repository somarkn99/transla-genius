<?php

namespace CodingPartners\TranslaGenius\Traits;

use CodingPartners\TranslaGenius\Jobs\TranslateFields;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

/**
 * Trait Translatable
 *
 * This trait provides functionality for translating fields in an Eloquent model.
 * It automatically dispatches translation jobs when a model is created or updated,
 * and provides a scope to filter fully translated models.
 *
 * @package CodingPartners\TranslaGenius\Traits
 */
trait Translatable
{
    use HasTranslations;

    /**
     * Boot the Translatable trait.
     *
     * This method sets up event listeners for the model's `created` and `updated` events.
     * When a model is created or updated, it dispatches a job to translate the specified fields.
     *
     * @return void
     */
    protected static function bootTranslatable()
    {
        static::created(fn($model) => TranslateFields::dispatch($model, $model->translatable));

        static::updated(function ($model) {
            $model->forgetAllTranslations(get_target_language());
            TranslateFields::dispatch($model, $model->translatable);
        });
    }

    /**
     * Scope to filter models that are fully translated in the target language.
     *
     * This scope checks if all translatable fields have a translation in the target language.
     *
     * @param Builder $query The Eloquent query builder instance.
     * @return Builder The modified query builder instance.
     */
    public function scopeFullyTranslated(Builder $query)
    {
        $targetLanguage = get_target_language();

        $conditions = array_map(
            fn($field) => "JSON_UNQUOTE(JSON_EXTRACT($field, '$.\"$targetLanguage\"')) IS NOT NULL",
            $this->translatable
        );

        return $query->whereRaw(implode(' AND ', $conditions));
    }
}
