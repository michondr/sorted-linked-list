<?php

namespace List\SortedLinked;

use Generator;
use List\LinkedListItem;
use List\ValueComparison\Strategy\StringByLengthStrategy;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class StringByLengthSortedLinkedListTest extends TestCase
{
    public function toValueDataProvider(): Generator
    {
        yield 'empty list' => [
            'list' => new StringByLengthSortedLinkedList(
                null
            ),
            'expectedResult' => [],
        ];
        yield 'ascending list' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'les',
                    new LinkedListItem(
                        'louka',
                        new LinkedListItem(
                            'lores ipsum',
                            null
                        )
                    )
                )
            ),
            'expectedResult' => ['les', 'louka', 'lores ipsum'],
        ];
        yield 'list with duplicate values in ascending order' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    '1',
                    new LinkedListItem(
                        '123',
                        new LinkedListItem(
                            '123',
                            new LinkedListItem(
                                'delší',
                                new LinkedListItem(
                                    'nejdelší',
                                    null
                                )
                            )
                        )
                    )
                )
            ),
            'expectedResult' => ['1', '123', '123', 'delší', 'nejdelší'],
        ];
    }

    /**
     * @dataProvider toValueDataProvider
     *
     * @param array<string> $expectedResult
     */
    public function testToValueArray(StringByLengthSortedLinkedList $list, array $expectedResult): void
    {
        Assert::assertSame(
            $expectedResult,
            $list->toValueArray(),
        );
    }

    public function testAddBefore(): void
    {
        $list = (new StringByLengthSortedLinkedList())
            ->add('aaa')
            ->add('bb');

        Assert::assertSame(
            ['bb', 'aaa'],
            $list->toValueArray(),
        );
    }

    public function testAddAfter(): void
    {
        $list = (new StringByLengthSortedLinkedList())
            ->add('a')
            ->add('bbbb');

        Assert::assertSame(
            ['a', 'bbbb'],
            $list->toValueArray(),
        );
    }

    public function testAddDuplicate(): void
    {
        $listWithAddedItems = (new StringByLengthSortedLinkedList())
            ->add('cc')
            ->add('cc');

        Assert::assertSame(
            ['cc', 'cc'],
            $listWithAddedItems->toValueArray(),
        );
    }

    public function testAddGradually(): void
    {
        $list = new StringByLengthSortedLinkedList();

        $list = $list->add('');

        Assert::assertSame(
            [''],
            $list->toValueArray(),
        );

        $list = $list->add('');

        Assert::assertSame(
            ['', ''],
            $list->toValueArray(),
        );

        $list = $list->add('user');

        Assert::assertSame(
            ['', '', 'user'],
            $list->toValueArray(),
        );

        $list = $list->add('admin');

        Assert::assertSame(
            ['', '', 'user', 'admin'],
            $list->toValueArray(),
        );

        $list = $list->add('');

        Assert::assertSame(
            ['', '', '', 'user', 'admin'],
            $list->toValueArray(),
        );
    }

    public function testAddMultiple(): void
    {
        $list = (new StringByLengthSortedLinkedList())
            ->add('')
            ->add('/.')
            ->add('/..')
            ->add('/src')
            ->add('/tests')
            ->add('/vendor')
            ->add('');

        Assert::assertSame(
            ['', '', '/.', '/..', '/src', '/tests', '/vendor'],
            $list->toValueArray(),
        );
    }

    public function testHasValue(): void
    {
        $list = new StringByLengthSortedLinkedList(
            new LinkedListItem(
                '/src',
                new LinkedListItem(
                    '/tests',
                    new LinkedListItem(
                        '/vendor',
                        null
                    )
                )
            )
        );

        Assert::assertTrue($list->hasValue('/src'));
        Assert::assertTrue($list->hasValue('/tests'));
        Assert::assertTrue($list->hasValue('/vendor'));

        Assert::assertFalse($list->hasValue(''));
        Assert::assertFalse($list->hasValue('foo'));
    }

    public function removeDataProvider(): Generator
    {
        yield 'list with single item' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'foo',
                    null
                )
            ),
            'valueToRemove' => 'foo',
            'expectedResult' => new StringByLengthSortedLinkedList(
                null
            ),
        ];

        yield 'remove from the start' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'b',
                    new LinkedListItem(
                        'aa',
                        new LinkedListItem(
                            'cdef',
                            null
                        )
                    )
                )
            ),
            'valueToRemove' => 'aa',
            'expectedResult' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'b',
                    new LinkedListItem(
                        'cdef',
                        null
                    )
                )
            ),
        ];
        yield 'remove from the middle' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'b',
                    new LinkedListItem(
                        'aa',
                        new LinkedListItem(
                            'cdef',
                            null
                        )
                    )
                )
            ),
            'valueToRemove' => 'aa',
            'expectedResult' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'b',
                    new LinkedListItem(
                        'cdef',
                        null
                    )
                )
            ),
        ];
        yield 'remove from the end' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'b',
                    new LinkedListItem(
                        'aa',
                        new LinkedListItem(
                            'cdef',
                            null
                        )
                    )
                )
            ),
            'valueToRemove' => 'cdef',
            'expectedResult' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'b',
                    new LinkedListItem(
                        'aa',
                        null
                    )
                )
            ),
        ];
        yield 'remove duplicate' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    '1',
                    new LinkedListItem(
                        '1',
                        new LinkedListItem(
                            '1',
                            null
                        )
                    )
                )
            ),
            'valueToRemove' => '1',
            'expectedResult' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    '1',
                    new LinkedListItem(
                        '1',
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
        StringByLengthSortedLinkedList $list,
        string $valueToRemove,
        StringByLengthSortedLinkedList $expectedResult,
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
        $list = new StringByLengthSortedLinkedList(
            new LinkedListItem(
                '',
                new LinkedListItem(
                    '',
                    new LinkedListItem(
                        '/.',
                        new LinkedListItem(
                            '/..',
                            new LinkedListItem(
                                '/src',
                                new LinkedListItem(
                                    '/tests',
                                    new LinkedListItem(
                                        '/vendor',
                                        null
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );

        $listWithRemovedItems = $list
            ->remove('/vendor')
            ->remove('')
            ->remove('/..');

        $expectedList = new StringByLengthSortedLinkedList(
            new LinkedListItem(
                '',
                new LinkedListItem(
                    '/.',
                    new LinkedListItem(
                        '/src',
                        new LinkedListItem(
                            '/tests',
                            null
                        )
                    )
                )
            )
        );

        Assert::assertEquals(
            $expectedList,
            $listWithRemovedItems,
        );
    }

    public function removeMissingValueDataProvider(): Generator
    {
        yield 'list with no items' => [
            'list' => new StringByLengthSortedLinkedList(),
        ];
        yield 'item not present in list' => [
            'list' => new StringByLengthSortedLinkedList(
                new LinkedListItem(
                    'foobar',
                    null
                )
            ),
        ];
    }

    /**
     * @dataProvider removeMissingValueDataProvider
     */
    public function testRemoveMissingValue(StringByLengthSortedLinkedList $list): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('list does not have value "hello world"');

        $list->remove('hello world');
    }
}
