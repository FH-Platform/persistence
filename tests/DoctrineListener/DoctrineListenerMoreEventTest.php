<?php

namespace Fico7489\PersistenceBundle\DoctrineListener;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\PersistenceBundle\Tests\TestCase;
use FHPlatform\PersistenceBundle\Tests\Util\Entity\Role;
use FHPlatform\PersistenceBundle\Tests\Util\Entity\User;

class DoctrineListenerMoreEventTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setNameString('name_string');
        $this->entityManager->persist($user);

        $user2 = new User();
        $user2->setNameString('name_string2');
        $this->entityManager->persist($user2);

        $this->entityManager->flush();

        $user->setNameString('name_string2_2');
        $this->entityManager->persist($user);

        $this->entityManager->remove($user2);
        $this->entityManager->flush();

        $role = new Role();
        $role->setNameString('name_string2');
        $this->entityManager->persist($role);
        $this->entityManager->flush();

        $events = $this->eventsGet(ChangedEntitiesEvent::class);
        $this->assertCount(3, $events);

        /** @var ChangedEntitiesEvent $event */
        /** @var ChangedEntitiesEvent $event2 */
        /** @var ChangedEntitiesEvent $event3 */
        list($event, $event2, $event3) = $this->eventsGet(ChangedEntitiesEvent::class);

        $this->assertCount(2, $event->getEntities());
        $this->assertCount(2, $event2->getEntities());
        $this->assertCount(1, $event3->getEntities());
    }
}
