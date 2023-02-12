<?php

namespace List\ValueComparison\Strategy;

use Generator;
use List\ValueComparison\ComparisonOutcomeEnum;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class AscendingStringStrategyTest extends TestCase
{
	public function compareValuesDataProvider(): Generator
	{
		yield 'values are equal' => [
			'firstValue' => 'hello world',
			'secondValue' => 'hello world',
			'expectedResult' => ComparisonOutcomeEnum::SAME
		];
		yield 'first value is higher' => [
			'firstValue' => 'hello world',
			'secondValue' => 'hello',
			'expectedResult' => ComparisonOutcomeEnum::MORE
		];
		yield 'first value is higher #2' => [
			'firstValue' => 'hello world',
			'secondValue' => 'hel',
			'expectedResult' => ComparisonOutcomeEnum::MORE
		];
		yield 'first value is higher #3' => [
			'firstValue' => 'hello world',
			'secondValue' => 'ahoj',
			'expectedResult' => ComparisonOutcomeEnum::MORE
		];
		yield 'second value is higher' => [
			'firstValue' => 'hello',
			'secondValue' => 'hello world',
			'expectedResult' => ComparisonOutcomeEnum::LESS
		];
		yield 'second value is higher #2' => [
			'firstValue' => 'ahoj kámo jak se máš',
			'secondValue' => 'hello world',
			'expectedResult' => ComparisonOutcomeEnum::LESS
		];
	}

	/**
	 * @dataProvider compareValuesDataProvider
	 */
	public function testCompareValues(string $firstValue, string $secondValue, ComparisonOutcomeEnum $expectedResult): void
	{
		$strategy = new AscendingIntegerStrategy();

		Assert::assertSame(
			$expectedResult,
			$strategy->compareValues($firstValue, $secondValue),
		);

	}
}
