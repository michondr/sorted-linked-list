<?php

namespace List;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class LinkedListItemTest extends TestCase
{
    public function getValueDataProvider(): Generator
    {
        yield 'negative max' => [
            'value' => -PHP_INT_MAX
        ];
        yield 'negative' => [
            'value' => -20
        ];
        yield 'zero' => [
            'value' => 0
        ];
        yield 'positive' => [
            'value' => 100
        ];
        yield 'positive max' => [
            'value' => PHP_INT_MAX
        ];
        yield 'string empty' => [
            'value' => ''
        ];
        yield 'string non-empty' => [
            'value' => 'foobar'
        ];
    }

    /**
     * @dataProvider getValueDataProvider
     */
    public function testGetValue(int|string $value): void
    {
        $item = new LinkedListItem($value, null);

        Assert::assertSame(
            $value,
            $item->getValue()
        );
    }

    public function testGetNextItem(): void
    {
        $nextItem = new LinkedListItem(0, null);
        $item = new LinkedListItem(0, $nextItem);

        Assert::assertSame(
            $nextItem,
            $item->getNextItem()
        );
    }

    public function testSetNextItem(): void
    {
        $item = new LinkedListItem(0, null);
        Assert::assertNull($item->getNextItem());

        $nextItem = new LinkedListItem('this is string', null);
        Assert::assertNull($nextItem->getNextItem());

        $item->setNextItem($nextItem);

        Assert::assertSame(
            $nextItem,
            $item->getNextItem()
        );
    }

    public function testYieldWithNext(): void
    {
        $item = new LinkedListItem(
            10,
            $sub1 = new LinkedListItem(
                'foo',
                $sub2 = new LinkedListItem(
                    -42,
                    $sub3 = new LinkedListItem(
                        'bar',
                        null
                    )
                )
            )
        );

        Assert::assertSame(
            [
                10 => $item,
                3 => $sub1,
                -42 => $sub2,
                -100 => $sub3
            ],
            iterator_to_array($item->yieldWithNext())
        );
    }
}
