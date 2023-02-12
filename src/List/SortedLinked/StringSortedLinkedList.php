<?php

namespace List\SortedLinked;

use List\LinkedListItem;
use List\ValueComparison\ValueComparisonStrategyInterface;

readonly class StringSortedLinkedList
{

	public function __construct(
		private ValueComparisonStrategyInterface $comparisonStrategy,
		private ?LinkedListItem $firstItem,
	)
	{
	}

	public static function createEmpty(ValueComparisonStrategyInterface $comparisonStrategy): self
	{
		return new self($comparisonStrategy, null);
	}

	/**
	 * @return array<string>
	 */
	public function toValueArray(): array
	{
		$values = [];

		foreach ($this->iterate() as $value => $item) {
			$values[] = $value;
		};

		return $values;
	}

	public function add(string $value): self
	{
		if ($this->firstItem === null) {
			return new self(
				$this->comparisonStrategy,
				new LinkedListItem($value, null)
			);
		}

		$isLessOrEqualToFirstItem = $this->comparisonStrategy->compareValues($value, $this->firstItem->getValue())->isLessOrEqual();

		if ($isLessOrEqualToFirstItem) {
			return new self(
				$this->comparisonStrategy,
				new LinkedListItem($value, $this->firstItem)
			);
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

	public function remove(string $value): self
	{
		if ($this->hasValue($value) === false) {
			throw new \InvalidArgumentException('list does not have value ' . $value);
		}

		if ($this->firstItem !== null && $value === $this->firstItem->getValue()) {
			return new self(
				$this->comparisonStrategy,
				$this->firstItem->getNextItem()
			);
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

	public function hasValue(string $value): bool
	{
		return in_array($value, $this->toValueArray());
	}

	/**
	 * @return \Generator<string, LinkedListItem>
	 */
	private function iterate(): \Generator
	{
		if ($this->firstItem !== null) {
			foreach ($this->firstItem->yieldWithNext() as $value => $item) {
				assert(is_string($value));
				
				yield $value => $item;
			}
		}
	}

}