<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Commentaire;
use App\Entity\Recomendedg;
use App\Entity\User;
use App\Form\CommentaireType;
use App\Form\RecomendedgType;
use App\Repository\RecomendedgRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/recomendedg")
 */
class RecomendedgController extends AbstractController
{
    /**
     * @Route("/", name="app_recomendedg_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $rec = $entityManager
            ->getRepository(Recomendedg::class)
            ->findAll();

        return $this->render('recomendedg/indexAdmin.html.twig', [
            'recomendedgs' => $rec,
        ]);
    }
    /**
     * @Route("/Admin/{id}", name="app_recomendedg_show_Admin", methods={"GET","POST"})
     */
    public function showAdmin($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $rec = $this->getDoctrine()->getRepository(Recomendedg::class)->find($id);

        $comment = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $comment);
        $comments = $this->getDoctrine()->getRepository(Commentaire::class)->findBy([
            'idrec' => $rec
        ]);


        return $this->render('recomendedg/indexCommentaireAdmin.html.twig', [
            'recomendedg' => $rec,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/new", name="app_recomendedg_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rec = new Recomendedg();
        $form = $this->createForm(RecomendedgType::class, $rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rec);
            $entityManager->flush();

            return $this->redirectToRoute('app_recomendedg_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recomendedg/new.html.twig', [
            'recomendedg' => $rec,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_recomendedg_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Recomendedg $rec, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecomendedgType::class, $rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recomendedg_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recomendedg/edit.html.twig', [
            'recomendedg' => $rec,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_recomendedg_deletee", methods={"GET"})
     */
    public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response
    {

        $rec = $this->getDoctrine()->getRepository(Recomendedg::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($rec);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('app_recomendedg_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/CommentAdmin/{id}", name="app_recomendedg_delete_admin",  methods={"GET"})
     */
    public function deleteComAdmin(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $com = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($com);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        $this->addFlash(
            'info-delete',
            'Deleted Successfully'
        );
        return $this->redirectToRoute('app_recomendedg_show_Admin', ['id' => $com->getIdrec()->getId()], Response::HTTP_SEE_OTHER);
    }


    //***********************FRONT

    /**
     * @Route("/Client/show", name="app_recomendedg_index_Client", methods={"GET"})
     */
    public function indexxx(EntityManagerInterface $entityManager): Response
    {
        $rec = $entityManager
            ->getRepository(Recomendedg::class)
            ->findAll();

        return $this->render('recomendedg/index.html.twig', [
            'recomendedgs' => $rec,
        ]);
    }



    /**
     * @Route("/Client/{id}", name="app_recomendedg_show", methods={"GET","POST"})
     */
    public function show(Recomendedg $rec, $id, Request $request, EntityManagerInterface $entityManager): Response
    {

        $comment = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);
        $comments = $this->getDoctrine()->getRepository(Commentaire::class)->findBy([
            'idrec' => $rec
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository(User::class)->find(1);
            $comment->setUser($user);
            $comment->setIdrec($rec);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Added Successfully'
            );

            return $this->redirectToRoute('app_recomendedg_show', array('id' => $id));
        }

        return $this->render('recomendedg/indexOne.html.twig', [
            'recomendedg' => $rec,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/editCommentaire", name="app_commentaire_edit", methods={"GET", "POST"})
     */
    public function editCommentaire(Request $request, Commentaire $com, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireType::class, $com);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recomendedg_show', ['id' => $com->getIdrec()->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->addFlash(
            'php',
            'Updated Successfully'
        );

        return $this->render('recomendedg/editComment.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/Comment/Delete/{id}", name="app_recomendedg_delete_index", methods={"GET"})
     */
    public function removeComment($id)
    {

        $manager = $this->getDoctrine()->getManager();
        $comment = $manager->getRepository(Commentaire::class)->find($id);

        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
            'info-delete',
            'Deleted Successfully'
        );

        return $this->redirectToRoute('app_recomendedg_show', array('id' => $comment->getIdrec()->getId()));
    }



    /**
     * @Route("/r/search_recc", name="search_recc", methods={"GET"})
     */
    public function search_rec(Request $request, NormalizerInterface $Normalizer, RecomendedgRepository $recomendedgRepository): Response
    {
        $requestString = $request->get('searchValue');
        $requestString3 = $request->get('orderid');

        $rec = $recomendedgRepository->findRec($requestString, $requestString3);
        $jsoncontentc = $Normalizer->normalize($rec, 'json', ['groups' => 'posts:read']);
        $jsonc = json_encode($jsoncontentc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }
    }

    //*****MOBILE

    /**
     * @Route("/mobile/aff", name="affmobrec")
     */
    public function affmobrec(NormalizerInterface $normalizer)
    {
        $med = $this->getDoctrine()->getRepository(Recomendedg::class)->findAll();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($med) {
            return $med->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers, $encoders);
        $formatted = $serializer->normalize($med);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/mobile/new", name="addmobrec")
     */
    public function addmobrec(Request $request, NormalizerInterface $normalizer, EntityManagerInterface $entityManager)
    {
        $recg = new Recomendedg();
        $recg->setNom($request->get('nom'));
        $recg->setPrix($request->get('prix'));
        $cat = $this->getDoctrine()->getManager()->getRepository(Category::class)->findOneBy(
            ['categoryName' => $request->get('category')]
        );

        $recg->setCategory($cat);
        $recg->setPlatform($request->get('platforme'));
        $recg->setUrl($request->get('url'));

        $entityManager->persist($recg);
        $entityManager->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($recg) {
            return $recg->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers, $encoders);
        $formatted = $serializer->normalize($recg);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/mobile/editrecg", name="editmobrecg")
     */
    public function editmobrecg(Request $request, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();

        $recg = $em->getRepository(Recomendedg::class)->find($request->get('id'));

        $recg->setNom($request->get('nom'));
        $recg->setPrix($request->get('prix'));
        $cat = $this->getDoctrine()->getManager()->getRepository(Category::class)->findOneBy(
            ['categoryName' => $request->get('category')]
        );

        $recg->setCategory($cat);
        $recg->setPlatform($request->get('platforme'));
        $recg->setUrl($request->get('url'));

        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($recg) {
            return $recg->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers, $encoders);
        $formatted = $serializer->normalize($recg);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/mobile/del", name="delmobrecg")
     */
    public function delmobproduit(Request $request, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $rec = $this->getDoctrine()->getRepository(Recomendedg::class)
            ->find($request->get('id'));
        $em->remove($rec);
        $em->flush();
        $jsonContent = $normalizer->normalize($rec, 'json', ['blog' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
}
