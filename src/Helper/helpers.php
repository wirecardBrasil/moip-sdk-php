<?php

if (!function_exists('to_cents')) {
    /**
     * convert a money amount (represented by a float or string (based on locale) ie.: R$ 5,00) to cents (represented by an int).
     *
     * @param float $amount
     *
     * @throws \UnexpectedValueException
     *
     * @return int
     */
    function to_cents(float $amount)
    {
        return Moip\Helper\Utils::toCents($amount);
    }
}

if (!function_exists('pr')) {
    /**
     * print_r() convenience function.
     *
     * In terminals this will act similar to using print_r() directly, when not run on cli
     * print_r() will also wrap <pre> tags around the output of given variable. Similar to debug().
     *
     * This function returns the same variable that was passed.
     *
     * @param mixed $var Variable to print out.
     *
     * @return mixed the same $var that was passed to this function
     *
     * @link http://book.cakephp.org/3.0/en/core-libraries/global-constants-and-functions.html#pr
     * @see debug()
     */
    function pr($var)
    {
        $template = '<pre class="pr">%s</pre>';
        printf($template, trim(print_r($var, true)));

        return $var;
    }
}
