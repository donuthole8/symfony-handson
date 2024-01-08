<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile')]
    public function show_profile(
        User                $user,
        MicroPostRepository $microPostRepository
    ): Response {
        return $this->render('profile/detail.html.twig', [
            'user' => $user,
            'microPosts' => $microPostRepository->findAllByAuthor($user)
        ]);
    }

    #[Route('/profile/{id}/follows', name: 'profile_follows')]
    public function follows(User $user): Response
    {
        return $this->render('profile/follows.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('//profile/{id}/followers', name: 'profile_followers')]
    public function followers(User $user): Response
    {
        return $this->render('profile/followers.html.twig', [
            'user' => $user,
        ]);
    }
}
