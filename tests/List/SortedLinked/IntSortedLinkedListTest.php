<?php

namespace List\SortedLinked;


use Generator;
use List\LinkedListItem;
use List\ValueComparison\Strategy\AscendingIntegerStrategy;
use List\ValueComparison\Strategy\DescendingIntegerStrategy;
use List\ValueComparison\ValueComparisonStrategyInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IntSortedLinkedListTest extends TestCase
{

	public function toValueDataProvider(): Generator
	{
		yield 'empty list' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				null
			),
			'expectedResult' => [],
		];
		yield 'ascending list' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					-3,
					new LinkedListItem(
						0,
						new LinkedListItem(
							5,
							null
						)
					)
				)
			),
			'expectedResult' => [-3, 0, 5]
		];
		yield 'descending list' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					10,
					new LinkedListItem(
						3,
						new LinkedListItem(
							-42,
							new LinkedListItem(
								-100,
								null
							)
						)
					)
				)
			),
			'expectedResult' => [10, 3, -42, -100]
		];
		yield 'list with duplicate values in descending order' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					10,
					new LinkedListItem(
						3,
						new LinkedListItem(
							-42,
							new LinkedListItem(
								-42,
								new LinkedListItem(
									-100,
									null
								)
							)
						)
					)
				)
			),
			'expectedResult' => [10, 3, -42, -42, -100]
		];
	}

	/**
	 * @dataProvider toValueDataProvider
	 * @param array<int> $expectedResult
	 */
	public function testToValueArray(IntSortedLinkedList $list, array $expectedResult): void
	{
		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function addBeforeDataProvider(): Generator
	{
		yield 'ascending' => [
			'strategy' => new AscendingIntegerStrategy(),
			'expectedResult' => [-3, 1]
		];
		yield 'descending' => [
			'strategy' => new DescendingIntegerStrategy(),
			'expectedResult' => [1, -3]
		];
	}

	/**
	 * @dataProvider addBeforeDataProvider
	 * @param array<int> $expectedResult
	 */
	public function testAddBefore(
		ValueComparisonStrategyInterface $strategy,
		array $expectedResult,
	): void
	{
		$list = IntSortedLinkedList::createEmpty($strategy)
			->add(1)
			->add(-3);

		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function addAfterDataProvider(): Generator
	{
		yield 'ascending' => [
			'strategy' => new AscendingIntegerStrategy(),
			'expectedResult' => [1, 5]
		];
		yield 'descending' => [
			'strategy' => new DescendingIntegerStrategy(),
			'expectedResult' => [5, 1]
		];
	}

	/**
	 * @dataProvider addAfterDataProvider
	 * @param array<int> $expectedResult
	 */
	public function testAddAfter(
		ValueComparisonStrategyInterface $strategy,
		array $expectedResult,
	): void
	{
		$list = IntSortedLinkedList::createEmpty($strategy)
			->add(1)
			->add(5);

		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function addDuplicateDataProvider(): Generator
	{
		yield 'ascending' => [
			'strategy' => new AscendingIntegerStrategy(),
			'expectedResult' => [1, 1]
		];
		yield 'descending' => [
			'strategy' => new DescendingIntegerStrategy(),
			'expectedResult' => [1, 1]
		];
	}

	/**
	 * @dataProvider addDuplicateDataProvider
	 * @param array<int> $expectedResult
	 */
	public function testAddDuplicate(
		ValueComparisonStrategyInterface $strategy,
		array $expectedResult,
	): void
	{
		$list = IntSortedLinkedList::createEmpty($strategy)
			->add(1)
			->add(1);

		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function testAddGradually(): void
	{
		$list = IntSortedLinkedList::createEmpty(new AscendingIntegerStrategy());

		$list = $list->add(0);

		Assert::assertSame(
			[0],
			$list->toValueArray(),
		);

		$list = $list->add(1);

		Assert::assertSame(
			[0, 1],
			$list->toValueArray(),
		);

		$list = $list->add(1);

		Assert::assertSame(
			[0, 1, 1],
			$list->toValueArray(),
		);

		$list = $list->add(2);

		Assert::assertSame(
			[0, 1, 1, 2],
			$list->toValueArray(),
		);

		$list = $list->add(-2);

		Assert::assertSame(
			[-2, 0, 1, 1, 2],
			$list->toValueArray(),
		);

		$list = $list->add(-2);

		Assert::assertSame(
			[-2, -2, 0, 1, 1, 2],
			$list->toValueArray(),
		);

	}

	public function testAddMultiple(): void
	{
		$list = IntSortedLinkedList::createEmpty(new AscendingIntegerStrategy())
			->add(1)
			->add(1)
			->add(0)
			->add(-100)
			->add(20)
			->add(11)
			->add(11)
			->add(11)
			->add(5);

		Assert::assertSame(
			[-100, 0, 1, 1, 5, 11, 11, 11, 20],
			$list->toValueArray(),
		);
	}

	public function testHasValue(): void
	{
		$list = IntSortedLinkedList::createEmpty(new AscendingIntegerStrategy())
			->add(1)
			->add(-100)
			->add(30);

		Assert::assertTrue($list->hasValue(1));
		Assert::assertTrue($list->hasValue(-100));
		Assert::assertTrue($list->hasValue(30));

		Assert::assertFalse($list->hasValue(-30));
		Assert::assertFalse($list->hasValue(100));
		Assert::assertFalse($list->hasValue(0));
		Assert::assertFalse($list->hasValue(300));
	}

	public function removeDataProvider(): Generator
	{
		yield 'list with single item' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					3,
					null
				)
			),
			'valueToRemove' => 3,
			'expectedResult' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				null
			)
		];

		yield 'remove from the start' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					-3,
					new LinkedListItem(
						0,
						new LinkedListItem(
							5,
							null
						)
					)
				)
			),
			'valueToRemove' => -3,
			'expectedResult' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					0,
					new LinkedListItem(
						5,
						null
					)
				)
			)
		];
		yield 'remove from the middle' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					-3,
					new LinkedListItem(
						0,
						new LinkedListItem(
							5,
							null
						)
					)
				)
			),
			'valueToRemove' => 0,
			'expectedResult' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					-3,
					new LinkedListItem(
						5,
						null
					)
				)
			)
		];
		yield 'remove from the end' => [
			'list' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					-3,
					new LinkedListItem(
						0,
						new LinkedListItem(
							5,
							null
						)
					)
				)
			),
			'valueToRemove' => 5,
			'expectedResult' => new IntSortedLinkedList(
				new AscendingIntegerStrategy(),
				new LinkedListItem(
					-3,
					new LinkedListItem(
						0,
						null
					)
				)
			)
		];
	}

	/**
	 * @dataProvider removeDataProvider
	 */
	public function testRemove(IntSortedLinkedList $list, int $valueToRemove, IntSortedLinkedList $expectedResult): void
	{
		Assert::assertNotEquals(
			$list,
			$expectedResult,
		);

		$list = $list->remove($valueToRemove);

		Assert::assertEquals(
			$expectedResult,
			$list,
		);
	}

	public function testRemoveMultiple(): void
	{
		$list = IntSortedLinkedList::createEmpty(new AscendingIntegerStrategy())
			->add(1)
			->add(1)
			->add(2)
			->add(3)
			->add(-10)
			->add(10);

		$listWithRemovedItems = $list
			->remove(1)
			->remove(2)
			->remove(-10);

		$expectedList = new IntSortedLinkedList(
			new AscendingIntegerStrategy(),
			new LinkedListItem(
				1,
				new LinkedListItem(
					3,
					new LinkedListItem(
						10,
						null
					)
				)
			)
		);

		Assert::assertEquals(
			$expectedList,
			$listWithRemovedItems,
		);
	}

	public function testRemoveWithNoItems(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('list does not have value 100');

		$list = IntSortedLinkedList::createEmpty(new AscendingIntegerStrategy());

		$list->remove(100);
	}

	public function testRemoveWithMissingValue(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('list does not have value 100');

		$list = new IntSortedLinkedList(
			new AscendingIntegerStrategy(),
			new LinkedListItem(
				20,
				null
			)
		);

		$list->remove(100);
	}
}
