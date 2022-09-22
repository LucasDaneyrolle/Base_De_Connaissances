<?php

namespace App\Entity;

use App\Repository\FormCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormCategoryRepository::class)]
class FormCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Form::class, inversedBy: 'categoryForms')]
    private Collection $fiche;

    #[ORM\ManyToOne(inversedBy: 'categoryForms')]
    private ?Category $category = null;

    public function __construct()
    {
        $this->fiche = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Form>
     */
    public function getFiche(): Collection
    {
        return $this->fiche;
    }

    public function addFiche(Form $fiche): self
    {
        if (!$this->fiche->contains($fiche)) {
            $this->fiche->add($fiche);
        }

        return $this;
    }

    public function removeFiche(Form $fiche): self
    {
        $this->fiche->removeElement($fiche);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
