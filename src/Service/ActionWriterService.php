<?php

namespace App\Service;

use App\Entity\Action;
use App\Entity\Character;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

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
     * @var HumanTimeConverterService
     */
    private $humanTimeConverterService;

    /**
     * @var Action
     */
    private $action;

    public function __construct(Character $character)
    {
        $this->character = $character;
        $this->humanTimeConverterService = new HumanTimeConverterService();


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
        $finder = new Finder();
        $finder->in('../src/Domain/Actions');

        // get all files
        $files = [];
        foreach ($finder as $file) {
            array_push($files, $file->getRealPath());
        }

        $value = Yaml::parseFile($files[0]);
        $textes = $value['textes'];

        //todo : get the name before .yml and after last/ and lowecase it

        if ($title === 'sleep') {
            $random = (rand(1, count($textes))-1);
            return $textes[$random];
        }

        else if ($title === 'play') {
            return '{{character.name}} joua.';
        }

        else if ($title === 'dance') {
            return '{{character.name}} dansa.';
        }

        else if ($title === 'work') {
            return '{{character.name}} travailla de {{action.start}} à {{action.end}} .';
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
        $pattern = "/\{\{(.*)\}\}/";

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
                $arrayText[$key] = $this->action->getStartAt();
                $arrayText[$key] = $this->humanTimeConverterService->convert($arrayText[$key], 'default');
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