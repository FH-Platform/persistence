<?php

namespace FHPlatform\PersistenceBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ChangedEntitiesEvent extends Event
{
    public function __construct(
        private readonly array $entities,
    ) {
    }

    public function getEntities(): array
    {
        return $this->entities;
    }
}
