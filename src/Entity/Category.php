<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: FormCategory::class)]
    private Collection $categoryForms;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: CategoryTopic::class)]
    private Collection $commentTopics;

    public function __construct()
    {
        $this->categoryForms = new ArrayCollection();
        $this->commentTopics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, FormCategory>
     */
    public function getCategoryForms(): Collection
    {
        return $this->categoryForms;
    }

    public function addCategoryForm(FormCategory $categoryForm): self
    {
        if (!$this->categoryForms->contains($categoryForm)) {
            $this->categoryForms->add($categoryForm);
            $categoryForm->setCategory($this);
        }

        return $this;
    }

    public function removeCategoryForm(FormCategory $categoryForm): self
    {
        if ($this->categoryForms->removeElement($categoryForm)) {
            // set the owning side to null (unless already changed)
            if ($categoryForm->getCategory() === $this) {
                $categoryForm->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CategoryTopic>
     */
    public function getCommentTopics(): Collection
    {
        return $this->commentTopics;
    }

    public function addCommentTopic(CategoryTopic $commentTopic): self
    {
        if (!$this->commentTopics->contains($commentTopic)) {
            $this->commentTopics->add($commentTopic);
            $commentTopic->setCategory($this);
        }

        return $this;
    }

    public function removeCommentTopic(CategoryTopic $commentTopic): self
    {
        if ($this->commentTopics->removeElement($commentTopic)) {
            // set the owning side to null (unless already changed)
            if ($commentTopic->getCategory() === $this) {
                $commentTopic->setCategory(null);
            }
        }

        return $this;
    }
}
