<?php

namespace List\ValueComparison\Strategy;

use Generator;
use List\ValueComparison\ComparisonOutcomeEnum;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IntegerAscStrategyTest extends TestCase
{

	public function compareValuesDataProvider(): Generator
	{
		yield 'values are equal' => [
			'firstValue' => 1,
			'secondValue' => 1,
			'expectedResult' => ComparisonOutcomeEnum::SAME
		];
		yield 'first value is more' => [
			'firstValue' => 3,
			'secondValue' => 1,
			'expectedResult' => ComparisonOutcomeEnum::MORE
		];
		yield 'first value is less' => [
			'firstValue' => -2,
			'secondValue' => 1,
			'expectedResult' => ComparisonOutcomeEnum::LESS
		];
	}

	/**
	 * @dataProvider compareValuesDataProvider
	 */
	public function testCompareValues(int $firstValue, int $secondValue, ComparisonOutcomeEnum $expectedResult): void
	{
		$strategy = new IntegerAscStrategy();

		Assert::assertSame(
			$expectedResult,
			$strategy->compareValues($firstValue, $secondValue),
		);

	}

}
