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

	public static function createEmpty(): self
	{
		return new self(null);
	}

	/**
	 * @return array<int>
	 */
	public function toValueArray(): array
	{
		$values = [];

		foreach ($this->iterate() as $value => $item) {
			$values[] = $value;
		};

		return $values;
	}

	public function add(int $value): self
	{
		if ($this->firstItem === null) {
			return new self(
				new LinkedListItem($value, null)
			);
		}

		if ($value <= $this->firstItem->getValue()) {
			return new self(
				new LinkedListItem($value, $this->firstItem)
			);
		}


		$current = $this->firstItem;
		while ($current !== null) {

			$isLast = $current->getNextItem() === null;
			$isMoreThanCurrent = $value > $current->getValue();
			$isLessThanNext = $current->getNextItem() !== null && $value <= $current->getNextItem()->getValue();

			if ($isMoreThanCurrent && ($isLessThanNext || $isLast)) {
				$current->setNextItem(
					new LinkedListItem(
						$value,
						$current->getNextItem()
					),
				);
			}

			$current = $current->getNextItem();
		}


		return $this;
	}

	public function hasValue(int $value): bool
	{
		return in_array($value, $this->toValueArray());
	}

	/**
	 * @return \Generator<int, LinkedListItem>
	 */
	private function iterate(): \Generator
	{
		if ($this->firstItem !== null) {
			foreach ($this->firstItem->yieldWithNext() as $value => $item) {
				yield $value => $item;
			}
		}
	}

}