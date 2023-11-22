<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\Profile;
use App\Service\FriendshipChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/private/conversation')]
class PrivateConversationController extends AbstractController
{
    #[Route('/all', name: 'app_private_conversation_all')]
    public function myPrivateConversations(): Response
    {
        if ($this->getUser()) {
            return $this->json($this->getUser()->getProfile()->getPrivateConversations(),200, [], ["groups"=>"privateConversation:read"]);
        }

        return $this->json("no user connect",200);
    }

    #[Route('/create/{profile_id}', name: 'app_private_conversation_create')]
    public function create(
        EntityManagerInterface $manager,
        FriendshipChecker $friendshipChecker,
        #[MapEntity(id: 'profile_id')] Profile $profile,
    ): Response
    {
        $friends = $friendshipChecker->getFriends();

        if ($friends != []) {
            foreach ($friends as $item) {
                if ($profile->getProfileUser()->getUsername() === $item->getProfileUser()->getUsername()) {

                    $privateConversation = new PrivateConversation();

                    $privateConversation->setIndividualA($this->getUser()->getProfile());
                    $privateConversation->setIndividualB($profile);

                    $manager->persist($privateConversation);
                    $manager->flush();

                    return $this->json('Conversation create with '.$privateConversation->getIndividualA()->getName().' and '.$privateConversation->getIndividualB()->getName(),200);
                }
                return $this->json("no friends",200);
            }
        }

        return $this->json("array empty",200);
    }

    #[Route('/{privateConversation_id}/messages', name: 'app_private_conversation_get_messages')]
    public function getAllMessagesByConversation(
        #[MapEntity(id: 'privateConversation_id')] PrivateConversation $privateConversation,
    ): Response
    {
        if ($this->getUser() === $privateConversation->getIndividualA()->getProfileUser() || $this->getUser() === $privateConversation->getIndividualB()->getProfileUser()) {
            return $this->json($privateConversation->getPrivateMessages(), 200, [], ["groups"=>"message_private_conversation"]);
        }

        return $this->json("not find",200);
    }


    // delete a conversation if ne sont plus friend/ban
    // delete tout court ?
}
