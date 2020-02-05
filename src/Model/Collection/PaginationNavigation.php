<?php

namespace App\Model\Collection;

use Symfony\Component\Serializer\Annotation\SerializedName;

class PaginationNavigation
{
    /**
     * @var string
     * @SerializedName("@id")
     */
    private $uri;

    /**
     * @var string
     * @SerializedName("@type")
     */
    private static $type = 'PartialCollectionView';

    /** @var string */
    private $first;

    /** @var string */
    private $previous;

    /** @var string */
    private $next;

    /** @var string */
    private $last;

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): void
    {
        $this->uri = $uri;
    }

    public function getType(): string
    {
        return self::$type;
    }

    public function getFirst(): ?string
    {
        return $this->first;
    }

    public function setFirst(?string $first): void
    {
        $this->first = $first;
    }

    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    public function setPrevious(?string $previous): void
    {
        $this->previous = $previous;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setNext(?string $next): void
    {
        $this->next = $next;
    }

    public function getLast(): ?string
    {
        return $this->last;
    }

    public function setLast(?string $last): void
    {
        $this->last = $last;
    }
}
