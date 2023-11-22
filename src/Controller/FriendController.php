<?php

namespace App\Controller;

use App\Entity\Relation;
use App\Service\FriendshipChecker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/friend')]
class FriendController extends AbstractController
{
    #[Route('/get', name: 'get_all_friends')]
    public function getFriends(FriendshipChecker $friendshipCheckerv): Response
    {
        return $this->json($friendshipCheckerv->getFriends(),200, [], ["groups"=>"friend"]);
    }

    #[Route('/remove/{id}', name: 'remove_friend')]
    public function removeFriend(Relation $relation, EntityManagerInterface $manager): Response
    {
        if ($this->getUser() === $relation->getPersonA()->getProfileUser() or $this->getUser() === $relation->getPersonB()->getProfileUser()){
            $manager->remove($relation);
            $manager->flush();

            return $this->json("friend has deleted",200);
        }

        return $this->json("friend not found",200);
    }
}
