<?php

namespace App\Controller;

use App\Entity\CategoryP;
use App\Form\CategoryPType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categoryP")
 */
class CategoryPController extends AbstractController
{
    /**
     * @Route("/", name="app_category_p_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categoryPs = $entityManager
            ->getRepository(CategoryP::class)
            ->findAll();

        return $this->render('category_p/index.html.twig', [
            'category_ps' => $categoryPs,
        ]);
    }

    /**
     * @Route("/new", name="app_category_p_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryP = new CategoryP();
        $form = $this->createForm(CategoryPType::class, $categoryP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categoryP);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_p_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_p/new.html.twig', [
            'category_p' => $categoryP,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_p_show", methods={"GET"})
     */
    public function show(CategoryP $categoryP): Response
    {
        return $this->render('category_p/show.html.twig', [
            'category_p' => $categoryP,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_category_p_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategoryP $categoryP, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryPType::class, $categoryP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_category_p_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_p/edit.html.twig', [
            'category_p' => $categoryP,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_category_p_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoryP $categoryP, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryP->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoryP);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_p_index', [], Response::HTTP_SEE_OTHER);
    }
}
