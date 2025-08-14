<?php

namespace App\Http\Controllers\Errors;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ErrorTranslator extends Controller
{
    // public static function translateError($code) {
    //     $messages = [
    //         '23000' => "Oops! That item already exists. Please try something different.",
    //         '42000' => "Something's wrong with the data you're trying to submit.",
    //         '42S02' => "We couldn't find the data you're trying to access.",
    //         '42S22' => "Some information is missing or incorrect.",
    //         '28000' => "Access denied. Please double-check your credentials.",
    //         'HY000' => "Something went wrong. Please try again later.",
    //         '22001' => "One of the fields has too much text. Please shorten your input.",
    //         '22007' => "Invalid date or time format.",
    //         '40001' => "We're having trouble saving your data due to a system conflict.",
    //     ];

    //     return $messages[$code] ?? "An unexpected error occurred. Please try again or contact support. (Error Code: $code)";
    // }

    public static function translateError($codeOrException)
    {
        $messages = [
            '23000' => "That record already exists. Try something different.",
            '42S02' => "Missing table. Something's broken internally.",
            // etc...
        ];

        // If a full exception is passed (optional)
        if ($codeOrException instanceof Exception) {
            $code = $codeOrException->getCode();
        } else {
            $code = $codeOrException;
        }

        return $messages[$code] ?? null;
    }
}
