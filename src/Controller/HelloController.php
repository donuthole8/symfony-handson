<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    private array $messages = [
        ["message" => "hello", "created" => "2022/06/12"],
        ["message" => "hi",    "created" => "2022/04/12"],
        ["message" => "bye",   "created" => "2022/04/12"],
    ];

    #[Route('/hello', name: 'app_index')]
    public function index(UserProfileRepository $profiles): Response
    {
        $user = new User();
        $user->setEmail(bin2hex(openssl_random_pseudo_bytes(8)) . '@gmail.com');
        $user->setPassword('pass');

        $profile = new UserProfile();
        $profile->setUser($user);
        $profiles->save($profile, true);

        return $this->render(
            'hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => 3
            ]
        );
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne(int $id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
    }
}
