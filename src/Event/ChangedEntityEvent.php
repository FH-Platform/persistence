<?php

namespace FHPlatform\PersistenceBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ChangedEntityEvent extends Event
{
    final public const TYPE_CREATE = 'create';
    final public const TYPE_UPDATE = 'update';
    final public const TYPE_DELETE = 'delete';
    final public const TYPE_DELETE_PRE = 'delete_pre';

    public function __construct(
        private readonly string $className,
        private readonly mixed $identifier,
        private readonly string $type,
        private readonly array $changedFields,
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getChangedFields(): array
    {
        return $this->changedFields;
    }
}
