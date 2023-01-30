<?php

namespace App\Models;

class DocumentSearch
{
    /**
     * @var string | null
     */
    private ?string $query = null;

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|null $query
     */
    public function setQuery(?string $query): void
    {
        $this->query = $query;
    }

}