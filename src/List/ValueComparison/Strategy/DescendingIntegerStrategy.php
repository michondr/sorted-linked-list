<?php

namespace List\ValueComparison\Strategy;

use List\ValueComparison\ComparisonOutcomeEnum;
use List\ValueComparison\ValueComparisonStrategyInterface;

class DescendingIntegerStrategy implements ValueComparisonStrategyInterface
{

	public function compareValues(mixed $first, mixed $second): ComparisonOutcomeEnum
	{
		assert(is_int($first));
		assert(is_int($second));

		return match ($first <=> $second) {
			-1 => ComparisonOutcomeEnum::MORE,
			0 => ComparisonOutcomeEnum::SAME,
			1 => ComparisonOutcomeEnum::LESS,
		};
	}
}