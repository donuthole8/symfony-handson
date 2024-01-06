<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FollowerController extends AbstractController
{
    #[Route('/follow/{id}', name: 'follow')]
    public function follow(
        User $userToFollow,
        ManagerRegistry $managerRegistry,
        Request $request,
    ): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($userToFollow->getId() !== $currentUser->getId()) {
            $currentUser->follow($userToFollow);
            $managerRegistry->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{id}', name: 'unfollow')]
    public function unfollow(
        User $userToFollow,
        ManagerRegistry $managerRegistry,
        Request $request,
    ): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($userToFollow->getId() !== $currentUser->getId()) {
            $currentUser->unfollow($userToFollow);
            $managerRegistry->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
