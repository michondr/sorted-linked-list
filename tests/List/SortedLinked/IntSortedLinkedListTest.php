<?php

namespace List\SortedLinked;


use Generator;
use List\LinkedListItem;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IntSortedLinkedListTest extends TestCase
{

	public function iterateDataProvider(): Generator
	{
		yield 'empty list' => [
			'list' => new IntSortedLinkedList(null),
			'expectedResult' => []
		];
		yield 'ascending list' => [
			'list' => new IntSortedLinkedList(
				$item1 = new LinkedListItem(
					-3,
					$item2 = new LinkedListItem(
						0,
						$item3 = new LinkedListItem(
							5,
							null
						)
					)
				)
			),
			'expectedResult' => [
				-3 => $item1,
				0 => $item2,
				5 => $item3
			]
		];
		yield 'descending list' => [
			'list' => new IntSortedLinkedList(
				$item1 = new LinkedListItem(
					10,
					$item2 = new LinkedListItem(
						3,
						$item3 = new LinkedListItem(
							-42,
							$item4 = new LinkedListItem(
								-100,
								null
							)
						)
					)
				)
			),
			'expectedResult' => [
				10 => $item1,
				3 => $item2,
				-42 => $item3,
				-100 => $item4,
			]
		];
	}

	/**
	 * @dataProvider iterateDataProvider
	 * @param array<int, LinkedListItem> $expectedResult
	 */
	public function testIterate(IntSortedLinkedList $list, array $expectedResult): void
	{
		Assert::assertSame(
			$expectedResult,
			iterator_to_array($list->iterate()),
		);
	}

}
