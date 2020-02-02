<?php

namespace App\Entity\Traits;

use App\Service\DateTimeService;

trait TimestampableTrait
{
    use CreatedTrait;
    use UpdatedTrait;

    /**
     * @ORM\PrePersist
     */
    public function setCreated(): void
    {
        $this->created = DateTimeService::getCurrentUTC();
        $this->updated = DateTimeService::getCurrentUTC();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdated(): void
    {
        $this->updated = DateTimeService::getCurrentUTC();
    }
}