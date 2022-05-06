<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Chat;
use App\Entity\Report;
use App\Form\ReportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{
    /**
     * @Route("/", name="reportcheckbox", methods={"GET","POST"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {

        $rep = "";
        $inputValue = $request->request->all();
        foreach ($inputValue as $v) {
            if (strlen($v) > 3) {
                $rep = $rep . " - " . $v;
            } else $id = (int) $v;
        }

        var_dump($inputValue);
        var_dump($id);

        $message = $entityManager
            ->getRepository(Chat::class)
            ->find($id);

        $user = $entityManager
            ->getRepository(User::class)
            ->find(32);

        $report = new Report();
        $report->setIdMessage($message);
        $report->setIdSender($user);
        $report->setReason($rep);

        $entityManager->persist($report);
        $entityManager->flush();


        $reports = $entityManager
            ->getRepository(Report::class)
            ->findAll();

        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();
        return
            $this->redirectToRoute('app_chat_index', ['bool' => true]);
    }
/**
     * @Route("/admin", name="reportad", methods={"GET","POST"})
     */
    public function indexAdmin(EntityManagerInterface $entityManager, Request $request): Response
    {
        $reports = $entityManager
            ->getRepository(Report::class)
            ->findAll();

        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();
        return
            $this->render('admin/reports.html.twig', ['reports' => $reports]);
    }

 /**
     * @Route("/{id}", name="app_report_email", methods={"GET"})
     */
    public function mail(Report $report,MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $chat = $entityManager
            ->getRepository(Chat::class)
            ->find($report->getIdMessage());
        $email = (new Email())
        ->from('realityvison.pidev@gmail.com')
        ->to('khaled.mihoub@esprit.tn')
        
        ->priority(Email::PRIORITY_HIGH)
        ->subject('Someone report your message')
        ->text('Someone report your message :  --'.$chat->getMessage().'--  in esplay community and may be deleted by admins.The reason was : '.$report->getReason());

    $mailer->send($email);

        return $this->redirectToRoute('reportad', []);
    }

     /**
     * @Route("/{id}", name="delete_report_email", methods={"GET"})
     */
    public function deletemessage(Report $report, EntityManagerInterface $entityManager): Response
    {
        $chat = $entityManager
            ->getRepository(Chat::class)
            ->find($report->getIdMessage());
        $entityManager->remove($report);
            $entityManager->flush();
            $entityManager->remove($chat);
            $entityManager->flush();
            

        return $this->redirectToRoute('reportad', []);
    }





    /**
     * @Route("/new", name="app_report_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($report);
            $entityManager->flush();

            return $this->redirectToRoute('app_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('report/new.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_report_show", methods={"GET"})
     */
    public function show(Report $report): Response
    {
        return $this->render('report/show.html.twig', [
            'report' => $report,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_report_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Report $report, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_report_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('report/edit.html.twig', [
            'report' => $report,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_report_delete", methods={"POST"})
     */
    public function delete(Request $request, Report $report, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $report->getId(), $request->request->get('_token'))) {
            $entityManager->remove($report);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_report_index', [], Response::HTTP_SEE_OTHER);
    }
}
