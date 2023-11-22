<?php

namespace App\Controller;

use App\Entity\GroupConversation;
use App\Entity\GroupMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/group/conversation')]
class GroupMessageController extends AbstractController
{
    #[Route('/send/{groupConversation_id}', name: 'app_group_message_send')]
    public function send(
        Request $request,
        EntityManagerInterface $manager,
        SerializerInterface $serializer,
        #[MapEntity(id: 'groupConversation_id')] GroupConversation $groupConversation,
    ): Response
    {
        foreach ($groupConversation->getMembers() as $profileMember){
            if ($this->getUser()->getProfile() === $profileMember) {

                $groupMessage = $serializer->deserialize($request->getContent(), GroupMessage::class, "json");
                $groupMessage->setAuthor($this->getUser()->getProfile());
                $groupMessage->setGroupConversation($groupConversation);

                $manager->persist($groupMessage);
                $manager->flush();

                return $this->json("message send in group", 201);
            }
        }

        return $this->json("cant be send", 204);
    }

    #[Route('/edit/{groupMessage_id}', name: 'app_group_message_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        #[MapEntity(id: 'groupMessage_id')] GroupMessage $groupMessage,
    ): Response
    {
        if ($groupMessage->getAuthor() == $this->getUser()->getProfile()){

            $content = json_decode($request->getContent(),true);
            $groupMessage->setContent($content['content']);

            $manager->persist($groupMessage);
            $manager->flush();

            return $this->json("message edit !",200);
        }
        return $this->json("cant edit this message", 200);
    }

    #[Route('/remove/{groupMessage_id}', name: 'app_group_message_remove')]
    public function remove(
        EntityManagerInterface $manager,
        #[MapEntity(id: 'groupMessage_id')] GroupMessage $groupMessage,
    ): Response
    {
        if ($groupMessage->getAuthor() == $this->getUser()->getProfile()) {
            $manager->remove($groupMessage);
            $manager->flush();
            return $this->json("message deleted",200);
        }
        return $this->json("cant be delete", 200);
    }
}
