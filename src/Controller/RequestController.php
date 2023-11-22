<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Relation;
use App\Entity\Request;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/request')]
class RequestController extends AbstractController
{
    #[Route('/all', name: 'all_requests')]
    public function allRequests(RequestRepository $requestRepository): Response
    {
        return $this->json($requestRepository->findAll(),200, [], ["groups"=>"request"]);
    }

    #[Route('/send/{id}', name: 'send_request')]
    public function sendRequest(EntityManagerInterface $manager, Profile $profile): Response
    {
        $request = new Request();

        $request->setSender($this->getUser()->getProfile());
        $request->setRecipient($profile);

        if ($this->getUser()->getProfile() === $profile) {
            return $this->json("sender === recipient", 200);
        }

        // Ajouter code pour voir si ils sont déjà ami // bloqué

        $manager->persist($request);
        $manager->flush();

        return $this->json("request send",200);
    }

    #[Route('/accept/{id}', name: 'accept_request')]
    public function acceptRequest(Request $request,EntityManagerInterface $manager): Response
    {
        $relation = new Relation();

        $relation->setPersonA($request->getSender());
        $relation->setPersonB($request->getRecipient());

        $manager->persist($relation);
        $manager->remove($request);
        $manager->flush();

        return $this->json("profile : ".$request->getSender()->getProfileUser()->getUsername()." add",200);
    }

    #[Route('/refuse/{id}', name: 'refuse_request')]
    public function refuseRequest(Request $request,EntityManagerInterface $manager):Response{

        $manager->remove($request);
        $manager->flush();

        return $this->json("friend request refuse with ".$request->getSender()->getProfileUser()->getUsername());
    }
}
