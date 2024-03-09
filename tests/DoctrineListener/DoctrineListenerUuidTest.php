<?php

namespace Fico7489\PersistenceBundle\DoctrineListener;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\PersistenceBundle\Event\ChangedEntityEvent;
use FHPlatform\PersistenceBundle\Tests\TestCase;
use FHPlatform\PersistenceBundle\Tests\Util\Entity\UserUuid;

class DoctrineListenerUuidTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new UserUuid();
        $user->setNameString('name_string');
        $this->entityManager->persist($user);

        // test persist
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $id = $user->getId();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntityEvent $value */
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());

        // test update
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $user->setNameString('name_string2');
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntityEvent $value */
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
        $this->assertEquals(['nameString'], $value->getChangedFields());

        // test remove
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getEntities();
        $this->assertCount(1, $entities);
        $key = array_key_first($entities);
        /** @var ChangedEntityEvent $value */
        $value = $entities[$key];
        $this->assertEquals(UserUuid::class.'_'.$id, $key);
        $this->assertEquals($id, $value->getIdentifier());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $value->getType());
        $this->assertEquals(UserUuid::class, $value->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());
    }
}
