<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isAngular;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isSvelte;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isSpring;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVue;

    #[ORM\ManyToMany(targetEntity: Friends::class, mappedBy: 'idUser')]
    private $friends;

    public function __construct()
    {
        $this->friends = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsAngular(): ?bool
    {
        return $this->isAngular;
    }

    public function setIsAngular(?bool $isAngular): self
    {
        $this->isAngular = $isAngular;

        return $this;
    }

    public function getIsSvelte(): ?bool
    {
        return $this->isSvelte;
    }

    public function setIsSvelte(?bool $isSvelte): self
    {
        $this->isSvelte = $isSvelte;

        return $this;
    }

    public function getIsSpring(): ?bool
    {
        return $this->isSpring;
    }

    public function setIsSpring(?bool $isSpring): self
    {
        $this->isSpring = $isSpring;

        return $this;
    }

    public function getIsVue(): ?bool
    {
        return $this->isVue;
    }

    public function setIsVue(?bool $isVue): self
    {
        $this->isVue = $isVue;

        return $this;
    }

    /**
     * @return Collection|Friends[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friends $friend): self
    {

        if ($this->friends->contains($friend)) {
            return $this->removeFriend($friend);
        }

        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
            $friend->addIdUser($this);
        }

        return $this;
    }

    public function isFriend(Friends $friend)
    {
        if ($this->friends->contains($friend)) {
            return true;
        }else{
            return false;
        }

    }


    public function removeFriend(Friends $friend): self
    {
        if ($this->friends->removeElement($friend)) {
            $friend->removeIdUser($this);
        }

        return $this;
    }

}
