<?php

declare(strict_types=1);

namespace EnjoysCMS\Module\Sitemap;

use DateTimeInterface;

final class Url
{
    public function __construct(
        private readonly string $loc,
        private readonly ?DateTimeInterface $modified = null,
        private readonly ?string $frequency = null,
        private readonly string|float|null $priority = null
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

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function getPriority(): string|float|null
    {
        return $this->priority;
    }

}
