<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: ResponseTopic::class)]
    private Collection $topicResponses;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'topics')]
    private Collection $topicCategory;

    #[ORM\ManyToOne(inversedBy: 'topics')]
    private ?User $User = null;

    //Stockage variable for topic
    public array $categoriesTopic;

    #[ORM\Column]
    private ?bool $state = null;

    public function __construct()
    {
        $this->topicResponses = new ArrayCollection();
        $this->topicCategory = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, ResponseTopic>
     */
    public function getTopicResponses(): Collection
    {
        return $this->topicResponses;
    }

    public function addTopicResponse(ResponseTopic $responseTopic): self
    {
        if (!$this->topicResponses->contains($responseTopic)) {
            $this->topicResponses->add($responseTopic);
            $responseTopic->setTopic($this);
        }

        return $this;
    }

    public function removeTopicResponse(ResponseTopic $responseTopic): self
    {
        if ($this->topicResponses->removeElement($responseTopic)) {
            // set the owning side to null (unless already changed)
            if ($responseTopic->getTopic() === $this) {
                $responseTopic->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getTopicCategory(): Collection
    {
        return $this->topicCategory;
    }

    public function addTopicCategory(Category $categoryForm): self
    {
        if (!$this->topicCategory->contains($categoryForm)) {
            $this->topicCategory->add($categoryForm);
        }

        return $this;
    }

    public function removeTopicCategory(Category $categoryForm): self
    {
        $this->topicCategory->removeElement($categoryForm);

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

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}