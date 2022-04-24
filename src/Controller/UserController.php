<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {




        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

        /**
     * @Route("/front", name="app_index", methods={"GET"})
     */
    public function indexfront(EntityManagerInterface $entityManager): Response
    {

        return $this->render('front/index.html.twig', [
        ]);
    }
    /**
     * @Route("/test", name="api", methods={"GET"})
     */
    public function indextest(EntityManagerInterface $entityManager): Response
    {

        //getSalt
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8080/getsalt');
        $salt = $response->getContent();
        var_dump($salt);

        // Create a POST request psw crypté 
        $response2 = $client->request('POST', 'http://localhost:8080/password', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => 
                [
                'salt' => $salt,
                'password' => 'azerty'
        ],
        ]);
            
        $crypted = $response2->getContent();
        var_dump( $crypted);
        // Create a POST request verifiépsw 
        $response3 = $client->request('POST', 'http://localhost:8080/verify', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => 
                [
                'salt' => 'nrykKc0qEVEMFrrAisrf'  ,
                'securedPassword' => 'uv7WAARXAJkjm1vWXdcgdHGE',
                'providedPassword' => 'azerty',
        ],
        ]);
            
        $verified = $response3->getContent();
        var_dump( $verified);
        
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $user->setSalt("aaaaaaaaaaaa");
             $user->setRole("Player");

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{idUser}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setPicture(null);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
