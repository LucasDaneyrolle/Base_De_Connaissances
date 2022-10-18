<?php

namespace App\Entity;

use App\Repository\FormRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormRepository::class)]
class Form
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $problem = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $solution = null;

    #[ORM\Column]
    private ?int $state = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'fiches')]
    private ?User $User = null;

    #[ORM\OneToMany(mappedBy: 'Form', targetEntity: CommentForm::class)]
    private Collection $commentForms;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'forms')]
    private Collection $categoryForm;

    public function __construct()
    {
        $this->commentForms = new ArrayCollection();
        $this->categoryForm = new ArrayCollection();
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

    public function getProblem(): ?string
    {
        return $this->problem;
    }

    public function setProblem(string $problem): self
    {
        $this->problem = $problem;

        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(string $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    /**
     * @return Collection<int, CommentForm>
     */
    public function getCommentForms(): Collection
    {
        return $this->commentForms;
    }

    public function addCommentForm(CommentForm $commentForm): self
    {
        if (!$this->commentForms->contains($commentForm)) {
            $this->commentForms->add($commentForm);
            $commentForm->setForm($this);
        }

        return $this;
    }

    public function removeCommentForm(CommentForm $commentForm): self
    {
        if ($this->commentForms->removeElement($commentForm)) {
            // set the owning side to null (unless already changed)
            if ($commentForm->getForm() === $this) {
                $commentForm->setForm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoryForm(): Collection
    {
        return $this->categoryForm;
    }

    public function addCategoryForm(Category $categoryForm): self
    {
        if (!$this->categoryForm->contains($categoryForm)) {
            $this->categoryForm->add($categoryForm);
        }

        return $this;
    }

    public function removeCategoryForm(Category $categoryForm): self
    {
        $this->categoryForm->removeElement($categoryForm);

        return $this;
    }
}
