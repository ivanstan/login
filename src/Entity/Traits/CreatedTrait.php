<?php

namespace App\Entity\Traits;

use Symfony\Component\Serializer\Annotation\Groups;

trait CreatedTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"user_read"})
     */
    private \DateTime $created;

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }
}
