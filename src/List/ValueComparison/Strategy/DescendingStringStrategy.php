<?php

namespace List\ValueComparison\Strategy;

use List\ValueComparison\ComparisonOutcomeEnum;
use List\ValueComparison\ValueComparisonStrategyInterface;

class DescendingStringStrategy implements ValueComparisonStrategyInterface
{

	public function compareValues(mixed $first, mixed $second): ComparisonOutcomeEnum
	{
		assert(is_string($first));
		assert(is_string($second));

		return match ($first <=> $second) {
			-1 => ComparisonOutcomeEnum::MORE,
			0 => ComparisonOutcomeEnum::SAME,
			1 => ComparisonOutcomeEnum::LESS,
		};
	}
}