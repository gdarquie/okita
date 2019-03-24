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
        // get all files from Action folder
        $files = $this->getFiles();

        // check if action name has a yml file corresponding
        if(!array_key_exists($title, $files)){
            return '{{character.name}} fit une action.';
        }

        $value = Yaml::parseFile($files[$title]);

        if(isset($value['textes.bad'])) {
            // possibilité d'ajouter un score
            $textes = $value['textes.bad'];
        }

        else{
            $textes = $value['textes'];
        }

        $random = (rand(1, count($textes))-1);

        //todo : save the action
        return $textes[$random];
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

    /**
     * @return array
     */
    private function getFiles(): array
    {
        // all contents for actions are in yaml files in /Actions, we use finder to get all files
        $finder = new Finder();
        $finder->in('../src/Domain/Actions');

        // we catch all name of the file for converting it in action name ($title)
        $files = [];
        foreach ($finder as $file) {

            // get all files names and convert into action
            preg_match('/(.*)\.y[a]ml/', $file->getBasename(), $output_array);

            if((isset($output_array[1])))
            {
                $fileName = strtolower($output_array[1]);
                // associate files names with action path
                $files[$fileName] =  $file->getRealPath();
            }
        }

        return $files;
    }
}