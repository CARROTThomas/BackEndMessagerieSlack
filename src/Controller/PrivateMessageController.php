<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use App\Service\ImagePostProcessing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/private/message')]
class PrivateMessageController extends AbstractController
{
    #[Route('/send/{privateConversation_id}', name: 'app_private_message_send')]
    public function send(
        Request $request,
        EntityManagerInterface $manager,
        SerializerInterface $serializer,
        ImagePostProcessing $imagePostProcessing,
        #[MapEntity(id: 'privateConversation_id')] PrivateConversation $privateConversation,
    ): Response
    {
        if ($this->getUser() === $privateConversation->getIndividualA()->getProfileUser() || $this->getUser() === $privateConversation->getIndividualB()->getProfileUser()) {
            $privateMessage = $serializer->deserialize($request->getContent(),PrivateMessage::class,"json");

            $imageIdsArray = $privateMessage->getAssociatedImages();
            if ($imageIdsArray){
                //dd($imageIdsArray);
                $newImages = $imagePostProcessing->getImagesFromIds($imageIdsArray);
                foreach ($newImages as $image){
                    $privateMessage->addImage($image);
                }
            }

            $privateMessage->setAuthor($this->getUser()->getProfile());
            $privateMessage->setPrivateConversation($privateConversation);

            //dd($privateMessage->getAssociatedImages());
            $manager->persist($privateMessage);
            $manager->flush();

            return $this->json("message send", 201);
        }
        return $this->json("cant be send", 204);
    }

    #[Route('/edit/{privateMessage_id}', name: 'app_private_message_edit', methods: ['PUT'])]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        #[MapEntity(id: 'privateMessage_id')] PrivateMessage $privateMessage,
    ): Response
    {
        if ($privateMessage->getAuthor() == $this->getUser()->getProfile()){

            $content = json_decode($request->getContent(),true);
            $privateMessage->setContent($content['content']);

            $manager->persist($privateMessage);
            $manager->flush();

            return $this->json("message edit !",200);
        }
        return $this->json("cant edit this message", 200);
    }

    #[Route('/remove/{privateMessage_id}', name: 'app_private_message_remove')]
    public function remove(
        EntityManagerInterface $manager,
        #[MapEntity(id: 'privateMessage_id')] PrivateMessage $privateMessage,
    ): Response
    {
        if ($privateMessage->getAuthor() == $this->getUser()->getProfile()) {
            $manager->remove($privateMessage);
            $manager->flush();
            return $this->json("message deleted",200);
        }
        return $this->json("cant be delete", 200);
    }
}
