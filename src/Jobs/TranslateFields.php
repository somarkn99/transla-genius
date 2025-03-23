<?php

namespace CodingPartners\AutoTranslator\Jobs;

use CodingPartners\AutoTranslator\Services\AutoTranslationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TranslateFields implements ShouldQueue
{
    use Queueable;

    protected $model;
    protected $fields;

    /**
     * Create a new job instance.
     */
    public function __construct($model, $fields)
    {
        $this->model = $model;
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     */
    public function handle(AutoTranslationService $translationService)
    {
        $translations = [];

        foreach ($this->fields as $field) {
            if (isset($this->model->{$field}) && empty($this->model->{$field}[get_target_language()])) {
                $translations[$field] = [
                    app()->getLocale() => $this->model->{$field},
                    get_target_language() => $translationService->translate($this->model->{$field})
                ];
            }
        }

        if (!empty($translations)) {
            $this->model->withoutEvents(fn() => $this->model->update($translations));
        }
    }
}
