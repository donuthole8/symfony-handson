<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/micro_post', name: 'micro_post_index')]
    public function index(
        MicroPostRepository $microPostRepository,
    ): Response {
        return $this->render('micro_post/index.html.twig', [
            'microPosts' => $microPostRepository->findAll(),
        ]);
    }

    #[Route('/micro_post/{microPost}', name: 'micro_post_detail')]
    public function microPostDetail(
        MicroPost $microPost,
    ): Response {
        return $this->render('micro_post/show.html.twig', [
            'microPost' => $microPost,
        ]);
    }

    #[Route('/micro_post/add', name: 'micro_post_add', priority: 2)]
    public function addMicroPost(
        Request $request,
        MicroPostRepository $microPostRepository,
    ): Response {
        $form = $this->createForm(MicroPostType::class, new MicroPost());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $microPost = $form->getData();
            $microPost->setCreated(new DateTime());
            $microPostRepository->save($microPost, true);

            $this->addFlash('success', 'Your micro post have been added');

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->renderForm(
            'micro_post/add.html.twig', 
            [
                'form' => $form
            ]
        );
    }

    #[Route('/micro_post/{microPost}/edit', name: 'micro_post_edit')]
    public function editMicroPost(
        MicroPost $microPost,
        Request $request,
        MicroPostRepository $microPostRepository,
    ): Response {
        $form = $this->createForm(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $microPost = $form->getData();
            $microPostRepository->save($microPost, true);

            $this->addFlash('success', 'Your micro post have been edited');

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->renderForm(
            'micro_post/edit.html.twig', 
            [
                'form' => $form
            ]
        );
    }

    #[Route('/micro_post/{microPost}/comment', name: 'micro_post_comment_add')]
    public function addMicroPostComment(
        MicroPost $microPost,
        Request $request,
        CommentRepository $commentRepository,
    ): Response {
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $microPostComment = $form->getData();
            $microPostComment->setMicroPost($microPost);
            $commentRepository->save($microPostComment, true);

            $this->addFlash('success', 'Your comment have been post');

            return $this->redirectToRoute(
                'micro_post_detail',
                ['microPost' => $microPost->getId()]
            );
        }

        return $this->renderForm(
            'micro_post/comment.html.twig', 
            [
                'form' => $form,
                'microPost' => $microPost,
            ]
        );
    }
}