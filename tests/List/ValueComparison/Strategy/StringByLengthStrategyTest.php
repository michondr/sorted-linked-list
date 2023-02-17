<?php

declare(strict_types=1);

namespace List\ValueComparison\Strategy;

use Generator;
use List\ValueComparison\ComparisonOutcomeEnum;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class StringByLengthStrategyTest extends TestCase
{
    public function compareValuesDataProvider(): Generator
    {
        yield 'empty strings are equal' => [
            'firstValue' => '',
            'secondValue' => '',
            'expectedResult' => ComparisonOutcomeEnum::EQUALS,
        ];
        yield 'single characters are equal' => [
            'firstValue' => 'a',
            'secondValue' => 'a',
            'expectedResult' => ComparisonOutcomeEnum::EQUALS,
        ];
        yield 'same strings with multibyte chars are equal' => [
            'firstValue' => 'Šmandalfe, ukážeš nám trik se špičatým kloboukem?',
            'secondValue' => 'Lorem ipsum dolor sit amet, consectetur adipiscin',
            'expectedResult' => ComparisonOutcomeEnum::EQUALS,
        ];
        yield 'first value is longer' => [
            'firstValue' => 'violet',
            'secondValue' => 'blue',
            'expectedResult' => ComparisonOutcomeEnum::MORE,
        ];
        yield 'first value is shorter' => [
            'firstValue' => 'red',
            'secondValue' => 'blue',
            'expectedResult' => ComparisonOutcomeEnum::LESS,
        ];
    }

    /**
     * @dataProvider compareValuesDataProvider
     */
    public function testCompareValues(
        string $firstValue,
        string $secondValue,
        ComparisonOutcomeEnum $expectedResult,
    ): void {
        $strategy = new StringByLengthStrategy();

        Assert::assertEquals(
            $expectedResult,
            $strategy->compareValues($firstValue, $secondValue),
        );
    }
}
