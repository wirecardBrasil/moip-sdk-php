<?php

if (! function_exists('to_cents')) {
    /**
     * convert a money amount (represented by a float or string (based on locale) ie.: R$ 5,00) to cents (represented by an int).
     *
     * @param float $amount
     *
     * @throws \UnexpectedValueException
     *
     * @return int
     */
    function to_cents(float $amount): int
    {
        return Moip\Helper\Utils::toCents($amount);
    }
}
