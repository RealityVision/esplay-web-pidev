<?php

namespace App\Controller;

use App\Entity\Produit2;
use App\Form\Produit2Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit2")
 */
class Produit2Controller extends AbstractController
{
    /**
     * @Route("/", name="app_produit2_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produit2s = $entityManager
            ->getRepository(Produit2::class)
            ->findAll();

        return $this->render('admin/store.html.twig', [
            'produit2s' => $produit2s,
        ]);
    }

    /**
     * @Route("/new", name="app_produit2_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit2 = new Produit2();
        $form = $this->createForm(Produit2Type::class, $produit2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit2);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit2/new.html.twig', [
            'produit2' => $produit2,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idp2}", name="app_produit2_show", methods={"GET"})
     */
    public function show(Produit2 $produit2): Response
    {
        return $this->render('produit2/show.html.twig', [
            'produit2' => $produit2,
        ]);
    }

    /**
     * @Route("/{idp2}/edit", name="app_produit2_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit2 $produit2, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Produit2Type::class, $produit2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit2/edit.html.twig', [
            'produit2' => $produit2,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idp2}", name="app_produit2_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit2 $produit2, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit2->getIdp2(), $request->request->get('_token'))) {
            $entityManager->remove($produit2);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit2_index', [], Response::HTTP_SEE_OTHER);
    }
}
