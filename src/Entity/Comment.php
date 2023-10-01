<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 5, max: 500)]
    private string $text;

    #[ORM\ManyToOne(inversedBy: 'comment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MicroPost $microPost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getMicroPost(): ?MicroPost
    {
        return $this->microPost;
    }

    public function setMicroPost(?MicroPost $microPost): self
    {
        $this->microPost = $microPost;

        return $this;
    }
}
