<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'micro_post_index')]
    public function index(): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'controller_name' => 'MicroPostController',
        ]);
    }
}