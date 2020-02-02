<?php

namespace App\Entity\Traits;

use Symfony\Component\Serializer\Annotation\Groups;

trait UpdatedTrait
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"user"})
     */
    private \DateTime $updated;

    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    public function setUpdated(\DateTime $updated): void
    {
        $this->updated = $updated;
    }
}
