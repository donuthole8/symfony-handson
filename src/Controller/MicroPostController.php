<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        MicroPostRepository $microPostRepository
    ): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'microPosts' => $microPostRepository->findAllWithComments(),
        ]);
    }

    #[Route('/micro_post', name: 'micro_post_index')]
    public function microPostIndex(
        MicroPostRepository $microPostRepository,
    ): Response {
        return $this->render('micro_post/index.html.twig', [
            'microPosts' => $microPostRepository->findAllWithComments(),
        ]);
    }

    #[Route('/micro_post/{microPost}', name: 'micro_post_detail')]
    #[IsGranted(MicroPost::VIEW, 'microPost')]
    public function microPostDetail(
        MicroPost $microPost,
    ): Response {
        return $this->render('micro_post/detail.html.twig', [
            'microPost' => $microPost,
        ]);
    }

    #[Route('/micro_post/add', name: 'micro_post_add', priority: 2)]
    #[IsGranted('ROLE_WRITER')]
    public function addMicroPost(
        Request $request,
        MicroPostRepository $microPostRepository,
    ): Response {
        $form = $this->createForm(MicroPostType::class, new MicroPost());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $microPost = $form->getData();
            $microPost->setAuthor($this->getUser());
            $microPostRepository->save($microPost, true);

            $this->addFlash('success', 'Your micro post have been added');

            return $this->redirectToRoute('micro_post_index');
        }

        return $this->renderForm('micro_post/add.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/micro_post/{microPost}/edit', name: 'micro_post_edit')]
    #[IsGranted(MicroPost::EDIT, 'microPost')]
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

        return $this->renderForm('micro_post/edit.html.twig', [
            'form' => $form,
            'microPost' => $microPost
        ]);
    }

    #[Route('/micro_post/{microPost}/comment', name: 'micro_post_comment_add')]
    #[IsGranted('ROLE_COMMENTER')]
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
            $microPostComment->setAuthor($this->getUser());
            $commentRepository->save($microPostComment, true);

            $this->addFlash('success', 'Your comment have been post');

            return $this->redirectToRoute('micro_post_detail', [
                'microPost' => $microPost->getId()
            ]);
        }

        return $this->renderForm('micro_post/comment.html.twig', [
            'form' => $form,
            'microPost' => $microPost,
        ]);
    }

    #[Route('/micro_post/top_liked', name: 'micro_post_top_liked')]
    public function topLiked(
        MicroPostRepository $microPostRepository
    ): Response
    {
        return $this->render('micro_post/top_liked.html.twig', [
            'microPosts' => $microPostRepository->findAllWithComments(),
        ]);
    }

    #[Route('/micro_post/follows', name: 'micro_post_follows')]
    public function follows(
        MicroPostRepository $microPostRepository
    ): Response
    {
        return $this->render('micro_post/follows.html.twig', [
            'microPosts' => $microPostRepository->findAllWithComments(),
        ]);
    }
}