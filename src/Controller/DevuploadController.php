<?php

namespace App\Controller;

use App\Entity\CategoryP;
use App\Entity\Devupload;
use App\Form\CategoryPType;
use App\Form\DevuploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/devupload")
 */
class DevuploadController extends AbstractController
{
    /**
     * @Route("/", name="app_devupload_index", methods={"GET"})
     */
    public function index(): Response
    {

        return $this->render('devupload/index.html.twig', [
            'controller_name' => 'DevuploadController',

        ]);
    }
    /**
     * @Route("/new", name="app_devupload_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $devupload = new Devupload();
        $form = $this->createForm(DevuploadType::class, $devupload);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $time = new \DateTime();
            $devupload->setDateUp($time);
            $entityManager->persist($devupload);
            $entityManager->flush();

            return $this->redirectToRoute('app_devupload_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('devupload/new.html.twig', [
            'devupload' => $devupload,
            'form' => $form->createView(),
        ]);
    }
}
