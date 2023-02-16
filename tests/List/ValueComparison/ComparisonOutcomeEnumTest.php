<?php

namespace List\ValueComparison;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ComparisonOutcomeEnumTest extends TestCase
{
    private const IS_LESS_OR_EQUAL = true;
    private const IS_NOT_LESS_OR_EQUAL = false;

    private const IS_SAME = true;
    private const IS_NOT_SAME = false;

    private const IS_MORE = true;
    private const IS_NOT_MORE = false;

    public function isLessOrEqualDataProvider(): Generator
    {
        yield 'more' => [
            'outcome' => ComparisonOutcomeEnum::MORE,
            'expectedIsLessOrEqual' => self::IS_NOT_LESS_OR_EQUAL,

        ];
        yield 'equals' => [
            'outcome' => ComparisonOutcomeEnum::EQUALS,
            'expectedIsLessOrEqual' => self::IS_LESS_OR_EQUAL,

        ];
        yield 'less' => [
            'outcome' => ComparisonOutcomeEnum::LESS,
            'expectedIsLessOrEqual' => self::IS_LESS_OR_EQUAL,

        ];
    }

    /**
     * @dataProvider isLessOrEqualDataProvider
     */
    public function testIsLessOrEqual(ComparisonOutcomeEnum $outcome, bool $expectedIsLessOrEqual): void
    {
        Assert::assertSame(
            $expectedIsLessOrEqual,
            $outcome->isLessOrEqual(),
        );
    }

    public function isSameDataProvider(): Generator
    {
        yield 'more' => [
            'outcome' => ComparisonOutcomeEnum::MORE,
            'expectedIsSame' => self::IS_NOT_SAME,

        ];
        yield 'equals' => [
            'outcome' => ComparisonOutcomeEnum::EQUALS,
            'expectedIsSame' => self::IS_SAME,

        ];
        yield 'less' => [
            'outcome' => ComparisonOutcomeEnum::LESS,
            'expectedIsSame' => self::IS_NOT_SAME,

        ];
    }

    /**
     * @dataProvider isSameDataProvider
     */
    public function testIsSame(ComparisonOutcomeEnum $outcome, bool $expectedIsSame): void
    {
        Assert::assertSame(
            $expectedIsSame,
            $outcome->isSame(),
        );
    }


    public function isMoreDataProvider(): Generator
    {
        yield 'more' => [
            'outcome' => ComparisonOutcomeEnum::MORE,
            'expectedIsMore' => self::IS_MORE,

        ];
        yield 'equals' => [
            'outcome' => ComparisonOutcomeEnum::EQUALS,
            'expectedIsMore' => self::IS_NOT_MORE,

        ];
        yield 'less' => [
            'outcome' => ComparisonOutcomeEnum::LESS,
            'expectedIsMore' => self::IS_NOT_MORE,

        ];
    }

    /**
     * @dataProvider isMoreDataProvider
     */
    public function testIsMore(ComparisonOutcomeEnum $outcome, bool $expectedIsMore): void
    {
        Assert::assertSame(
            $expectedIsMore,
            $outcome->isMore(),
        );
    }
}
