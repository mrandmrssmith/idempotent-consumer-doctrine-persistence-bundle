<?php

namespace MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Exception;

class MessageStatusDoesNotExistException extends \Exception
{
    public function __construct(string $messageStatusKey)
    {
        parent::__construct(sprintf('Message status with key %s does not exist', $messageStatusKey));
    }
}
