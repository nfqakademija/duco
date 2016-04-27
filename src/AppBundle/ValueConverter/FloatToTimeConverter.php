<?php

namespace AppBundle\ValueConverter;

use Ddeboer\DataImport\ValueConverter\ValueConverterInterface;

class FloatToTimeConverter implements ValueConverterInterface
{
    /**
     * @param mixed $input
     * @return string|void
     */
    public function convert($input)
    {
        if (!$input) {
            return;
        }
        $time =  $input * 86400;
        $hours = floor($time / 3600);
        $minutes = floor($time / 60) - ($hours * 60);
        $seconds = floor($time) - ($hours * 3600) - ($minutes * 60);
        $dateTime = new \DateTime();
        $dateTime->setTime($hours, $minutes, $seconds);
        return $dateTime->format('H:i:s');
    }
}
