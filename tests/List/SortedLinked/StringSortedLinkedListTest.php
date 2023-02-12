<?php

namespace List\SortedLinked;


use Generator;
use List\LinkedListItem;
use List\ValueComparison\Strategy\AscendingStringStrategy;
use List\ValueComparison\Strategy\DescendingStringStrategy;
use List\ValueComparison\ValueComparisonStrategyInterface;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class StringSortedLinkedListTest extends TestCase
{

	public function toValueDataProvider(): Generator
	{
		yield 'empty list' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				null
			),
			'expectedResult' => [],
		];
		yield 'ascending list' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'a',
					new LinkedListItem(
						'b',
						new LinkedListItem(
							'c',
							null
						)
					)
				)
			),
			'expectedResult' => ['a', 'b', 'c']
		];
		yield 'descending list' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'foo',
					new LinkedListItem(
						'bar',
						new LinkedListItem(
							'baz',
							new LinkedListItem(
								'faz',
								null
							)
						)
					)
				)
			),
			'expectedResult' => ['foo', 'bar', 'baz', 'faz']
		];
		yield 'list with duplicate values in ascending order' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'a',
					new LinkedListItem(
						'b',
						new LinkedListItem(
							'c',
							new LinkedListItem(
								'c',
								new LinkedListItem(
									'd',
									null
								)
							)
						)
					)
				)
			),
			'expectedResult' => ['a', 'b', 'c', 'c', 'd']
		];
	}

	/**
	 * @dataProvider toValueDataProvider
	 * @param array<int> $expectedResult
	 */
	public function testToValueArray(StringSortedLinkedList $list, array $expectedResult): void
	{
		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function addBeforeDataProvider(): Generator
	{
		yield 'ascending' => [
			'strategy' => new AscendingStringStrategy(),
			'expectedResult' => ['a', 'b']
		];
		yield 'descending' => [
			'strategy' => new DescendingStringStrategy(),
			'expectedResult' => ['b', 'a']
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
		$list = StringSortedLinkedList::createEmpty($strategy)
			->add('a')
			->add('b');

		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function addAfterDataProvider(): Generator
	{
		yield 'ascending' => [
			'strategy' => new AscendingStringStrategy(),
			'expectedResult' => ['a', 'b']
		];
		yield 'descending' => [
			'strategy' => new DescendingStringStrategy(),
			'expectedResult' => ['b', 'a']
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
		$list = StringSortedLinkedList::createEmpty($strategy)
			->add('b')
			->add('a');

		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function addDuplicateDataProvider(): Generator
	{
		yield 'ascending' => [
			'strategy' => new AscendingStringStrategy(),
			'expectedResult' => ['a', 'a']
		];
		yield 'descending' => [
			'strategy' => new DescendingStringStrategy(),
			'expectedResult' => ['a', 'a']
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
		$list = StringSortedLinkedList::createEmpty($strategy)
			->add('a')
			->add('a');

		Assert::assertSame(
			$expectedResult,
			$list->toValueArray(),
		);
	}

	public function testAddGradually(): void
	{
		$list = StringSortedLinkedList::createEmpty(new AscendingStringStrategy());

		$list = $list->add('b');

		Assert::assertSame(
			['b'],
			$list->toValueArray(),
		);

		$list = $list->add('a');

		Assert::assertSame(
			['a', 'b'],
			$list->toValueArray(),
		);

		$list = $list->add('a');

		Assert::assertSame(
			['a', 'a', 'b'],
			$list->toValueArray(),
		);

		$list = $list->add('c');

		Assert::assertSame(
			['a', 'a', 'b', 'c'],
			$list->toValueArray(),
		);

		$list = $list->add('-1');

		Assert::assertSame(
			['-1', 'a', 'a', 'b', 'c'],
			$list->toValueArray(),
		);

		$list = $list->add('-2');

		Assert::assertSame(
			['-2', '-1', 'a', 'a', 'b', 'c'],
			$list->toValueArray(),
		);

	}

	public function testAddMultiple(): void
	{
		$list = StringSortedLinkedList::createEmpty(new AscendingStringStrategy())
			->add('a')
			->add('a')
			->add('b')
			->add('-100')
			->add('c')
			->add('oo')
			->add('oo')
			->add('oo')
			->add('ééé');

		Assert::assertSame(
			[
				'-100',
				'a',
				'a',
				'b',
				'c',
				'oo',
				'oo',
				'oo',
				'ééé',
			],
			$list->toValueArray(),
		);
	}

	public function testHasValue(): void
	{
		$list = StringSortedLinkedList::createEmpty(new AscendingStringStrategy())
			->add('a')
			->add('q')
			->add('ahoj');

		Assert::assertTrue($list->hasValue('a'));
		Assert::assertTrue($list->hasValue('q'));
		Assert::assertTrue($list->hasValue('ahoj'));

		Assert::assertFalse($list->hasValue('b'));
		Assert::assertFalse($list->hasValue('something something'));
		Assert::assertFalse($list->hasValue('foo'));
		Assert::assertFalse($list->hasValue(233));
	}

	public function removeDataProvider(): Generator
	{
		yield 'list with single item' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'abc',
					null
				)
			),
			'valueToRemove' => 'abc',
			'expectedResult' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				null
			)
		];

		yield 'remove from the start' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'foo',
					new LinkedListItem(
						'bar',
						new LinkedListItem(
							'baz',
							null
						)
					)
				)
			),
			'valueToRemove' => 'foo',
			'expectedResult' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'bar',
					new LinkedListItem(
						'baz',
						null
					)
				)
			)
		];
		yield 'remove from the middle' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'foo',
					new LinkedListItem(
						'bar',
						new LinkedListItem(
							'baz',
							null
						)
					)
				)
			),
			'valueToRemove' => 'bar',
			'expectedResult' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'foo',
					new LinkedListItem(
						'baz',
						null
					)
				)
			)
		];
		yield 'remove from the end' => [
			'list' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'foo',
					new LinkedListItem(
						'bar',
						new LinkedListItem(
							'baz',
							null
						)
					)
				)
			),
			'valueToRemove' => 'baz',
			'expectedResult' => new StringSortedLinkedList(
				new AscendingStringStrategy(),
				new LinkedListItem(
					'foo',
					new LinkedListItem(
						'bar',
						null
					)
				)
			)
		];
	}

	/**
	 * @dataProvider removeDataProvider
	 */
	public function testRemove(StringSortedLinkedList $list, string $valueToRemove, StringSortedLinkedList $expectedResult): void
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
		$list = StringSortedLinkedList::createEmpty(new AscendingStringStrategy())
			->add('a')
			->add('a')
			->add('c')
			->add('query')
			->add('123')
			->add('hello world');

		$listWithRemovedItems = $list
			->remove('a')
			->remove('query')
			->remove('123');

		$expectedList = new StringSortedLinkedList(
			new AscendingStringStrategy(),
			new LinkedListItem(
				'a',
				new LinkedListItem(
					'c',
					new LinkedListItem(
						'hello world',
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

		$list = StringSortedLinkedList::createEmpty(new AscendingStringStrategy());

		$list->remove(100);
	}

	public function testRemoveWithMissingValue(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('list does not have value 100');

		$list = new StringSortedLinkedList(
			new AscendingStringStrategy(),
			new LinkedListItem(
				20,
				null
			)
		);

		$list->remove(100);
	}
}
