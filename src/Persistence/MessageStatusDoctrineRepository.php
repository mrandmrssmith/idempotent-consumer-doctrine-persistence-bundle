<?php

namespace MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Persistence;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MrAndMrsSmith\IdempotentConsumerBundle\Message\MessageStatus;
use MrAndMrsSmith\IdempotentConsumerBundle\Persistence\MessageStatusPersister;
use MrAndMrsSmith\IdempotentConsumerBundle\Persistence\MessageStatusRetriever;
use MrAndMrsSmith\IdempotentConsumerBundle\Persistence\MessageStatusUpdater;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity\MessageStatus as MessageStatusEntity;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Exception\MessageStatusDoesNotExistException;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory\MessageStatusEntityFactory;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory\MessageStatusFactory;

/**
 * @template T of MessageStatusEntity
 * @extends ServiceEntityRepository<T>
 */
class MessageStatusDoctrineRepository extends ServiceEntityRepository implements
    MessageStatusPersister,
    MessageStatusRetriever,
    MessageStatusUpdater
{
    /** @var MessageStatusEntityFactory */
    private $messageStatusEntityFactory;

    /** @var MessageStatusFactory */
    private $messageStatusFactory;

    public function __construct(
        ManagerRegistry $registry,
        MessageStatusEntityFactory $messageStatusEntityFactory,
        MessageStatusFactory $messageStatusFactory
    ) {
        $this->messageStatusEntityFactory = $messageStatusEntityFactory;
        $this->messageStatusFactory = $messageStatusFactory;
        parent::__construct($registry, MessageStatusEntity::class);
    }

    public function persist(MessageStatus $messageStatus): void
    {
        $messageStatusEntity = $this->messageStatusEntityFactory->createFromMessageStatus($messageStatus);
        $this->getEntityManager()->persist($messageStatusEntity);

        $this->getEntityManager()->flush();
    }

    public function retrieve(string $key): ?MessageStatus
    {
        $messageStatusEntity = $this->findOneBy(['messageIdempotentKey' => $key]);
        if (!$messageStatusEntity instanceof MessageStatusEntity) {
            return null;
        }


        return $this->messageStatusFactory->createMessageStatusEntity($messageStatusEntity);
    }

    public function update(MessageStatus $messageStatus): void
    {
        $messageStatusEntity = $this->findOneBy(['messageIdempotentKey' => $messageStatus->getIdempotentKey()]);
        if (!$messageStatusEntity instanceof MessageStatusEntity) {
            throw new MessageStatusDoesNotExistException($messageStatus->getIdempotentKey());
        }
        $this->messageStatusEntityFactory->applyValuesFromMessageStatus(
            $messageStatus,
            $messageStatusEntity
        );

        $this->getEntityManager()->flush();
    }
}
