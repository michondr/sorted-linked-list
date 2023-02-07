<?php

namespace List\SortedLinked;

use List\LinkedListItem;

readonly class IntSortedLinkedList
{
	public function __construct(
		private ?LinkedListItem $firstItem,
	)
	{
	}

	/**
	 * @return \Generator<int, LinkedListItem>
	 */
	public function iterate(): \Generator
	{
		if ($this->firstItem !== null) {
			foreach ($this->firstItem->yieldWithNext() as $value => $item) {
				yield $value => $item;
			}

		}
	}

}