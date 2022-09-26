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

    #[ORM\ManyToOne(inversedBy: 'categoryForms')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'formCategories')]
    private ?Form $fiche = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFiche(): ?Form
    {
        return $this->fiche;
    }

    public function setFiche(?Form $fiche): self
    {
        $this->fiche = $fiche;

        return $this;
    }
}
