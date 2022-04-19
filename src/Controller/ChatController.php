<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use App\Form\ChatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chat")
 */
class ChatController extends AbstractController
{
    /**
     * @Route("/", name="app_chat_index", methods={"GET", "POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chat = new Chat();
        $user = new User();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);
        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();

        $user = $entityManager
            ->getRepository(User::class)
            ->find(32);


        if ($form->isSubmitted() && $form->isValid()) {
            $time = new \DateTime();
            $chat->setDateMessage($time);
            $chat->setIdUser($user);
            $chat->setUsername("khaled");
            $entityManager->persist($chat);
            $entityManager->flush();

            $chats = $entityManager
                ->getRepository(Chat::class)
                ->findAll();
        }


        return $this->render('front/chat.html.twig', [
            'chats' => $chats,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="app_chat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chat = new Chat();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $time = new \DateTime();
            $chat->setDateMessage($time);
            $entityManager->persist($chat);
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chat/new.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idMessage}", name="app_chat_show", methods={"GET"})
     */
    public function show(Chat $chat): Response
    {
        return $this->render('chat/show.html.twig', [
            'chat' => $chat,
        ]);
    }

    /**
     * @Route("/{idMessage}/edit", name="app_chat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chat/edit.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idMessage}", name="app_chat_delete", methods={"POST"})
     */
    public function delete(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chat->getIdMessage(), $request->request->get('_token'))) {
            $entityManager->remove($chat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
    }
}
