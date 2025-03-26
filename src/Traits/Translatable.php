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
            $model->forgetAllTranslations(get_current_locale());
            TranslateFields::dispatch($model, $model->translatable);
        });
    }

    /**
     * Scope to filter models that are fully translated in all target languages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullyTranslated(Builder $query)
    {
        $targetLanguages = get_supported_languages();

        foreach ($this->translatable as $field) {
            $query->where(function ($q) use ($field, $targetLanguages) {
                foreach ($targetLanguages as $language) {
                    $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(`$field`, '$.$language')) IS NOT NULL");
                }
            });
        }

        return $query;
    }
}
