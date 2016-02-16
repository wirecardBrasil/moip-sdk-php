<?php
namespace Moip;

class Utils {

    /**
     * convert a money amount (represented by a float) to cents (represented by an int).
     * @param float $amount
     * @return int
     */
    public static function toCents($amount) {

        /*
         * There's probably a better way, but this is what i could come up with
         * to avoid rounding errors
         * todo: find a better way
         */

        $parts = explode(".", "$amount");

        // handle the case where $amount has a .0 fraction part
        if (count($parts) == 1) {
            $parts[] = '00';
        }

        list($whole, $fraction) = $parts;

       /*
        * since the documentation only mentions decimals with a precision of two
        * and doesn't specify any rounding method i'm truncating the number
        *
        * the str_pad is to handle the case where $amount is, for example, 6.9
        */
        $fraction = str_pad(substr($fraction, 0, 2), 2, '0');

        $whole = (int)$whole * 100;
        $fraction = (int)$fraction;

        return $whole + $fraction;
    }

}