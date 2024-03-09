<?php

namespace FHPlatform\PersistenceBundle\Tests\Util\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class UserUuid
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    public ?UuidInterface $id = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $nameString = '';

    public function getId(): ?UuidInterface
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
}
