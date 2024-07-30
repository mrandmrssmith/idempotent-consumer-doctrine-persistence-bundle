<?php

namespace MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Persistence\MessageStatusDoctrineRepository")
 */
class MessageStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $messageStatus;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $messageIdempotentKey;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    private $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getMessageStatus(): string
    {
        return $this->messageStatus;
    }

    public function setMessageStatus(string $messageStatus): void
    {
        $this->messageStatus = $messageStatus;
    }

    public function getMessageIdempotentKey(): string
    {
        return $this->messageIdempotentKey;
    }

    public function setMessageIdempotentKey(string $messageIdempotentKey): void
    {
        $this->messageIdempotentKey = $messageIdempotentKey;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
