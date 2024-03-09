<?php

namespace FHPlatform\PersistenceBundle;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\PersistenceBundle\Event\ChangedEntityEvent;
use FHPlatform\PersistenceBundle\Event\PreDeleteEntityEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventsManager
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    protected array $events = [];

    public function dispatchPreDeleteEntityEvent($className, $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new PreDeleteEntityEvent($className, $identifierValue));
    }

    public function addEvent($className, $identifierValue, $type = ChangedEntityEvent::TYPE_UPDATE, $changedFields = ['id']): void
    {
        // make changes unique
        $hash = $className.'_'.$identifierValue;
        $this->events[$hash] = new ChangedEntityEvent($className, $identifierValue, $type, $changedFields);

        // TODO when there are more updates merge changedFields, or when is delete remove all updates
    }

    public function flushEvent(): void
    {
        // TODO by config flush or onKernelFinishRequest
        $this->dispatchEvents();
    }

    public function kernelFinishRequestEvent(): void
    {
        // TODO
    }

    public function dispatchEvents(): void
    {
        if (count($this->events)) {
            $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($this->events));
        }

        foreach ($this->events as $event) {
            $this->eventDispatcher->dispatch($event);
        }

        // reset var
        $this->events = [];
    }
}
