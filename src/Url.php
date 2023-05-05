<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\Sitemap;

use DateTimeInterface;

final class Url
{
    public function __construct(
        private string $loc,
        private ?DateTimeInterface $modified = null,
        private $frequency = null,
        private $priority = null
    ) {
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getModified(): ?int
    {
        return $this->modified?->getTimestamp();
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function getPriority()
    {
        return $this->priority;
    }

}
