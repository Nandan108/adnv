<?php

namespace App\Http\Controllers\Booking;

use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CaptchaController
{
    /**
     * Generates a new captcha image and returns its content as a string
     * to be used as value for an img's src property
     * @param float $sleep
     * @return string
     */
    public static function generateInlineImage(float $sleep = 1)
    {
        $captchaBuilder = new CaptchaBuilder();
        $captchaBuilder->build();
        session(['captcha-phrase' => $phrase = $captchaBuilder->getPhrase()]);

        self::sleep(1);

        return $captchaBuilder->inline();
    }

    /**
     * For use in an HTTP GET request
     * @return Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function getNewCaptchaImage() {
        return response(['image' => self::generateInlineImage(sleep: 1)]);
    }

    public static function sleep(float $sleep = 1) {
        sleep($intSleep = floor($sleep));
        usleep(($sleep - $intSleep) * 1000000);
    }

    /**
     * Summary of check
     * @param mixed $code
     * @return mixed
     */
    public function check($code) {
        self::staticCheck($code, $newCaptchaResponse);
        return $newCaptchaResponse;
    }

    /**
     * Check if the captcha
     */
    public static function staticCheck(string $code, &$response): false | Response
    {
        $phrase = session('captcha-phrase');
        $captchaBuilder = new CaptchaBuilder(session('captcha-phrase'));

        if ($captchaBuilder->testPhrase($code)) {
            return $response = response(['success' => true]);
        }

        // an invalid captcha check implies 2sec wait before a an error response
        sleep(2);

        $response = response()->json(['success' => false], 400);

        return false;
    }
}
