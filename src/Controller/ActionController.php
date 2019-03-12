<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Character;
use App\Service\ActionWriterService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActionController extends AbstractController
{
    /**
     * @param Request $request
     * @param Character $character
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/personnages/{id}/actions", name="get_actions")
     * @ParamConverter("id", class="App\Entity\Character")
     */
    public function getActions(Request $request, Character $character)
    {
        // by default we select day 1
        ($day = $request->get('jour')) ? $time = $day*24*60*60 : $time = 24*60*60;

        // get all the actions of the day for the character
        $characterId = $character->getId();
        $actions = $this->getDoctrine()->getRepository(Action::class)->findByCharacter($characterId, $time);

        $actionWriterService = new ActionWriterService($character);
        $descriptions = [];

        foreach ($actions as $action) {
            array_push($descriptions, $actionWriterService->write($action));
        }

        return $this->render('actions.html.twig', array(
            'character' => $character,
            'actions' => $actions,
            'descriptions' => $descriptions
        ));

    }
}
