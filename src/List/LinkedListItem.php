<?php

namespace List;

use Generator;

class LinkedListItem
{
    public function __construct(
        private readonly int|string $value,
        private ?self $nextItem,
    ) {
        //TODO: add validation callback, fix tests
    }

    public function setNextItem(?LinkedListItem $nextItem): void
    {
        $this->nextItem = $nextItem;
    }

    /**
     * @return \Generator<int|string, LinkedListItem>
     */
    public function yieldWithNext(): Generator
    {
        yield $this->value => $this;

        if ($this->nextItem !== null) {
            foreach ($this->nextItem->yieldWithNext() as $value => $next) {
                yield $value => $next;
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
