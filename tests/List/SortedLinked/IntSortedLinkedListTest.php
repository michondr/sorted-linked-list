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
			'list' => new IntSortedLinkedList(null),
			'expectedResult' => [],
		];
		yield 'ascending list' => [
			'list' => new IntSortedLinkedList(
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
		yield 'list with duplicate values in ascending order' => [
			'list' => new IntSortedLinkedList(
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
	 * @param array<int, LinkedListItem> $expectedResult
	 */
	public function testToValueArray(IntSortedLinkedList $list, array $expectedResult): void
	{
		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function testAddBeforeInAsc(): void
	{
		$list = IntSortedLinkedList::createEmpty()
			->add(1)
			->add(-3);

		Assert::assertSame(
			[-3, 1],
			$list->toValueArray(),
		);
	}


	public function testAddAfterInAsc(): void
	{
		$list = IntSortedLinkedList::createEmpty()
			->add(1)
			->add(5);

		Assert::assertSame(
			[1, 5],
			$list->toValueArray(),
		);
	}

	public function testAddDuplicate(): void
	{
		$list = IntSortedLinkedList::createEmpty()
			->add(1)
			->add(1);

		Assert::assertSame(
			[1, 1],
			$list->toValueArray(),
		);
	}

	public function testAddGradually(): void
	{
		$list = IntSortedLinkedList::createEmpty();

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
		$list = IntSortedLinkedList::createEmpty()
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

}
