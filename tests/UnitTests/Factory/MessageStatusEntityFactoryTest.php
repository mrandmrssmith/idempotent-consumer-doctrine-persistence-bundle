<?php

namespace Tests\UnitTests\Factory;

use MrAndMrsSmith\IdempotentConsumerBundle\Message\MessageStatus;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity\MessageStatus as MessageStatusEntity;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory\MessageStatusEntityFactory;
use PHPUnit\Framework\TestCase;

class MessageStatusEntityFactoryTest extends TestCase
{
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new MessageStatusEntityFactory();
    }

    public function testCreateFromMessageStatus(): void
    {
        $status = 'finished';
        $idempotentKey = 'key';
        $name = \stdClass::class;
        $messageStatus = new MessageStatus($status, $idempotentKey, $name);

        $messageStatusEntity = $this->factory->createFromMessageStatus($messageStatus);

        $this->assertEquals($status, $messageStatusEntity->getMessageStatus());
        $this->assertEquals($idempotentKey, $messageStatusEntity->getMessageIdempotentKey());
        $this->assertEquals($name, $messageStatusEntity->getName());
    }

    public function testApplyValuesFromMessageStatus(): void
    {
        $status = 'finished';
        $idempotentKey = 'key';
        $name = \stdClass::class;
        $messageStatus = new MessageStatus($status, $idempotentKey, $name);
        $messageStatusEntity = $this->createMock(MessageStatusEntity::class);

        $messageStatusEntity
            ->expects($this->once())
            ->method('setMessageStatus')
            ->with($status);
        $messageStatusEntity
            ->expects($this->once())
            ->method('setMessageIdempotentKey')
            ->with($idempotentKey);
        $messageStatusEntity
            ->expects($this->once())
            ->method('setName')
            ->with($name);

        $this->factory->applyValuesFromMessageStatus($messageStatus, $messageStatusEntity);
    }
}
