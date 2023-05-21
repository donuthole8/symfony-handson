<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'micro_post_index')]
    public function index(MicroPostRepository $posts): Response
    {
        // $microPost = new MicroPost();
        // $microPost->setTitle('FromController');
        // $microPost->setText('from controller');
        // $microPost->setCreated(new DateTime());
        // $posts->save($microPost, true);
        // dd($posts->find(1));

        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response {
        // dd($posts->find($id));
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, MicroPostRepository $posts): Response {
        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $posts->save($post, true);

            // add a flash
            $this->addFlash('success', 'success!!');

            // redirect
            return $this->redirectToRoute('micro_post_index');
        }

        return $this->renderForm(
            'micro_post/add.html.twig', 
            [
                'form' => $form
            ]
        );
    }

    #[Route('/micro-post/edit', name: 'app_micro_post_edit', priority: 2)]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $posts): Response {
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $posts->save($post, true);

            // add a flash
            $this->addFlash('success', 'success edit!!');

            // redirect
            return $this->redirectToRoute('micro_post_index');
        }

        return $this->renderForm(
            'micro_post/edit.html.twig', 
            [
                'form' => $form
            ]
        );
    }
}