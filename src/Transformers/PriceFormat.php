<?php

namespace Egyjs\FatooraZatca\Transformers;

class PriceFormat
{
    /**
     * transform the price number to a format.
     *
     * @param  mixed $number
     * @return string
     */
    public function transform($number): string
    {
        return number_format((float) $number, 2, '.', '');
    }
}
