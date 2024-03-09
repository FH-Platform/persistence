<?php

namespace FHPlatform\PersistenceBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PreDeleteEntityEvent extends Event
{
    public function __construct(
        private readonly string $className,
        private readonly mixed $identifier,
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getIdentifier(): mixed
    {
        return $this->identifier;
    }
}
