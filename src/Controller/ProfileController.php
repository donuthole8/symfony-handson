<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'profile')]
    public function show_profile(User $user): Response
    {
        return $this->render('profile/detail.html.twig', [
            'user' => $user,
        ]);
    }
}
