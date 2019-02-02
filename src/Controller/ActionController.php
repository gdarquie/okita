<?php

namespace App\Controller;

use App\Entity\Action;
use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActionController extends AbstractController
{
    /**
     * @Route("/personnages/{personnageId}/actions", name="action")
     */
    public function getActions(Request $request, $personnageId)
    {
        ($day = $request->get('jour')) ? $time = $day*24*60*60 : $time = 0;

        $character = $this->getDoctrine()->getRepository(Character::class)->findOneById($personnageId);
        $actions = $this->getDoctrine()->getRepository(Action::class)->findByPersonnage($personnageId, $time);

        return $this->render('actions.html.twig', array(
            'character' => $character,
            'actions' => $actions
        ));

    }
}
