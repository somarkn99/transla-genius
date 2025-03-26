<?php

namespace CodingPartners\TranslaGenius\Jobs;

use CodingPartners\TranslaGenius\Services\AutoTranslationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
        try {
            $currentLocale = get_current_locale();
            $targetLanguages = get_supported_languages();
            $translations = [];

            foreach ($this->fields as $field) {
                $originalText = is_array($this->model->{$field})
                    ? ($this->model->{$field}[$currentLocale] ?? reset($this->model->{$field}))
                    : $this->model->{$field};

                $translations[$field] = [
                    $currentLocale => $originalText
                ];

                foreach ($targetLanguages as $targetLanguage) {
                    if (empty($this->model->{$field}[$targetLanguage])) {
                        $translations[$field][$targetLanguage] = $translationService->translate(
                            $originalText,
                            $currentLocale,
                            $targetLanguage
                        );
                    }
                }
            }

            if (!empty($translations)) {
                $this->model->withoutEvents(fn() => $this->model->update($translations));
            }
        } catch (\Throwable $th) {
            Log::error("Translation job failed", [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);
        }
    }
}
