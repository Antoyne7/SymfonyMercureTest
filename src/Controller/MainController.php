<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\MercureCookieGenerator;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class MainController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/{userToSend}", name="accueil", requirements={"userToSend"="\d+"})
     * @param LoggerInterface $logger
     * @param MercureCookieGenerator $cookieGenerator
     * @param UtilisateurRepository $users
     * @param Utilisateur|null $userToSend
     * @return Response
     */
    public function index(LoggerInterface $logger, MercureCookieGenerator $cookieGenerator,UtilisateurRepository $users,?Utilisateur $userToSend = null)
    {
        $logger->info('Log : accueil');

        $user = $this->getUser();

        $messagesReceived = $user->getMessagesSent();
        $messagesSent = $user->getMessages();

        $response = $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'users' => $users->findAll(),
            'userToSend' => $userToSend,
            'messagesReceived' => $messagesReceived,
            'messagesSent' => $messagesSent

        ]);

        $response->headers->set('set-cookie', $cookieGenerator->generate($this->getUser()));

        return $response;
    }




    /**
     * @Route("/liste", name="liste")
     */
    public function liste()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }


    /**
     * @Route("/ping/{toUser}", name="ping", methods={"POST"})
     * @param Request $request
     * @param MessageBusInterface $bus
     * @param LoggerInterface $logger
     * @param Utilisateur|null $toUser
     * @param SerializerInterface $serializer
     * @return RedirectResponse
     */
    public function ping(Request $request, MessageBusInterface $bus, LoggerInterface $logger,?Utilisateur $toUser, SerializerInterface $serializer)
    {
        $logger->info('Log : je log !');
        $data = array();
        $data[] = ["sendBy" => $serializer->serialize($this->getUser(),'json', ['groups' => 'public'])];
        $data[] = ["sendTo" => $serializer->serialize($toUser,'json', ['groups' => 'public'])];
        $data[] = $request->request->get("message");

        if ($toUser !== null){
            $update = new Update(
                "ping",
                $serializer->serialize($data,'json')
            );

            $message = new Message($this->getUser(),$toUser,$request->request->get("message"));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            $bus->dispatch($update);
        }

        return $this->redirect($request->headers->get("referer"));
    }


}
