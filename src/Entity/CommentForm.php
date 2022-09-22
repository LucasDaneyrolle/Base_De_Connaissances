<?php

namespace App\Entity;

use App\Repository\CommentFormRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentFormRepository::class)]
class CommentForm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentForms')]
    private ?User $User = null;

    #[ORM\ManyToOne(inversedBy: 'commentForms')]
    private ?Form $Form = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->Form;
    }

    public function setForm(?Form $Form): self
    {
        $this->Form = $Form;

        return $this;
    }
}
