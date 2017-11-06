<?php

namespace Moip\Helper;

/**
 * Class Utils.
 */
class Utils
{
    /**
     * convert a money amount (represented by a float or string (based on locale) ie.: R$ 5,00) to cents (represented by an int).
     *
     * @param float $amount
     *
     * @throws \UnexpectedValueException
     *
     * @return int
     */
    public static function toCents($amount)
    {
        /*
         * There's probably a better way, but this is what i could come up with
         * to avoid rounding errors
         * todo: search for a better way
         */

        if (!is_float($amount)) {
            $type = gettype($amount);

            throw new \UnexpectedValueException("Needs a float! not $type");
        }

        //handle locales
        $locale = localeconv();

        $amount = str_replace($locale['mon_thousands_sep'], '', $amount);
        $amount = str_replace($locale['mon_decimal_point'], '.', $amount);
        $amount = str_replace($locale['decimal_point'], '.', $amount);

        $parts = explode('.', "$amount");

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

        $whole = (int) $whole * 100;
        $fraction = (int) $fraction;

        return $whole + $fraction;
    }
}
