<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    /**
     * @Route("/personnage", name="character")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CharacterController.php',
        ]);
    }

    /**
     * @Route("/personnages/{id}", name="get_character")
     */
    public function getCharacter($id)
    {
        $character = $this->getDoctrine()->getRepository(Character::class)->findOneById($id);
        return $this->render('character.html.twig', array(
            'character' => $character
        ));
    }

    /**
     * @Route("/character/{characterId}/day/{day}", name="get_character_actions_by_day")
     */
    public function getCharacterActionsByDay($characterId, $day)
    {
        $character = $this->getDoctrine()->getRepository(Character::class)->findOneById($characterId);
        $actions = "";
        return $this->render('actions.html.twig', array(
            'character' => $character,
            'actions' => $actions
        ));
    }
}
