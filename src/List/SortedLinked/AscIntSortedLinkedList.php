<?php

namespace List\SortedLinked;

use List\LinkedListItem;
use List\ValueComparison\Strategy\AscendingIntegerStrategy;

/**
 * @extends \List\SortedLinked\AbstractSortedLinkedList<int>
 */
final class AscIntSortedLinkedList extends AbstractSortedLinkedList
{
	//TODO: remove unused strategies
	//TODO: rename to type first, order second. dont forget strategies
	public function __construct(
		LinkedListItem $firstItem = null,
	)
	{
		parent::__construct(
			new AscendingIntegerStrategy(),
			$firstItem,
		);
	}

}