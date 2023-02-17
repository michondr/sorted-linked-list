<?php

declare(strict_types=1);

namespace List;

use Generator;

class LinkedListItem
{
    public function __construct(
        private readonly int|string $value,
        private ?self $nextItem,
    ) {
    }

    public function setNextItem(?LinkedListItem $nextItem): void
    {
        $this->nextItem = $nextItem;
    }

    /**
     * @return \Generator<int|string>
     */
    public function yieldWithNext(): Generator
    {
        yield $this->value;

        if ($this->nextItem !== null) {
            foreach ($this->nextItem->yieldWithNext() as $value) {
                yield $value;
            }
        }
    }

    public function getNextItem(): ?LinkedListItem
    {
        return $this->nextItem;
    }

    public function getValue(): int|string
    {
        return $this->value;
    }
}
