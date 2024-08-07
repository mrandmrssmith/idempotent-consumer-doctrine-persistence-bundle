<?php

namespace MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory;

use MrAndMrsSmith\IdempotentConsumerBundle\Message\MessageStatus;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity\MessageStatus as MessageStatusEntity;

class MessageStatusEntityFactory
{
    public function createFromMessageStatus(MessageStatus $messageStatus): MessageStatusEntity
    {
        $messageStatusEntity = new MessageStatusEntity();
        $messageStatusEntity->setMessageStatus($messageStatus->getStatus());
        $messageStatusEntity->setMessageIdempotentKey($messageStatus->getIdempotentKey());
        $messageStatusEntity->setName($messageStatus->getMessageName());

        return $messageStatusEntity;
    }

    public function applyValuesFromMessageStatus(
        MessageStatus $messageStatus,
        MessageStatusEntity $messageStatusEntity
    ): void {
        $messageStatusEntity->setMessageStatus($messageStatus->getStatus());
        $messageStatusEntity->setMessageIdempotentKey($messageStatus->getIdempotentKey());
        $messageStatusEntity->setName($messageStatus->getMessageName());
    }
}
