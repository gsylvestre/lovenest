<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Regex(pattern="/^[a-zA-Z0-9_]+$/", message="Lettres sans accent, chiffres et underscores svp !")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\Email(message="Cet email n'est pas valide !")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=ProfilePicture::class, mappedBy="user", orphanRemoval=true)
     */
    private $profilePicture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreated;

    /**
     * @ORM\OneToOne(targetEntity=Profile::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\OneToOne(targetEntity=SearchCriterias::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $searchCriterias;

    /**
     * @ORM\OneToMany(targetEntity=Heart::class, mappedBy="initiatedBy")
     */
    private $sentHearts;

    /**
     * @ORM\OneToMany(targetEntity=Heart::class, mappedBy="sentTo")
     */
    private $receivedHearts;


    public function __construct()
    {
        $this->profilePicture = new ArrayCollection();
        $this->sentHearts = new ArrayCollection();
        $this->receivedHearts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
     * @return Collection|ProfilePicture[]
     */
    public function getProfilePicture(): Collection
    {
        return $this->profilePicture;
    }

    public function addProfilePicture(ProfilePicture $profilePicture): self
    {
        if (!$this->profilePicture->contains($profilePicture)) {
            $this->profilePicture[] = $profilePicture;
            $profilePicture->setUser($this);
        }

        return $this;
    }

    public function removeProfilePicture(ProfilePicture $profilePicture): self
    {
        if ($this->profilePicture->removeElement($profilePicture)) {
            // set the owning side to null (unless already changed)
            if ($profilePicture->getUser() === $this) {
                $profilePicture->setUser(null);
            }
        }

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        // set the owning side of the relation if necessary
        if ($profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }

    public function getSearchCriterias(): ?SearchCriterias
    {
        return $this->searchCriterias;
    }

    public function setSearchCriterias(SearchCriterias $searchCriterias): self
    {
        // set the owning side of the relation if necessary
        if ($searchCriterias->getUser() !== $this) {
            $searchCriterias->setUser($this);
        }

        $this->searchCriterias = $searchCriterias;

        return $this;
    }

    /**
     * @return Collection|Heart[]
     */
    public function getSentHearts(): Collection
    {
        return $this->sentHearts;
    }

    public function addSentHeart(Heart $sentHeart): self
    {
        if (!$this->sentHearts->contains($sentHeart)) {
            $this->sentHearts[] = $sentHeart;
            $sentHeart->setInitiatedBy($this);
        }

        return $this;
    }

    public function removeSentHeart(Heart $sentHeart): self
    {
        if ($this->sentHearts->removeElement($sentHeart)) {
            // set the owning side to null (unless already changed)
            if ($sentHeart->getInitiatedBy() === $this) {
                $sentHeart->setInitiatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Heart[]
     */
    public function getReceivedHearts(): Collection
    {
        return $this->receivedHearts;
    }

    public function addReceivedHeart(Heart $receivedHeart): self
    {
        if (!$this->receivedHearts->contains($receivedHeart)) {
            $this->receivedHearts[] = $receivedHeart;
            $receivedHeart->setSentTo($this);
        }

        return $this;
    }

    public function removeReceivedHeart(Heart $receivedHeart): self
    {
        if ($this->receivedHearts->removeElement($receivedHeart)) {
            // set the owning side to null (unless already changed)
            if ($receivedHeart->getSentTo() === $this) {
                $receivedHeart->setSentTo(null);
            }
        }

        return $this;
    }
}
