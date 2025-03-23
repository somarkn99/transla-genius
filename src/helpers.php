<?php

if (!function_exists('get_target_language')) {
    /**
     * Determine the target language based on the current locale.
     *
     * @return string
     */
    function get_target_language()
    {
        return app()->getLocale() == 'en' ? 'ar' : 'en';
    }
}
