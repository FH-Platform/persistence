<?php

namespace Fico7489\PersistenceBundle\DoctrineListener;

use FHPlatform\PersistenceBundle\Event\ChangedEntitiesEvent;
use FHPlatform\PersistenceBundle\Tests\TestCase;
use FHPlatform\PersistenceBundle\Tests\Util\Entity\User;

class DoctrineListenerTest extends TestCase
{
    public function testSomething(): void
    {
        $this->eventsStartListen(ChangedEntitiesEvent::class);

        $user = new User();
        $user->setNameString('name_string');
        $this->entityManager->persist($user);
        $user->setNameString('name_string2');
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->assertEquals(1, 1);
    }
}
