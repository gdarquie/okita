<?php

namespace App\Domain\Actions;

abstract class AbstractAction
{
    /**
     * @param array $texts
     * @return array
     */
    public function getTextes(Array $texts): array
    {
        return $texts;
    }
}