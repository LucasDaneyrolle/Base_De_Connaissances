<?php

namespace App\Entity;

use App\Repository\TopicCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicCategoryRepository::class)]
class TopicCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Topic::class, inversedBy: 'topicCategories')]
    private Collection $topic;

    #[ORM\ManyToMany(targetEntity: Topic::class, inversedBy: 'topicCategories')]
    private Collection $category;

    public function __construct()
    {
        $this->topic = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Topic>
     */
    public function getTopic(): Collection
    {
        return $this->topic;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topic->contains($topic)) {
            $this->topic->add($topic);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        $this->topic->removeElement($topic);

        return $this;
    }

    /**
     * @return Collection<int, Topic>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Topic $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Topic $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
