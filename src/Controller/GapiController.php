<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class GapiController extends  AbstractController
{


    /**
     * @Route("/api/addprod", name="addprod")
     */
    public function addGame(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $nom = $request->get("title");
        $prix = $request->get("size");
        $description = $request->get("description");
        $categorie = $request->get("category");
        // $dateCreation = new \DateTime(urldecode($request->get("dateCreation")));


        // $category = $em->getRepository(Category::class)->find($category_id);
        $game = new Game();
        $game->setTitle($nom);
        $game->setSize($prix);
        $game->setDescription($description);
        $game->setCategory($categorie);
        //   $product->setDateCreation($dateCreation);

        /* $product->setImage($request->get("image"));
         if($request->files->get("image") !=null) {
             $file = $request->files->get("image");
             $fileName = $file->getClientOriginalName();

             $filename = md5(uniqid()) . '.' .$file->guessExtension();//crypté image

             $file->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);


             $product->setImage($filename);



         }*/
        $em->persist($game);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getIdGame();
        });

        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($game);

        return new JsonResponse($formatted);


    }

    /**
     * @Route("/api/modifprod", name="modifprod")
     */
    public function updateProduct(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $idg = $request->get("idGame");
        $game = $em->getRepository(Game::class)->find($idg);

        $title = $request->get("title");
        $size = $request->get("size");
        $description = $request->get("description");
        $category = $request->get("category");
        // $dateCreation = new \DateTime(urldecode($request->get("dateCreation")));


        // $category = $em->getRepository(Category::class)->find($category_id);


        $game->setNom($title);
        $game->setPrix($size);
        $game->setDescription($description);
        $game->setCategory($category);
        //  $prod->setDateCreation($dateCreation);

        /* $prod->setImage($request->get("image"));
         if($request->files->get("image") !=null) {
             $file = $request->files->get("image");
             $fileName = $file->getClientOriginalName();

             $filename = md5(uniqid()) . '.' .$file->guessExtension();//crypté image

             $file->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);


             $prod->setImage($filename);



         }*/
        $em->persist($game);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getIdGame();
        });

        //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

        return new JsonResponse("Product update with success");


    }

    /**
     * @Route("/Gapi/deleteGame/{idGame}", name="deleteGame")
     */
    public function deleteProd($idGame)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository(Game::class)->find($idGame);
        $em->remove($game);
        $em->flush();
        return new JsonResponse("Game deleted .");

    }

    /**
     * @Route("/Gapi/affichGame", name="affichGame")
     */
    public function afficheGame()
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository(Game::class)->findAll();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        //JOIN ERROR
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            if (method_exists($object, 'getIdGame')) {
                return $object->getIdGame();
            }
        });


        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($game);



        return new JsonResponse($formatted);


    }
}



