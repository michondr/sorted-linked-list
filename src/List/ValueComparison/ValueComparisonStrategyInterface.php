<?php

namespace List\ValueComparison;

interface ValueComparisonStrategyInterface
{
	public function compareValues(mixed $first, mixed $second): ComparisonOutcomeEnum;
}