<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    /**
     * @Route("/stat", name="app_stat")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();
        $count = count($users);
        $count_c = count($chats);



        $query = $entityManager
            ->createQuery('SELECT MONTH(c.dateMessage) AS 
        mois,COUNT(c.idMessage) as nombre FROM App\Entity\Chat c  
        WHERE YEAR(c.dateMessage) = 2022 GROUP BY mois');

        $result = $query->getResult();

        $axeX = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
        //echo $axeX[3];
        $axeY = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($result as $element) {

            $axeY[intval($element["mois"]) - 1] = $element["nombre"];
        }





        return $this->render('admin/index.html.twig', [
            'controller_name' => 'StatController',
            'count' => $count,
            'count_c' => $count_c,
            'axex' => json_encode($axeX),
            'axey' => json_encode($axeY),
        ]);
    }
    /**
     * @Route("/stat2", name="app_stat2")
     */
    public function index6(EntityManagerInterface $entityManager): Response
    {

        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();
        $count = count($users);
        $count_c = count($chats);



        $query = $entityManager
            ->createQuery('SELECT MONTH(c.dateMessage) AS 
        mois,COUNT(c.idMessage) as nombre FROM App\Entity\Chat c  
        WHERE YEAR(c.dateMessage) = 2022 GROUP BY mois');

        $result = $query->getResult();

        $axeX = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
        //echo $axeX[3];
        $axeY = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($result as $element) {

            $axeY[intval($element["mois"]) - 1] = $element["nombre"];
        }





        return $this->render('admin/index.html.twig', [
            'controller_name' => 'StatController',
            'count' => $count,
            'count_c' => $count_c,
            'axex' => json_encode($axeX),
            'axey' => json_encode($axeY),
        ]);
    }
    /**
     * @Route("/stat3", name="app_stat3")
     */
    public function index7(EntityManagerInterface $entityManager): Response
    {

        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();
        $count = count($users);
        $count_c = count($chats);



        $query = $entityManager
            ->createQuery('SELECT MONTH(c.dateMessage) AS 
        mois,COUNT(c.idMessage) as nombre FROM App\Entity\Chat c  
        WHERE YEAR(c.dateMessage) = 2022 GROUP BY mois');

        $result = $query->getResult();

        $axeX = ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"];
        //echo $axeX[3];
        $axeY = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($result as $element) {

            $axeY[intval($element["mois"]) - 1] = $element["nombre"];
        }





        return $this->render('admin/index.html.twig', [
            'controller_name' => 'StatController',
            'count' => $count,
            'count_c' => $count_c,
            'axex' => json_encode($axeX),
            'axey' => json_encode($axeY),
        ]);
    }
}
