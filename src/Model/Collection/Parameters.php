<?php

namespace App\Model\Collection;

use Symfony\Component\HttpFoundation\Request;

class Parameters
{
    private ?int $page = 1;
    private ?int $pageSize = 20;
    private ?string $search = '*';

    public static function fromRequest(Request $request): self
    {
        $self = new static();

        $self->setPage($request->request->get('page'));
        $self->setPageSize($request->request->get('page-size'));

        return $self;
    }

    public function getPage(): int
    {
        return max(1, $this->page);
    }

    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    public function getPageSize(): int
    {
        return max(1, $this->pageSize);
    }

    public function setPageSize(?int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }

    public function getOffset(): int
    {
        $offset = 0;
        if ($this->page > 1) {
            $offset = ($this->page - 1) * $this->pageSize;
        }

        return $offset;
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    public function hasSearch(): bool {
        return $this->search !== '*';
    }
}