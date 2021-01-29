<?php
if (!function_exists('failureResponse')) {
    /**
     * @param array $errors
     * @param int $code
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    function failureResponse(int $code, string $message = null, array $errors = array()){
        return response()->json(['error' => $errors, 'message' => $message], $code);
    }


}
if (!function_exists('successResponse')) {
    /**
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    function successResponse(string $message = null, array $data = null){
        return response()->json(['message' => $message, 'data' => $data], 200);
    }
}

if (!function_exists('convertDateToUTC')) {

    /**
     * @param $datetime
     * @return string
     * @throws Exception
     */
    function convertDateToUTC($datetime)
    {
        $datetimeUTC = new DateTime($datetime);
        $datetimeUTC->setTimezone(new DateTimeZone("UTC"));
        return $datetimeUTC->format("Y-m-d H:i:s");
    }
}
