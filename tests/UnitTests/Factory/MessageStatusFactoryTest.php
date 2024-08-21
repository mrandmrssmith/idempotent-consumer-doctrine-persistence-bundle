<?php

namespace Tests\UnitTests\Factory;

use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory\MessageStatusFactory;
use PHPUnit\Framework\TestCase;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity\MessageStatus as MessageStatusEntity;

class MessageStatusFactoryTest extends TestCase
{
    /** @var MessageStatusFactory */
    private $messageStatusFactory;

    public function setUp(): void
    {
        $this->messageStatusFactory = new MessageStatusFactory();
    }

    public function testCreateMessageStatusEntity(): void
    {
        $status = 'finished';
        $idempotentKey = 'key';
        $name = \stdClass::class;
        $messageStatusEntity = $this->createMock(MessageStatusEntity::class);

        $messageStatusEntity
            ->expects($this->once())
            ->method('getMessageStatus')
            ->willReturn($status);
        $messageStatusEntity
            ->expects($this->once())
            ->method('getMessageIdempotentKey')
            ->willReturn($idempotentKey);
        $messageStatusEntity
            ->expects($this->once())
            ->method('getName')
            ->willReturn($name);

        $messageStatus = $this->messageStatusFactory->createMessageStatusEntity($messageStatusEntity);

        $this->assertEquals($status, $messageStatus->getStatus());
        $this->assertEquals($idempotentKey, $messageStatus->getIdempotentKey());
        $this->assertEquals($name, $messageStatus->getMessageName());
    }
}
