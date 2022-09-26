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
    private ?int $state = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: ResponseTopic::class)]
    private Collection $topicCategories;

    #[ORM\OneToMany(mappedBy: 'topic', targetEntity: CommentTopic::class)]
    private Collection $commentTopics;

    public function __construct()
    {
        $this->topicCategories = new ArrayCollection();
        $this->commentTopics = new ArrayCollection();
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

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

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
    public function getTopicCategories(): Collection
    {
        return $this->topicCategories;
    }

    public function addTopicCategory(ResponseTopic $topicCategory): self
    {
        if (!$this->topicCategories->contains($topicCategory)) {
            $this->topicCategories->add($topicCategory);
            $topicCategory->setTopic($this);
        }

        return $this;
    }

    public function removeTopicCategory(ResponseTopic $topicCategory): self
    {
        if ($this->topicCategories->removeElement($topicCategory)) {
            // set the owning side to null (unless already changed)
            if ($topicCategory->getTopic() === $this) {
                $topicCategory->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentTopic>
     */
    public function getCommentTopics(): Collection
    {
        return $this->commentTopics;
    }

    public function addCommentTopic(CommentTopic $commentTopic): self
    {
        if (!$this->commentTopics->contains($commentTopic)) {
            $this->commentTopics->add($commentTopic);
            $commentTopic->setTopic($this);
        }

        return $this;
    }

    public function removeCommentTopic(CommentTopic $commentTopic): self
    {
        if ($this->commentTopics->removeElement($commentTopic)) {
            // set the owning side to null (unless already changed)
            if ($commentTopic->getTopic() === $this) {
                $commentTopic->setTopic(null);
            }
        }

        return $this;
    }
}