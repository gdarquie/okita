<?php

namespace App\Service;

use App\Entity\Action;
use App\Entity\Character;

/**
 * Class ActionWriterService
 * @package App\Service
 */
class ActionWriterService
{
    /**
     * @var Character
     */
    private $character;

    /**
     * @var Action
     */
    private $action;

    public function __construct(Character $character)
    {
        $this->character = $character;

    }

    /**
     * @param $action
     * @return mixed
     */
    public function write(Action $action)
    {
        $this->action = $action;
        $title = $this->action->getTitle();
        $description = $this->getAction($title);
        $description = $this->translateAction($description);

        return $description;
    }

    /**
     * @param $title
     * @return string
     */
    private function getAction($title)
    {
        // todo : améliorer le random
        if ($title === 'sleep') {

            $sleep[0] = '{{character.name}} ne dormit pas très bien. {{character.pronoun}} passa la nuit à se retourner dans le lit. {{character.name}} ne se sentait pas en forme au réveil.';

            $sleep[1] = '{{character.name}} dormit d’une traite entre {{action.start}} et {{action.end}} , d’un sommeil lourd et profond. Au réveil, {{character.pronoun}} se sentait parfaitement reposé.e.';

            $random = (rand(1, count($sleep))-1);

            return $sleep[$random];
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
     * @param $text
     * @return string
     */
    public function getCharacterName($string)
    {
        ($string === '{{character.name}}') ? $result = true : $result = false;

        return $result;
    }

    public function getSpecialString($string)
    {
        $result = false;
        $pattern = "#\{\{(.*)\}\}#";

        if(preg_match($pattern, $string)){
            $result = true;
        }

        return $result;
    }
    
    /**
     * @param String $text
     * @return string
     */
    private function translateAction(String $text): string
    {
        $arrayText = explode(' ',$text);

        // Transform name and action
        $arrayFiltered = array_filter($arrayText, array($this, 'getSpecialString'));

        foreach($arrayFiltered as $key => $value)
        {
            if($arrayText[$key] === '{{character.name}}')
            {
                $arrayText[$key] = $this->character->getName();
            }

            else if ($arrayText[$key] === '{{action.start}}')
            {
                $arrayText[$key]  = $this->action->getStartAt();
            }

            else if ($arrayText[$key] === '{{action.end}}')
            {
                //todo : add human time conversion
                $arrayText[$key]  = $this->action->getEndAt();
            }
        }

        // Transform pronoun
        // todo : faire quelque chose s'il y a plusieurs personnages
        (in_array('{{character.pronoun}}', $arrayText)) ? $arrayText[array_search('{{character.pronoun}}', $arrayText)]= $this->character->getPronoun($this->character->getPronoun()) : '';


        // Transform inclusive language

        $translation = implode(' ', $arrayText);

        return $translation;
    }

}