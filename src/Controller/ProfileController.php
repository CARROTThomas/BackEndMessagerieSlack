<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/profile')]
class ProfileController extends AbstractController
{
    #[Route('/all', name: 'all_profiles')]
    public function allProfile(ProfileRepository $profileRepository): Response
    {
        return $this->json($profileRepository->findAll(), 200, [], ["groups" =>"profile"]);
    }

    #[Route('/show/{id}', name: 'app_user',methods: 'GET')]
    public function showProfile(Profile $profile): Response
    {
        return $this->json($profile,200,[],["groups"=>"profile"]);
    }
}
