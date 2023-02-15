<?php

namespace List\SortedLinked;


use Generator;
use List\LinkedListItem;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IntSortedLinkedListTest extends TestCase
{

	public function toValueDataProvider(): Generator
	{
		yield 'empty list' => [
			'list' => new AscIntSortedLinkedList(
				null
			),
			'expectedResult' => [],
		];
		yield 'ascending list' => [
			'list' => new AscIntSortedLinkedList(
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
			'list' => new AscIntSortedLinkedList(
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
			'list' => new AscIntSortedLinkedList(
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
	public function testToValueArray(AscIntSortedLinkedList $list, array $expectedResult): void
	{
		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function testAddBefore(): void
	{
		$list = (new AscIntSortedLinkedList())
			->add(1)
			->add(-3);

		Assert::assertSame(
			[-3, 1],
			$list->toValueArray(),
		);
	}

	public function testAddAfter(): void
	{
		$list = (new AscIntSortedLinkedList())
			->add(1)
			->add(5);

		Assert::assertSame(
			[1, 5],
			$list->toValueArray(),
		);
	}

	public function testAddDuplicate(): void
	{
		$listWithAddedItems = (new AscIntSortedLinkedList())
			->add(1)
			->add(1);

		Assert::assertSame(
			[1, 1],
			$listWithAddedItems->toValueArray(),
		);
	}

	public function testAddGradually(): void
	{
		$list = new AscIntSortedLinkedList();

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
		$list = (new AscIntSortedLinkedList())
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
		$list = (new AscIntSortedLinkedList())
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
			'list' => new AscIntSortedLinkedList(
				new LinkedListItem(
					3,
					null
				)
			),
			'valueToRemove' => 3,
			'expectedResult' => new AscIntSortedLinkedList(
				null
			)
		];

		yield 'remove from the start' => [
			'list' => new AscIntSortedLinkedList(
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
			'expectedResult' => new AscIntSortedLinkedList(
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
			'list' => new AscIntSortedLinkedList(
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
			'expectedResult' => new AscIntSortedLinkedList(
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
			'list' => new AscIntSortedLinkedList(
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
			'expectedResult' => new AscIntSortedLinkedList(
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
	public function testRemove(AscIntSortedLinkedList $list, int $valueToRemove, AscIntSortedLinkedList $expectedResult): void
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
		$list = (new AscIntSortedLinkedList())
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

		$expectedList = new AscIntSortedLinkedList(
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

		$list = new AscIntSortedLinkedList();

		$list->remove(100);
	}

	public function testRemoveWithMissingValue(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('list does not have value 100');

		$list = new AscIntSortedLinkedList(
			new LinkedListItem(
				20,
				null
			)
		);

		$list->remove(100);
	}
}
