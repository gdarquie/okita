<?php

namespace App\Service;


class HumanTimeConverterService
{
    public function convert($seconds, $type = 'default')
    {
        $minutes = ($seconds/60);

        return $minutes;
    }
}