<?php

namespace App\Services;

class Money
{
    public static function getAmount($m)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $m);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $m);
        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;
        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);
        return (float) str_replace(',', '.', $removedThousandSeparator);
    }

    public static function decimalFloat($num)
    {
        return number_format($num, 2, '.', '.');
    }

    public static function formatReal($num)
    {
        return number_format($num, 2, ',', '.');
    }

}
