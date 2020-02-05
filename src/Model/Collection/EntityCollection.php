<?php

namespace App\Model\Collection;

class EntityCollection
{
    private array $collection = [];

    private int $total = 0;

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function setCollection(array $collection): void
    {
        $this->collection = $collection;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
    }
}