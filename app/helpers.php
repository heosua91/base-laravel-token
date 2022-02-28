<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('api_format')) {
    /**
     * API Format
     * Ref: https://google.github.io/styleguide/jsoncstyleguide.xml#JSON_Structure_&_Reserved_Property_Names
     * 
     * @param  array  $data
     * @param  bool  $isError
     * @return array
     */
    function api_format(array $data, bool $isError = false): array
    {
        $apiFormat = array_merge([
            'apiVersion' => config('app.api_version', '1.0.0'),
            'api' => request()->path(),
        ], [($isError ? 'error' : 'data') => empty($data) ? (object)[] : $data]);

        return $apiFormat;
    }
}
