<?php

namespace App\Service;

use App\Entity\Action;
use App\Entity\Character;
use PhpParser\Node\Scalar\String_;

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
    public function write(Action $action, Character $character)
    {
        $title = $action->getTitle();
        $description = $this->getAction($title);
        $description = $this->translateAction($description, $character);

        return $description;
    }

    /**
     * @param $title
     * @return string
     */
    private function getAction($title)
    {
        if ($title === 'sleep') {
            return '{{character.name}} dormit d’une traite entre {{action.start}} et {{action.end}}, d’un sommeil lourd et profond. Au réveil, {{character.pronoun}} se sentait parfaitement reposé.e.
';
        }

        else if ($title === 'play') {
            return '{{character.name}} joua.';
        }

        else if ($title === 'dance') {
            return '{{character.name}} dansa.';
        }

        else {
            return $title;
        }
    }

    /**
     * @param String $text
     * @param Character $character
     * @return string
     */
    private function translateAction(String $text, Character $character): string
    {
        $arrayText = explode(' ',$text);

        // Transform name
        (in_array('{{character.name}}', $arrayText)) ? $arrayText[array_search('{{character.name}}', $arrayText)]= $character->getName() : '';

        // Transform pronoun
        (in_array('{{character.pronoun}}', $arrayText)) ? $arrayText[array_search('{{character.pronoun}}', $arrayText)]= $character->getPronoun($character->getPronoun()) : '';

        // Transform action

        // Transform inclusive language

        $translation = implode(' ', $arrayText);

        return $translation;
    }

}