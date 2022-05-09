<?php

namespace App\Controller;

use App\Entity\Produit2;
use App\Form\Produit2Type;
use App\Form\ProduitSearchType;
use App\Entity\ProduitSearch;
use App\Entity\CatPSearch;
use App\Form\CatPSearchType;
use App\Repository\Produit2Repository;
use Symfony\Component\Form\FormView;
use OC\PlatformBundle\Form\ArticlesType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/produit2")
 */
class Produit2Controller extends Controller
{
    /**
     * @Route("/front/", name="app_produit2_index")
     */
    public function index(Request $request,EntityManagerInterface $entityManager ): Response
    {
        $produitSearch= new ProduitSearch();
        $form = $this->createForm(ProduitSearchType::class, $produitSearch);
        $form->handleRequest($request);
        $produit2ss= [];
        if($form->isSubmitted() && $form->isValid()) {
            $nom = $produitSearch->getNom();
            if ($nom!="")
                $produit2ss= $this->getDoctrine()->getRepository(Produit2::class)->findBy(['nom' => $nom] );
            else
                $produit2ss= $this->getDoctrine()->getRepository(Produit2::class)->findAll();
        }
        $allproduit2s = $entityManager
            ->getRepository(Produit2::class)
            ->findAll();

        $produit2s = $this->get('knp_paginator')->paginate(
            $allproduit2s,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('admin/store.html.twig', [
            'produit2s' => $produit2s,
            'produit2ss' => $produit2ss,
            'form' => $form-> createView()
        ]);
    }




    /**
     * @Route("/admin/new", name="app_produit2_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit2 = new Produit2();
        $form = $this->createForm(Produit2Type::class, $produit2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit2);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'New Item Added !'
            );

            return $this->redirectToRoute('app_produit2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit2/new.html.twig', [
            'produit2' => $produit2,
            'form' => $form->createView(),
        ]);
    }
//

//
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
    public function edit(Request $request, Produit2 $produit2, EntityManagerInterface $entityManager, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(Produit2Type::class, $produit2);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $message = (new \Swift_Message('New'))
                ->setFrom('realityvison.pidev@gmail.com')
                ->setTo('slim.derouuiche@esprit.tn')
                ->setSubject('Produit  modifié')
                ->setBody(
                    $this->renderView(
                        'cart/mail.html.twig'),
                    'text/html'
                );

            $mailer->send($message);
            $this->addFlash('message', 'le message a bien ete envoye');
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
        $this->addFlash('success', 'Item deleted');
        return $this->redirectToRoute('app_produit2_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *@Route("/search",name="produit_list")
     */
    public function search(Request $request)
    {
        $produitSearch = new ProduitSearch();
        $form = $this->createForm(ProduitSearchType::class,$produitSearch);
        $form->handleRequest($request);
        $produit2s= [];

        if($form->isSubmitted() && $form->isValid()) {
            $nom = $produitSearch->getNom();
            if ($nom!="")
                $produit2s= $this->getDoctrine()->getRepository(Produit2::class)->findBy(['nom' => $nom] );
            else
                $produit2s= $this->getDoctrine()->getRepository(Produit2::class)->findAll();
        }

        return  $this->render('front/store.html.twig',[ 'form' =>$form->createView(), 'produit2s' => $produit2s]);
    }
///trie
///
///
    /**
     * @Route("/Produit2/tri", name="/produit/tri")
     */
    public function Tri(Request $request,PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery(
            'SELECT produit2 FROM App\Entity\Produit2 produit2
            ORDER BY produit2.prix DESC'
        );

        $produit2s = $query->getResult();

        $produit2s = $paginator->paginate(
            $produit2s,
            $request->query->getInt('page',1),
            4
        );

        return $this->render('admin/store.html.twig',
            array('produit2s' => $produit2s));

    }

    /**
     * @Route("/cat/", name="produit_par_cat")
     * Method({"GET", "POST"})
     */
    public function ProduitsParCategorie(Request $request) {
        $catPSearch = new CatPSearch();
        $form = $this->createForm(CatPSearchType::class,$catPSearch);
        $form->handleRequest($request);

        $produit2s= [];

        if($form->isSubmitted() && $form->isValid()) {
            $categoryP = $catPSearch->getCategoryP();

            if ($categoryP!="")
            {

                $produit2s= $categoryP->getProduit2s();
                // ou bien
                //$articles= $this->getDoctrine()->getRepository(Article::class)->findBy(['category' => $category] );
            }
            else
                $produit2s= $this->getDoctrine()->getRepository(Produit2::class)->findAll();
        }

        return $this->render('category_p/ProduitsParCategorie.html.twig',[
            'form' => $form->createView(),
            'produit2s' => $produit2s
        ]);
    }
    /**
     * @Route("/produit2/stat", name="/produit2/stat")
     */
    public function indexAction(){
        $repository = $this->getDoctrine()->getRepository(Produit2::class);
        $Produit2s = $repository->findAll();
        $em = $this->getDoctrine()->getManager();

        $pr1=52;
        $pr2=10;


        foreach ($Produit2s as $Produit2s)
        {
            if (  $Produit2s->getPrix()=="250")  :

                $pr1+=1;
            else:

                $pr2+=1;


            endif;

        }


        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['prix', 'nombres'],
                ['150', $pr1],
                ['51', $pr2],
            ]
        );
        $pieChart->getOptions()->setTitle('Top Products by category');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('produit2/stat.html.twig', array('piechart' => $pieChart));
    }




}
