<?php

namespace App\Controller;

use App\Entity\CategoryP;
use App\Entity\Produit2;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class ApiController extends  AbstractController
{


    /**
     * @Route("/api/addprod", name="addprod")
     */
    public function addProduct(Request $request) {

        $em = $this->getDoctrine()->getManager();

        $nom = $request->get("nom");
        $prix = $request->get("prix");
        $description = $request->get("description");
        $stockProduit = $request->get("stockProduit");
       // $dateCreation = new \DateTime(urldecode($request->get("dateCreation")));


        // $category = $em->getRepository(Category::class)->find($category_id);
        $produit2 = new Produit2();
        $produit2->setNom($nom);
        $produit2->setPrix($prix);
        $produit2->setDescription($description);
        $produit2->setStockProduit($stockProduit);
     //   $product->setDateCreation($dateCreation);

       /* $product->setImage($request->get("image"));
        if($request->files->get("image") !=null) {
            $file = $request->files->get("image");
            $fileName = $file->getClientOriginalName();

            $filename = md5(uniqid()) . '.' .$file->guessExtension();//crypté image

            $file->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);


            $product->setImage($filename);



        }*/
        $em->persist($produit2);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getIdp2();
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($produit2);

        return new JsonResponse($formatted);





    }

    /**
     * @Route("/api/modifprod", name="modifprod")
     */
    public function updateProduct(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $idp2 = $request->get("idp2");
        $prod= $em->getRepository(Produit2::class)->find($idp2);

        $nom = $request->get("nom");
        $prix = $request->get("prix");
        $description = $request->get("description");
        $stockProduit = $request->get("stockProduit");
       // $dateCreation = new \DateTime(urldecode($request->get("dateCreation")));
        // $category = $em->getRepository(Category::class)->find($category_id);
        $prod->setNom($nom);
        $prod->setPrix($prix);
        $prod->setDescription($description);
        $prod->set($stockProduit);
      //  $prod->setDateCreation($dateCreation);
       /* $prod->setImage($request->get("image"));
        if($request->files->get("image") !=null) {
            $file = $request->files->get("image");
            $fileName = $file->getClientOriginalName();
            $filename = md5(uniqid()) . '.' .$file->guessExtension();//crypté image
            $file->move($this->getParameter('kernel.project_dir').'/public/uploads/produit_image',$filename);
            $prod->setImage($filename);
        }*/
        $em->persist($prod);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        //  $serializer = new Serializer([$normalizer],[$encoder]);
        //$formatted = $serializer->normalize($prod);

        return new JsonResponse("Product update with success");
    }

    /**
     * @Route("/api/deleteprod/{idp2}", name="deleteprod")
     */
    public function deleteProd($idp2) {
        $em= $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Produit2::class)->find($idp2);
        $em->remove($prod);
        $em->flush();
        return new JsonResponse("Product deleted .");

    }

    /**
     * @Route("/api/affichProd", name="affichProd")
     */
    public function afficheProd( \Swift_Mailer $mailer) {
        $em= $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Produit2::class)->findAll();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        //JOIN ERROR
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) {
            if(method_exists($object, 'getIdp2')){
                return $object->getIdp2();
            }
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($prod);
        $message = (new \Swift_Message('STORE-MOBILE'))
            ->setSubject('MOBILE NOTIFICATION')
            ->setFrom('realityvison.pidev@gmail.com')
            ->setTo('slim.derouiche@esprit.tn')
            ->setBody(
                'STORE '
            )
        ;
        $mailer->send($message);
        return new JsonResponse($formatted);
    }
}
