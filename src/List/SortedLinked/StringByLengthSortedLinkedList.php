<?php

namespace List\SortedLinked;

use List\LinkedListItem;
use List\ValueComparison\Strategy\IntegerAscStrategy;
use List\ValueComparison\Strategy\StringByLengthStrategy;

/**
 * @extends \List\SortedLinked\AbstractSortedLinkedList<string>
 */
final class StringByLengthSortedLinkedList extends AbstractSortedLinkedList
{
    public function __construct(
        LinkedListItem $firstItem = null,
    ) {
        parent::__construct(
            new StringByLengthStrategy(),
            $firstItem,
        );

        array_reduce(
            $this->toValueArray(),
            function (?string $previousValue, string $value) {
                if ($previousValue !== null) {
                    assert(strlen($previousValue) <= strlen($value));
                }
                return $value;
            },
        );
    }
}
