<?php

namespace Fico7489\PersistenceBundle\DoctrineListener;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\PersistenceBundle\Event\ChangedEntityEvent;
use FHPlatform\PersistenceBundle\Tests\TestCase;
use FHPlatform\PersistenceBundle\Tests\Util\Entity\Role;
use FHPlatform\PersistenceBundle\Tests\Util\Entity\User;

class DoctrineListenerMoreDifferentTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setNameString('name_string');
        $this->entityManager->persist($user);

        $user2 = new User();
        $user2->setNameString('name_string2');
        $this->entityManager->persist($user2);

        $role = new Role();
        $role->setNameString('name_string');
        $this->entityManager->persist($role);

        // test persist
        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getEntities();
        $this->assertCount(3, $entities);

        list($key, $key2, $key3) = array_keys($entities);

        /** @var ChangedEntityEvent $value */
        $value = $entities[$key];
        $value2 = $entities[$key2];
        $value3 = $entities[$key3];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(Role::class.'_1', $key3);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());
        $this->assertEquals(1, $value3->getIdentifier());

        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $value->getType());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $value2->getType());
        $this->assertEquals(ChangedEntityEvent::TYPE_CREATE, $value3->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
        $this->assertEquals(Role::class, $value3->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());
        $this->assertEquals(['id'], $value2->getChangedFields());
        $this->assertEquals(['id'], $value3->getChangedFields());

        // test update
        $user->setNameString('name_string_1');
        $user->setNameText('name_text_1');
        $user2->setNameText('name_text2_1');
        $role->setNameString('name_text2_3');
        $this->entityManager->persist($user);
        $this->entityManager->persist($user2);
        $this->entityManager->persist($role);

        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getEntities();
        $this->assertCount(3, $entities);

        list($key, $key2, $key3) = array_keys($entities);

        /** @var ChangedEntityEvent $value */
        $value = $entities[$key];
        $value2 = $entities[$key2];
        $value3 = $entities[$key3];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(Role::class.'_1', $key3);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());
        $this->assertEquals(1, $value3->getIdentifier());

        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $value->getType());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $value2->getType());
        $this->assertEquals(ChangedEntityEvent::TYPE_UPDATE, $value3->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
        $this->assertEquals(Role::class, $value3->getClassName());
        $this->assertEquals(['nameString', 'nameText'], $value->getChangedFields());
        $this->assertEquals(['nameText'], $value2->getChangedFields());
        $this->assertEquals(['nameString'], $value3->getChangedFields());

        // test delete
        $this->entityManager->remove($user);
        $this->entityManager->remove($user2);
        $this->entityManager->remove($role);

        $this->eventsClear(ChangedEntitiesEvent::class);
        $this->assertCount(0, $this->eventsGet(ChangedEntitiesEvent::class));
        $this->entityManager->flush();
        $this->assertCount(1, $this->eventsGet(ChangedEntitiesEvent::class));
        /** @var ChangedEntitiesEvent $event */
        $event = $this->eventsGet(ChangedEntitiesEvent::class)[0];
        $entities = $event->getEntities();
        $this->assertCount(3, $entities);

        list($key, $key2, $key3) = array_keys($entities);

        /** @var ChangedEntityEvent $value */
        $value = $entities[$key];
        $value2 = $entities[$key2];
        $value3 = $entities[$key3];
        $this->assertEquals(User::class.'_1', $key);
        $this->assertEquals(User::class.'_2', $key2);
        $this->assertEquals(Role::class.'_1', $key3);
        $this->assertEquals(1, $value->getIdentifier());
        $this->assertEquals(2, $value2->getIdentifier());
        $this->assertEquals(1, $value3->getIdentifier());

        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $value->getType());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $value2->getType());
        $this->assertEquals(ChangedEntityEvent::TYPE_DELETE, $value3->getType());

        $this->assertEquals(User::class, $value->getClassName());
        $this->assertEquals(User::class, $value2->getClassName());
        $this->assertEquals(Role::class, $value3->getClassName());
        $this->assertEquals(['id'], $value->getChangedFields());
        $this->assertEquals(['id'], $value2->getChangedFields());
        $this->assertEquals(['id'], $value3->getChangedFields());
    }
}
