<?php

namespace MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Factory;

use MrAndMrsSmith\IdempotentConsumerBundle\Message\MessageStatus;
use MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity\MessageStatus as MessageStatusEntity;

class MessageStatusFactory
{
    public function createMessageStatusEntity(MessageStatusEntity $messageStatus): MessageStatus
    {
        return new MessageStatus(
            $messageStatus->getMessageStatus(),
            $messageStatus->getMessageIdempotentKey(),
            $messageStatus->getName()
        );
    }
}
