<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test-1@email.com');
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1,
                'pppppppp',
            )
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('test-2@email.com');
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2,
                'pppppppp',
            )
        );
        $manager->persist($user2);

        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welcome to Poland!');
        $microPost1->setText('Welcome poland');
        $microPost1->setAuthor($user1);
        $microPost1->setCreated(new DateTime());

        $manager->persist($microPost1);

        $microPost2= new MicroPost();
        $microPost2->setTitle('Welcome to USA!');
        $microPost2->setText('Welcome usa');
        $microPost2->setCreated(new DateTime());
        $microPost2->setAuthor($user1);
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welcome to Germany!');
        $microPost3->setText('Welcome germany');
        $microPost3->setCreated(new DateTime());
        $microPost3->setAuthor($user2);
        $manager->persist($microPost3);

        $microPost4 = new MicroPost();
        $microPost4->setTitle('Welcome to Greece!');
        $microPost4->setText('Welcome greece');
        $microPost4->setCreated(new DateTime());
        $microPost4->setAuthor($user2);
        $manager->persist($microPost4);

        $microPost5 = new MicroPost();
        $microPost5->setTitle('Welcome to Italy!');
        $microPost5->setText('Welcome italy');
        $microPost5->setCreated(new DateTime());
        $microPost5->setAuthor($user2);
        $manager->persist($microPost5);

        $manager->flush();
    }
}
