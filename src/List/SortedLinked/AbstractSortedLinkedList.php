<?php

namespace List\SortedLinked;

use List\LinkedListItem;
use List\ValueComparison\ValueComparisonStrategyInterface;

/**
 * @template ListValueType of string|int
 */
abstract class AbstractSortedLinkedList
{

	public function __construct(
		private readonly ValueComparisonStrategyInterface $comparisonStrategy,
		private ?LinkedListItem $firstItem,
	)
	{
	}

	/**
	 * @return array<ListValueType>
	 */
	public function toValueArray(): array
	{
		$values = [];

		foreach ($this->iterate() as $value => $item) {
			$values[] = $value;
		};

		return $values;
	}

	/**
	 * @param ListValueType $value
	 */
	public function add($value): static
	{
		if ($this->firstItem === null) {
			$this->firstItem = new LinkedListItem($value, null);
			return $this;
		}

		$isLessOrEqualToFirstItem = $this->comparisonStrategy->compareValues($value, $this->firstItem->getValue())->isLessOrEqual();

		if ($isLessOrEqualToFirstItem) {
			$this->firstItem = new LinkedListItem($value, $this->firstItem);
			return $this;
		}


		$current = $this->firstItem;
		while ($current !== null) {

			$isLast = $current->getNextItem() === null;
			$isMoreThanCurrent = $this->comparisonStrategy->compareValues($value, $current->getValue())->isMore();
			$isLessOrEqualToNext = $current->getNextItem() !== null && $this->comparisonStrategy->compareValues($value, $current->getNextItem()->getValue())->isLessOrEqual();

			if ($isMoreThanCurrent && ($isLessOrEqualToNext || $isLast)) {
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

	/**
	 * @param ListValueType $value
	 */
	public function remove($value): static
	{
		if ($this->hasValue($value) === false) {
			throw new \InvalidArgumentException('list does not have value ' . $value);
		}

		if ($this->firstItem !== null && $value === $this->firstItem->getValue()) {
			$this->firstItem = $this->firstItem->getNextItem();
			return $this;
		}

		$previous = null;
		$current = $this->firstItem;
		while ($current !== null) {

			if ($value === $current->getValue()) {
				assert($previous !== null);

				$previous->setNextItem($current->getNextItem());
			}

			$previous = $current;
			$current = $current->getNextItem();
		}

		return $this;
	}

	/**
	 * @param ListValueType $value
	 */
	public function hasValue($value): bool
	{
		return in_array($value, $this->toValueArray());
	}

	/**
	 * @return \Generator<ListValueType, LinkedListItem>
	 */
	private function iterate(): \Generator
	{
		if ($this->firstItem !== null) {
			foreach ($this->firstItem->yieldWithNext() as $value => $item) {
				assert(is_int($value));

				yield $value => $item;
			}
		}
	}

}