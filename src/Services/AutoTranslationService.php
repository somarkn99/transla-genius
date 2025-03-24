<?php

namespace CodingPartners\TranslaGenius\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;

/**
 * Class AutoTranslationService
 *
 * This class provides functionality to automatically translate text using an external API.
 * It handles the configuration and communication with the translation API, and returns the translated text.
 *
 * @package CodingPartners\TranslaGenius\Services
 */
class AutoTranslationService
{
    /**
     * @var string $apiKey The API key for authenticating with the translation service.
     */
    protected $apiKey;

    /**
     * @var string $apiUrl The URL of the translation API endpoint.
     */
    protected $apiUrl;

    /**
     * @var string $model The model to be used for translation.
     */
    protected $model;

    /**
     * @var float $temperature The temperature parameter for the translation model.
     */
    protected $temperature;

    /**
     * @var int $maxTokens The maximum number of tokens to generate in the translation.
     */
    protected $maxTokens;

    /**
     * AutoTranslationService constructor.
     *
     * Initializes the service by loading configuration values from the application's configuration.
     */
    public function __construct()
    {
        $this->apiKey      = config('translaGenius.api_key');
        $this->apiUrl      = config('translaGenius.api_url');
        $this->model       = config('translaGenius.model');
        $this->temperature = config('translaGenius.temperature');
        $this->maxTokens   = config('translaGenius.max_tokens');
    }

    /**
     * Translates the given text into the target language.
     *
     * @param string $text The text to be translated.
     * @return string The translated text.
     * @throws HttpResponseException If the translation fails, an exception is thrown with the error details.
     */
    public function translate($text)
    {
        $lang = get_target_language();

        $message = "Translate this text into " . $lang . ": " . $text;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl, [
            "model" => $this->model,
            'messages' => [["role" => "user", "content" => $message]],
            'temperature' => $this->temperature,
            "max_tokens" => $this->maxTokens
        ])->json();

        if (isset($response['error'])) {
            throw new HttpResponseException(response()->json([
                'message' => 'Translation failed',
                'error' => $response['error'],
            ], 500));
        }

        return $response['choices'][0]['message']['content'];
    }
}
