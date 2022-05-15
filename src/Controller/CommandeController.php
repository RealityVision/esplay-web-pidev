<?php

namespace App\Controller;

use App\Entity\Commandeprod;
use App\Form\CommandeprodType;
use App\Repository\CommandeRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/admin/", name="app_commande_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandeprods = $entityManager
            ->getRepository(Commandeprod::class)
            ->findAll();

        return $this->render('commande/index.html.twig', [
            'commandeprods' => $commandeprods,
        ]);
    }
    //////
///
///
///
    /**
     * @Route("/ImprimerCommande", name="ImprimerCommande")
     */
    public function ImprimerCommande()
    {
        $repository = $this->getDoctrine()->getRepository(Commandeprod::class);
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commande = $repository->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/Commande.html.twig',
            ['commande' => $commande]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Commande_finale.pdf", [
            "Attachment" => true
        ]);


    }
    ///trie
    ///
    ///
    /**
     * @Route("/triid", name="triid")
     */

    public function Triid(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT c FROM App\Entity\Commandeprod c 
            ORDER BY c.quantite'
        );


        $rep = $query->getResult();

        return $this->render('commande/index.html.twig',
            array('commande' => $rep));

    }
    /**
     * @Route("/front/new", name="app_commande_new", methods={"GET", "POST"})
     */
    public function new(CommandeRepository $commandeRepository , Request $request, EntityManagerInterface $entityManager, \Swift_Mailer $mailer): Response
    {
        $commandeprod = new Commandeprod();
        $form = $this->createForm(CommandeprodType::class, $commandeprod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commandeprod);
            $entityManager->flush();
            $commandeRepository->add($commandeprod);
            $message = (new \Swift_Message('Reservation'))
                ->setSubject('Order')
                ->setFrom('realityvison.pidev@gmail.com')
                ->setTo('slim.derouiche@esprit.tn')
                ->setBody(
                    'new order'
                )
            ;
            $mailer->send($message);
        }

            $this->addFlash('message', 'le message a bien ete envoye');


        return $this->render('commande/new.html.twig', [
            'commandeprod' => $commandeprod,
            'form' => $form->createView(),
        ]);
    }
    ///

    ///

    /**
     * @Route("/{idCommande}", name="app_commande_show", methods={"GET"})
     */
    public function show(Commandeprod $commandeprod): Response
    {

        return $this->render('commande/show.html.twig', [
            'commandeprod' => $commandeprod,
        ]);
    }

    /**
     * @Route("/{idCommande}/edit", name="app_commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commandeprod $commandeprod, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeprodType::class, $commandeprod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commandeprod' => $commandeprod,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCommande}", name="app_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commandeprod $commandeprod, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commandeprod->getIdCommande(), $request->request->get('_token'))) {
            $entityManager->remove($commandeprod);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/AfficheCommande", name="AfficheCommande")
     */
    public function AfficheCommande()
    {
        $repository = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $repository->findAll();

        return $this->render('commande/AfficheC.html.twig',
            ['commande' => $commande]);


    }

}