<?php

namespace UnitTests\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\UnitOfWork;
use MrAndMrsSmith\IdempotentConsumerBundle\Message\MessageStatus;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Exception\MessageStatusDoesNotExistException;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory\MessageStatusEntityFactory;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory\MessageStatusFactory;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Persistence\MessageStatusDoctrineRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ManagerRegistry;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity\MessageStatus as MessageStatusEntity;

class MessageStatusDoctrineRepositoryTest extends TestCase
{
    private const IDEMPOTENT_KEY = 'key';

    /** @var MessageStatusEntityFactory|MockObject */
    private $messageStatusEntityFactory;

    /** @var MessageStatusFactory|MockObject */
    private $messageStatusFactory;

    /** @var ManagerRegistry|MockObject */
    private $managerRegister;

    /** @var EntityManagerInterface|MockObject */
    private $entityManager;

    /** @var MessageStatusDoctrineRepository */
    private $repository;

    public function setUp(): void
    {
        $this->messageStatusEntityFactory = $this->createMock(MessageStatusEntityFactory::class);
        $this->messageStatusFactory = $this->createMock(MessageStatusFactory::class);
        $this->managerRegister = $this->createMock(ManagerRegistry::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->managerRegister
            ->method('getManagerForClass')
            ->willReturn($this->entityManager);
        $this->entityManager
            ->method('getClassMetadata')
            ->willReturn($this->createMock(ClassMetadata::class));

        $this->repository = new MessageStatusDoctrineRepository(
            $this->managerRegister,
            $this->messageStatusEntityFactory,
            $this->messageStatusFactory
        );
    }

    public function testPersist(): void
    {
        $messageStatus = $this->createMock(MessageStatus::class);
        $messageStatusEntity = $this->createMock(MessageStatusEntity::class);

        $this->messageStatusEntityFactory
            ->expects($this->once())
            ->method('createFromMessageStatus')
            ->willReturn($messageStatusEntity);

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($messageStatusEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository->persist($messageStatus);
    }

    public function testRetrieve(): void
    {
        $messageStatus = $this->createMock(MessageStatus::class);
        $messageStatusEntity = $this->createMock(MessageStatusEntity::class);

        $messageStatus
            ->method('getIdempotentKey')
            ->willReturn(self::IDEMPOTENT_KEY);
        $this->mockGetMessageStatusEntity($messageStatusEntity);
        $this->messageStatusFactory
            ->expects($this->once())
            ->method('createMessageStatusEntity')
            ->with($messageStatusEntity)
            ->willReturn($messageStatus);

        $this->assertEquals($messageStatus, $this->repository->retrieve(self::IDEMPOTENT_KEY));
    }

    public function testRetrieveReturnsNull(): void
    {
        $this->mockGetMessageStatusEntity(null);

        $this->assertNull($this->repository->retrieve(self::IDEMPOTENT_KEY));
    }

    public function testUpdate(): void
    {
        $messageStatus = $this->createMock(MessageStatus::class);
        $messageStatusEntity = $this->createMock(MessageStatusEntity::class);

        $messageStatus
            ->expects($this->once())
            ->method('getIdempotentKey')
            ->willReturn(self::IDEMPOTENT_KEY);
        $this->mockGetMessageStatusEntity($messageStatusEntity);
        $this->messageStatusEntityFactory
            ->expects($this->once())
            ->method('applyValuesFromMessageStatus')
            ->with($messageStatus, $messageStatusEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository->update($messageStatus);
    }

    public function testUpdateWillThrowExceptionWhenMessageWithKeyDoesNoteExist(): void
    {
        $messageStatus = $this->createMock(MessageStatus::class);

        $messageStatus
            ->expects($this->exactly(2))
            ->method('getIdempotentKey')
            ->willReturn(self::IDEMPOTENT_KEY);
        $this->mockGetMessageStatusEntity(null);

        $this->expectException(MessageStatusDoesNotExistException::class);
        $this->repository->update($messageStatus);
    }

    private function mockGetMessageStatusEntity(?MessageStatusEntity $messageStatusEntity): void
    {
        $uow = $this->createMock(UnitOfWork::class);
        $entityPersister = $this->createMock(EntityPersister::class);

        $this->entityManager
            ->method('getUnitOfWork')
            ->willReturn($uow);
        $uow
            ->method('getEntityPersister')
            ->willReturn($entityPersister);
        $entityPersister
            ->method('load')
            ->willReturn($messageStatusEntity);
    }
}
