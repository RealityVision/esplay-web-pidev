<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{
    /**
     * @Route("/new", name="app_commentaire")
     */
    public function index(): Response
    {
        return $this->render('commentaire/indexOne.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
}
