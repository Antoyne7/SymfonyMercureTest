<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\MercureCookieGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/utilisateurs", name="user")
     * @param UtilisateurRepository $users
     * @param LoggerInterface $log
     * @param MercureCookieGenerator $cookieGenerator
     * @return Response
     */
    public function usersList(UtilisateurRepository $users, LoggerInterface $log , MercureCookieGenerator $cookieGenerator)
    {


        $user = $this->getUser();

        $response =  $this->render('admin/users.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users->findAll(),
            'userbis' => $user
        ]);
//        $response->headers->set('set-cookie', $cookieGenerator->generate($this->getUser()));

        return $response;
    }

    /**
     * @Route("/ping/{user}", name="ping",methods={"POST"})
     * @param UtilisateurRepository $users
     * @param Utilisateur|null $user
     * @param PublisherInterface $publisher
     * @param LoggerInterface $logger
     * @return Response
     */
    public function adminPing(UtilisateurRepository $users, ?Utilisateur $user, MessageBusInterface $bus, LoggerInterface $logger)
    {
        $logger->info('Log : je log !');
        $target = [];
        if ($user !== null){
            $target = [
                "ping/{$user->getId()}"
            ];
        }

        $update = new Update("ping", '[]', $target);  // , $target
        $bus->dispatch($update);

        return $this->redirectToRoute("admin_user");
    }

}
