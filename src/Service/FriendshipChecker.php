<?php

namespace App\Service;

use App\Repository\RelationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FriendshipChecker extends AbstractController
{
    private $repository;

    public function __construct(RelationRepository $repository){
        $this->repository = $repository;
    }

    public function getFriends(): array
    {
        // donner des users à vérifier -> évite de parcourir toute la table
        $friends = [];

        foreach ($this->repository->findAll() as $item){
            if ($this->getUser()->getProfile()->getId() == $item->getPersonA()->getId()) {
                $friends[] = $item->getPersonB();
            }
            elseif ($this->getUser()->getProfile()->getId() == $item->getPersonB()->getId()) {
                $friends[] = $item->getPersonA();
            }
        }

        return $friends;
    }
}