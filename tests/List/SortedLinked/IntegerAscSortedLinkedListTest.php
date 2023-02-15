<?php

namespace List\SortedLinked;

use Generator;
use List\LinkedListItem;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IntegerAscSortedLinkedListTest extends TestCase
{
    public function toValueDataProvider(): Generator
    {
        yield 'empty list' => [
            'list' => new IntegerAscSortedLinkedList(
                null
            ),
            'expectedResult' => [],
        ];
        yield 'ascending list' => [
            'list' => new IntegerAscSortedLinkedList(
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
            'expectedResult' => [-3, 0, 5],
        ];
        yield 'descending list' => [
            'list' => new IntegerAscSortedLinkedList(
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
            'expectedResult' => [10, 3, -42, -100],
        ];
        yield 'list with duplicate values in descending order' => [
            'list' => new IntegerAscSortedLinkedList(
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
            'expectedResult' => [10, 3, -42, -42, -100],
        ];
    }

    /**
     * @dataProvider toValueDataProvider
     *
     * @param array<int> $expectedResult
     */
    public function testToValueArray(IntegerAscSortedLinkedList $list, array $expectedResult): void
    {
        Assert::assertSame(
            $expectedResult,
            $list->toValueArray(),
        );
    }

    public function testAddBefore(): void
    {
        $list = (new IntegerAscSortedLinkedList())
            ->add(1)
            ->add(-3);

        Assert::assertSame(
            [-3, 1],
            $list->toValueArray(),
        );
    }

    public function testAddAfter(): void
    {
        $list = (new IntegerAscSortedLinkedList())
            ->add(1)
            ->add(5);

        Assert::assertSame(
            [1, 5],
            $list->toValueArray(),
        );
    }

    public function testAddDuplicate(): void
    {
        $listWithAddedItems = (new IntegerAscSortedLinkedList())
            ->add(1)
            ->add(1);

        Assert::assertSame(
            [1, 1],
            $listWithAddedItems->toValueArray(),
        );
    }

    public function testAddGradually(): void
    {
        $list = new IntegerAscSortedLinkedList();

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
        $list = (new IntegerAscSortedLinkedList())
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
        $list = (new IntegerAscSortedLinkedList())
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
            'list' => new IntegerAscSortedLinkedList(
                new LinkedListItem(
                    3,
                    null
                )
            ),
            'valueToRemove' => 3,
            'expectedResult' => new IntegerAscSortedLinkedList(
                null
            ),
        ];

        yield 'remove from the start' => [
            'list' => new IntegerAscSortedLinkedList(
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
            'expectedResult' => new IntegerAscSortedLinkedList(
                new LinkedListItem(
                    0,
                    new LinkedListItem(
                        5,
                        null
                    )
                )
            ),
        ];
        yield 'remove from the middle' => [
            'list' => new IntegerAscSortedLinkedList(
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
            'expectedResult' => new IntegerAscSortedLinkedList(
                new LinkedListItem(
                    -3,
                    new LinkedListItem(
                        5,
                        null
                    )
                )
            ),
        ];
        yield 'remove from the end' => [
            'list' => new IntegerAscSortedLinkedList(
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
            'expectedResult' => new IntegerAscSortedLinkedList(
                new LinkedListItem(
                    -3,
                    new LinkedListItem(
                        0,
                        null
                    )
                )
            ),
        ];
        yield 'remove duplicate' => [
            'list' => new IntegerAscSortedLinkedList(
                new LinkedListItem(
                    3,
                    new LinkedListItem(
                        3,
                        new LinkedListItem(
                            3,
                            null
                        )
                    )
                )
            ),
            'valueToRemove' => 3,
            'expectedResult' => new IntegerAscSortedLinkedList(
                new LinkedListItem(
                    3,
                    new LinkedListItem(
                        3,
                        null
                    )
                )
            ),
        ];
    }

    /**
     * @dataProvider removeDataProvider
     */
    public function testRemove(
        IntegerAscSortedLinkedList $list,
        int $valueToRemove,
        IntegerAscSortedLinkedList $expectedResult,
    ): void {
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
        $list = (new IntegerAscSortedLinkedList())
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

        $expectedList = new IntegerAscSortedLinkedList(
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

        $list = new IntegerAscSortedLinkedList();

        $list->remove(100);
    }

    public function testRemoveWithMissingValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('list does not have value 100');

        $list = new IntegerAscSortedLinkedList(
            new LinkedListItem(
                20,
                null
            )
        );

        $list->remove(100);
    }
}
