<?php

namespace App\Controller;

use App\Entity\PrivateMessage;
use App\Entity\PrivateResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/private/response')]
class PrivateResponseController extends AbstractController
{
    #[Route('/send/{privateMessage_id}', name: 'app_private_response_send')]
    public function send(
        Request $request,
        EntityManagerInterface $manager,
        SerializerInterface $serializer,
        #[MapEntity(id: 'privateMessage_id')] PrivateMessage $privateMessage,
    ): Response
    {
        if ($this->getUser() === $privateMessage->getPrivateConversation()->getIndividualA()->getProfileUser() || $this->getUser() === $privateMessage->getPrivateConversation()->getIndividualB()->getProfileUser()) {
            $privateResponse = $serializer->deserialize($request->getContent(),PrivateResponse::class,"json");
            $privateResponse->setAuthor($this->getUser()->getProfile());
            $privateResponse->setPrivateMessage($privateMessage);

            $manager->persist($privateResponse);
            $manager->flush();

            return $this->json("response send", 201);
        }
        return $this->json("cant be send", 204);
    }

    #[Route('/edit/{privateResponse_id}', name: 'app_private_response_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        #[MapEntity(id: 'privateResponse_id')] PrivateResponse $privateResponse,
    ): Response
    {
        if ($privateResponse->getAuthor() == $this->getUser()->getProfile()){

            $content = json_decode($request->getContent(),true);
            $privateResponse->setContent($content['content']);

            $manager->persist($privateResponse);
            $manager->flush();

            return $this->json("message edit !",200);
        }
        return $this->json("cant edit this message", 200);
    }

    #[Route('/remove/{privateResponse_id}', name: 'app_private_response_remove')]
    public function remove(
        EntityManagerInterface $manager,
        #[MapEntity(id: 'privateResponse_id')] PrivateResponse $privateResponse,
    ): Response
    {
        if ($privateResponse->getAuthor() == $this->getUser()->getProfile()) {
            $manager->remove($privateResponse);
            $manager->flush();
            return $this->json("response deleted",200);
        }
        return $this->json("cant be delete", 200);
    }
}
