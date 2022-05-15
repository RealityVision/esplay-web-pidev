<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/signuppmobile", name="app_chat_index4e")
     */
    public function index4(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager): Response
    {

        //getSalt
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8080/getsalt');
        $salt = $response->getContent();



        // Create a POST request psw crypté 
        $response2 = $client->request('POST', 'http://localhost:8080/password', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' =>
            [
                'salt' => $salt,
                'password' => $request->get("Password")
            ],
        ]);

        $crypted = $response2->getContent();

        $user = new User();
        $time = new \DateTime();
        $user->setCreated($time);
        $user->setUsername($request->get("Login"));
        $user->setFirstName($request->get("Name"));
        $user->setLastName($request->get("LastName"));
        $user->setEmail($request->get("Email"));
        $user->setSalt($salt);
        $user->setPassword($crypted);
        $user->setRole("player");


        $entityManager->persist($user);
        $entityManager->flush();
        return  new Response("salut");
    }

    /**
     * @Route("/login", name="login", methods={"GET","POST"})
     */
    public function login(EntityManagerInterface $entityManager, Request $request): Response
    {
        $msgerreurpsw = false;
        $msgerreuruser = false;
        $bool = false;
        $formData = $request->request->all();
        $username = $formData["username"];
        $password = $formData["password"];
        $user = $entityManager
            ->getRepository(User::class)
            ->findByUsername($username);
        if (!empty($user)) {
            // Create a POST request verifiépsw 

            $client = HttpClient::create();
            $response3 = $client->request('POST', 'http://localhost:8080/verify', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' =>
                [
                    'salt' => $user[0]->getSalt(),
                    'securedPassword' =>  $user[0]->getPassword(),
                    'providedPassword' => $password,
                ],
            ]);
            $verified = $response3->getContent();
        }

        if (empty($user)) {
            $msgerreuruser = true;
            $bool = false;
            $user = null;
        } elseif (strcmp($verified, "false") == 0) {
            $msgerreurpsw = true;
            $bool = false;
            $user = null;
        } else {
            $msgerreurpsw = false;
            $msgerreuruser = false;
            $bool = true;
            if ($user[0]->getRole() == "admin") {

                $iduser = $user[0]->getIdUser();
                $session = new Session();
                $session->set('idsession', $iduser);

                //$session->get('idsession');


                return $this->redirectToRoute('app_user_index');
            } else {
                $iduser = $user[0]->getIdUser();
                $session = new Session();
                $session->set('idsession', $iduser);

                return
                    $this->redirectToRoute('app_game_front');
                $user = null;
            }
        }
        $games = $entityManager

            ->getRepository(Game::class)
            ->findAll();
        return $this->render(
            'front/game.html.twig',
            [
                'msgerreuruser' => $msgerreuruser,
                'msgerreurpsw' => $msgerreurpsw,
                'games' => $games,
                'user' => $user,
                'msg' => $bool,
            ]
        );
    }
    /**
     * @Route("/loginmobile", name="loginmobile")
     */
    public function loginmobile(EntityManagerInterface $entityManager, Request $request): Response
    {

        $formData = $request->request->all();
        $username = $request->get("login");
        $password = $request->get("password");
        $user = $entityManager
            ->getRepository(User::class)
            ->findByUsername($username);
        if (!empty($user)) {
            // Create a POST request verifiépsw 

            $client = HttpClient::create();
            $response3 = $client->request('POST', 'http://localhost:8080/verify', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' =>
                [
                    'salt' => $user[0]->getSalt(),
                    'securedPassword' =>  $user[0]->getPassword(),
                    'providedPassword' => $password,
                ],
            ]);
            $verified = $response3->getContent();
        }

        if (empty($user)) {
            return new Response('falseempty');
        } elseif (strcmp($verified, "false") == 0) {
            return new Response('falsepzass');
        } else {

            if ($user[0]->getRole() == "admin") {



                //$session->get('idsession');


                return new Response('true');
            } else {


                return new Response('true');
            }
        }
    }

    /**
     * @Route("/signup", name="signup", methods={"GET","POST"})
     */
    public function signup(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): Response
    {
        $formData = $request->request->all();

        $login = $formData["login"];
        $name = $formData["name"];
        $lastname = $formData["last_name"];
        $email = $formData["Email"];
        $password = $formData["password"];


        //getSalt
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8080/getsalt');
        $salt = $response->getContent();



        // Create a POST request psw crypté 
        $response2 = $client->request('POST', 'http://localhost:8080/password', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' =>
            [
                'salt' => $salt,
                'password' => $password
            ],
        ]);

        $crypted = $response2->getContent();

        $user = new User();
        $time = new \DateTime();
        $user->setCreated($time);
        $user->setUsername($login);
        $user->setFirstName($name);
        $user->setLastName($lastname);
        $user->setEmail($email);
        $user->setSalt($salt);
        $user->setPassword($crypted);
        $user->setRole("player");
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $err = (string) $errors;
            $error = array();
            if (strpos($err, "username") !== false) {
                array_push($error, "Usename must be at least 4 characters long");
            }
            if (strpos($err, "firstName") !== false) {
                array_push($error, "Lirst Name field can't be blank ");
            }
            if (strpos($err, "lastName") !== false) {
                array_push($error, "Last Name field can't be blank ");
            }
            if (strpos($err, "email") !== false) {
                array_push($error, "Email not valid ");
            }
            if (strlen($password) < 8) {
                array_push($error, "Password must be at least 8 characters long ");
            }
        }
        if (empty($error)) {
            $entityManager->persist($user);
            $entityManager->flush();
            return
                $this->redirectToRoute('app_game_front');
        }
        $user = null;
        $games = $entityManager

            ->getRepository(Game::class)
            ->findAll();
        return $this->render(
            'front/game.html.twig',
            [
                'error' => $error,
                'games' => $games,
                'user' => $user,

            ]
        );
    }



    /**
     * @Route("/logout", name="logoutroute", methods={"GET","POST"})
     */
    public function logout(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        $session->invalidate();


        return $this->redirectToRoute('app_game_front');
    }



    /**
     * @Route("/front", name="app_index", methods={"GET"})
     */
    public function indexfront(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->get('msg') == true) {
            $session = $request->getSession();


            $user = $entityManager
                ->getRepository(User::class)
                ->find($session->get('idsession'));
        } else $user = null;
        return $this->render('front/index.html.twig', [
            'user' =>  $user,
            'msg' => true,
        ]);
    }


    /**
     * @Route("/pdf", name="app_user_pdf", methods={"GET"})
     */
    public function pdfdownload(EntityManagerInterface $entityManager): Response
    {

        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $pdfOptions = new Options();
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->render('admin/datapdf.html.twig', [
            'users' => $users,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("users.pdf", [
            "Attachment" => true,
        ]);
    }



    /**
     * @Route("/test", name="api", methods={"GET"})
     */
    public function indextest(EntityManagerInterface $entityManager): Response
    {

        //getSalt
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8080/getsalt');
        $salt = $response->getContent();
        var_dump($salt);

        // Create a POST request psw crypté 
        $response2 = $client->request('POST', 'http://localhost:8080/password', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' =>
            [
                'salt' => $salt,
                'password' => 'azerty'
            ],
        ]);

        $crypted = $response2->getContent();
        var_dump($crypted);
        // Create a POST request verifiépsw 
        $response3 = $client->request('POST', 'http://localhost:8080/verify', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' =>
            [
                'salt' => $salt,
                'securedPassword' => $crypted,
                'providedPassword' => 'azerty',
            ],
        ]);

        $verified = $response3->getContent();
        var_dump($verified);



        return $this->render('front/index.html.twig', []);
    }

    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        //$session = $request->getSession();
        // $session->invalidate();
        //  var_dump($session->get('idsession'));
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();



        return $this->render('admin/users.html.twig', [
            'users' => $users,
            'datelioum' => new \DateTime(),
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     */
    public function ADD(EntityManagerInterface $entityManager, Request $request): Response
    {
        $Data = $request->request->all();
        $login = $Data["UserName"];
        $name = $Data["Name"];
        $lastname = $Data["LastName"];
        $email = $Data["Email"];
        $password = $Data["password"];


        //getSalt
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8080/getsalt');
        $salt = $response->getContent();



        // Create a POST request psw crypté 
        $response2 = $client->request('POST', 'http://localhost:8080/password', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' =>
            [
                'salt' => $salt,
                'password' => $password
            ],
        ]);

        $crypted = $response2->getContent();

        $user = new User();
        $time = new \DateTime();
        $user->setCreated($time);
        $user->setUsername($login);
        $user->setFirstName($name);
        $user->setLastName($lastname);
        $user->setEmail($email);
        $user->setSalt($salt);
        $user->setPassword($crypted);
        $user->setRole("player");

        $entityManager->persist($user);
        $entityManager->flush();

        return
            $this->redirectToRoute('app_user_index');
    }


    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setSalt("aaaaaaaaaaaa");
            $user->setRole("Player");
            $time = new \DateTime();
            $user->setCreated($time);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{idUser}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $user->setPicture(null);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUser}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getIdUser(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
