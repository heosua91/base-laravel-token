<?php

use Illuminate\Http\JsonResponse;

if (!function_exists('parse_json')) {
    /**
     * Parse JSON
     * Ref: https://google.github.io/styleguide/jsoncstyleguide.xml#JSON_Structure_&_Reserved_Property_Names
     * 
     * @param  array  $data
     * @param  int  $statusCode
     * @return JsonResponse
     */
    function parse_json(array $data, int $statusCode, bool $isError = false): JsonResponse
    {
        $dataFormat = array_merge([
            'apiVersion' => config('app.api_version', '1.0.0'),
            'api' => request()->path(),
        ], [($isError ? 'error' : 'data') => empty($data) ? (object)[] : $data]);

        return response()->json($dataFormat, $statusCode);
    }
}
