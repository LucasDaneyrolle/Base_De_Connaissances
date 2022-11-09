<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Form::class)]
    private Collection $fiches;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: CommentForm::class)]
    private Collection $commentForms;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ResponseTopic::class)]
    private Collection $topicCategories;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Topic::class)]
    private Collection $topics;

    public function __construct()
    {
        $this->fiches = new ArrayCollection();
        $this->commentForms = new ArrayCollection();
        $this->topicCategories = new ArrayCollection();
        $this->topics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Form>
     */
    public function getFiches(): Collection
    {
        return $this->fiches;
    }

    public function addFiche(Form $fiche): self
    {
        if (!$this->fiches->contains($fiche)) {
            $this->fiches->add($fiche);
            $fiche->setUser($this);
        }

        return $this;
    }

    public function removeFiche(Form $fiche): self
    {
        if ($this->fiches->removeElement($fiche)) {
            // set the owning side to null (unless already changed)
            if ($fiche->getUser() === $this) {
                $fiche->setUser(null);
            }
        }

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
            $commentForm->setUser($this);
        }

        return $this;
    }

    public function removeCommentForm(CommentForm $commentForm): self
    {
        if ($this->commentForms->removeElement($commentForm)) {
            // set the owning side to null (unless already changed)
            if ($commentForm->getUser() === $this) {
                $commentForm->setUser(null);
            }
        }

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
            $topicCategory->setUser($this);
        }

        return $this;
    }

    public function removeTopicCategory(ResponseTopic $topicCategory): self
    {
        if ($this->topicCategories->removeElement($topicCategory)) {
            // set the owning side to null (unless already changed)
            if ($topicCategory->getUser() === $this) {
                $topicCategory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Topic>
     */
    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function addTopic(Topic $topic): self
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setUser($this);
        }

        return $this;
    }

    public function removeTopic(Topic $topic): self
    {
        if ($this->topics->removeElement($topic)) {
            // set the owning side to null (unless already changed)
            if ($topic->getUser() === $this) {
                $topic->setUser(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->getNom();
    }
}
