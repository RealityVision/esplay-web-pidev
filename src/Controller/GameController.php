<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/game")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="app_game_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $games = $entityManager

            ->getRepository(Game::class)
            ->findAll();

        return $this->render('admin/games.html.twig',
            ['games' => $games]
        );
    }
   /**
     * @Route("/{idGame}/play", name="app_game_play", methods={"GET", "POST"})
     */
    public function play(Request $request, Game $game, EntityManagerInterface $entityManager, $idGame): Response
    {

        if ($idGame == 1) {
            return $this->render('game/game1.html.twig', [
                'game' => $game,
            ]);
        }
        elseif($idGame == 2) {
            return $this->render('game/game2.html.twig', [
                'game' => $game,
            ]);}
        elseif($idGame == 3) {
            return $this->render('game/game3.html.twig', [
                'game' => $game,
            ]);}
        elseif($idGame == 4) {
            return $this->render('game/game4.html.twig', [
                'game' => $game,
            ]);}
        elseif($idGame == 5) {
            return $this->render('game/game5.html.twig', [
                'game' => $game,
            ]);}
        elseif($idGame == 6) {
            return $this->render('game/game6.html.twig', [
                'game' => $game,
            ]);}

        return $this->render('game/gameinJava.html.twig', [
            'game' => $game,
        ]);
    }


    /**
     * @Route("/game1", name="app_game_1", methods={"GET"})
     */
    public function game1(): Response
    {
        return $this->render('game/game1.html.twig');
    }

    /**
     * @Route("/game2", name="app_game_2", methods={"GET"})
     */
    public function game2(): Response
    {
        return $this->render('game/game2.html.twig');
    }

    /**
     * @Route("/game3", name="app_game_3", methods={"GET"})
     */
    public function game3(): Response
    {
        return $this->render('game/game3.html.twig');
    }
    /**
     * @Route("/game4", name="app_game_4", methods={"GET"})
     */
    public function game4(): Response
    {
        return $this->render('game/game4.html.twig');
    }

    /**
     * @Route("/new", name="app_game_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $time=new \DateTime();
            $game->setDateG($time);
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/new.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idGame}", name="app_game_show", methods={"GET"})
     */
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    /**
     * @Route("/{idGame}/edit", name="app_game_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idGame}", name="app_game_delete", methods={"POST"})
     */
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getIdGame(), $request->request->get('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }
}
