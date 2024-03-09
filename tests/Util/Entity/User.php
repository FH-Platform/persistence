<?php

namespace FHPlatform\PersistenceBundle\Tests\Util\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nameString = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $nameText = '';

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameString(): ?string
    {
        return $this->nameString;
    }

    public function setNameString(?string $nameString): void
    {
        $this->nameString = $nameString;
    }

    public function getNameText(): ?string
    {
        return $this->nameText;
    }

    public function setNameText(?string $nameText): void
    {
        $this->nameText = $nameText;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): void
    {
        $this->roles = $roles;
    }
}
