<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key
    |--------------------------------------------------------------------------
    |
    | This key is used to authenticate requests to the OpenAI translation API.
    |
    */
    'api_key' => env('OPENAI_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | API Endpoint
    |--------------------------------------------------------------------------
    |
    | The URL used to send requests to the translation API.
    |
    */
    'api_url' => env('TRANSLATION_API_URL', 'https://openrouter.ai/api/v1/chat/completions'),

    /*
    |--------------------------------------------------------------------------
    | Default Model
    |--------------------------------------------------------------------------
    |
    | The AI model used for translation processing.
    |
    */
    'model' => env('TRANSLATION_MODEL', 'openai/gpt-4o'),

    /*
    |--------------------------------------------------------------------------
    | Translation Settings
    |--------------------------------------------------------------------------
    |
    | Additional settings such as temperature and max tokens to adjust translation behavior.
    |
    */
    'temperature' => env('TRANSLATION_TEMPERATURE', 0.3),
    'max_tokens' => env('TRANSLATION_MAX_TOKENS', 200),
];
