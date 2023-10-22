<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(
        Request $request,
        MicroPost $microPost,
        MicroPostRepository $microPostRepository,
    ): Response {
        $currentUser = $this->getUser();
        $microPost->addLikedBy($currentUser);
        $microPostRepository->save($microPost, true);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unlike/{id}', name: 'unlike')]
    public function unlike(
        Request $request,
        MicroPost $microPost,
        MicroPostRepository $microPostRepository,
    ): Response {
        $currentUser = $this->getUser();
        $microPost->removeLikedBy($currentUser);
        $microPostRepository->save($microPost, true);

        return $this->redirect($request->headers->get('referer'));
    }
}
