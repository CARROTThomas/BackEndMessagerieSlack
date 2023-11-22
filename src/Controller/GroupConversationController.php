<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\GroupConversation;
use App\Service\FriendshipChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/group/conversation')]
class GroupConversationController extends AbstractController
{
    #[Route('/all', name: 'app_group_conversation_all')]
    public function myGroupConversation(): Response
    {
        if ($this->getUser()) {
            return $this->json($this->getUser()->getProfile()->getGroupConversations(),200, [], ["groups"=>"groupConversation"]);
        }

        return $this->json("no user connect",200);
    }

    #[Route('/show/{groupConversation_id}', name: 'app_group_conversation_all')]
    public function show(
        #[MapEntity(id: 'groupConversation_id')] GroupConversation $groupConversation,
    ): Response
    {
        if (!$groupConversation) {return $this->json("no group", 200);}

        return $this->json($groupConversation->getGroupMessages(),200, [], ["groups"=>"message_group_conversation"]);
    }

    #[Route('/create', name: 'app_group_conversation_create')]
    public function create(
        EntityManagerInterface $manager
    ): Response
    {
        $groupConversation = new GroupConversation();
        $groupConversation->setOwner($this->getUser()->getProfile());
        $groupConversation->addMember($this->getUser()->getProfile());

        $manager->persist($groupConversation);
        $manager->flush();

        return $this->json("group create", 201);
    }

    #[Route('/people/add/{groupConversation_id}/{profile_id}', name: 'app_group_conversation_add_people')]
    public function addNewMember(
        EntityManagerInterface $manager,
        FriendshipChecker $friendshipChecker,
        #[MapEntity(id: 'profile_id')] Profile $profile,
        #[MapEntity(id: 'groupConversation_id')] GroupConversation $groupConversation,
    ): Response
    {
        foreach ($groupConversation->getMembers() as $groupMember){
            if ($groupMember === $profile) {return $this->json("profile déjà dans le group", 200);}
        }

        $friends = $friendshipChecker->getFriends();
        if (!$friends) {return $this->json("no data in friends[]", 200);}

        foreach ($friends as $item) {
            if ($profile->getProfileUser()->getUsername() === $item->getProfileUser()->getUsername()) {
                $groupConversation->addMember($profile);

                $manager->persist($groupConversation);
                $manager->flush();
                return $this->json("Le membre est ajouté au groupe", 200);
            }
        }

        return $this->json("erreur", 200);
    }

    #[Route('/delete/{groupConversation_id}', name: 'app_group_conversation_delete')]
    public function delete(
        #[MapEntity(id: 'groupConversation_id')] GroupConversation $groupConversation,
    ): Response
    {
        if ($this->getUser()->getProfile() === $groupConversation->getOwner()) {return $this->json("group delete", 200);}
        return $this->json("not owner", 200);
    }




    // exclure qqu'un
    // leave group => promote 1 people if I'm the owner (s'il n'y a personne suppr le groupe)
}
