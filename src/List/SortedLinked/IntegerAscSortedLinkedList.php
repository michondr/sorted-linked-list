<?php

declare(strict_types=1);

namespace List\SortedLinked;

use List\LinkedListItem;
use List\ValueComparison\Strategy\IntegerAscStrategy;

/**
 * @extends \List\SortedLinked\AbstractSortedLinkedList<int>
 */
final class IntegerAscSortedLinkedList extends AbstractSortedLinkedList
{
    public function __construct(
        LinkedListItem $firstItem = null,
    ) {
        parent::__construct(
            new IntegerAscStrategy(),
            $firstItem,
        );

        array_reduce(
            $this->toValueArray(),
            function (?int $previousValue, int $value) {
                if ($previousValue !== null) {
                    assert($previousValue > $value);
                }
                return $value;
            },
        );
    }
}
