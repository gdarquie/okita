<?php

namespace App\Service;

use App\Entity\Action;

/**
 * Class ActionWriterService
 * @package App\Service
 */
class ActionWriterService
{
    /**
     * @param $action
     * @return mixed
     */
    public function write(Action $action)
    {
        $title = $action->getTitle();
        $description = $this->getAction($title);

        return $description;
    }

    /**
     * @param $title
     * @return string
     */
    private function getAction($title)
    {
        if ($title === 'sleep') {
            return 'X dormit d’une traite, d’un sommeil lourd et profond. Au réveil, elle.il se sentait parfaitement reposé.e.
';
        }

        else if ($title === 'play') {
            return 'X joua.';
        }

        else if ($title === 'dance') {
            return 'X dansa.';
        }

        else {
            return $title;
        }
    }
}