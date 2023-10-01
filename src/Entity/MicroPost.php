<?php

namespace App\Entity;

use App\Repository\MicroPostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MicroPostRepository::class)]
class MicroPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 5, max: 255, minMessage: 'Title is to short, 5 characters or more')]
    private string $title;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 5, max: 500, minMessage: 'Title is to short, 5 characters or more')]
    private string $text;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $created;

    #[ORM\OneToMany(mappedBy: 'microPost', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comment;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'liked')]
    private Collection $likedBy;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->likedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

/**
 * @return Collection<int, Comment>
 */
public function getComment(): Collection
{
    return $this->comment;
}

public function addComment(Comment $comment): self
{
    if (!$this->comment->contains($comment)) {
        $this->comment->add($comment);
        $comment->setMicroPost($this);
    }

    return $this;
}

public function removeComment(Comment $comment): self
{
    if ($this->comment->removeElement($comment)) {
        // set the owning side to null (unless already changed)
        if ($comment->getMicroPost() === $this) {
            $comment->setMicroPost(null);
        }
    }

    return $this;
}

/**
 * @return Collection<int, User>
 */
public function getLikedBy(): Collection
{
    return $this->likedBy;
}

public function addLikedBy(User $likedBy): self
{
    if (!$this->likedBy->contains($likedBy)) {
        $this->likedBy->add($likedBy);
    }

    return $this;
}

public function removeLikedBy(User $likedBy): self
{
    $this->likedBy->removeElement($likedBy);

    return $this;
}
}
