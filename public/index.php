<?php

use List\SortedLinked\IntegerAscSortedLinkedList as IntegerAscSortedLinkedListAlias;

require __DIR__ . '/../vendor/autoload.php';

echo "example of integers:";

$tomCruiseOscarsYears = [
    1990,
    2023,
    2000,
    1997,
];
$yearsAsc = new IntegerAscSortedLinkedListAlias();

foreach ($tomCruiseOscarsYears as $year) {
    $yearsAsc->add($year);
}

foreach ($yearsAsc->toValueArray() as $oscarWonYear) {
    echo sprintf("\nTom Cruise won oscar in year %d", $oscarWonYear);
}

echo "\n\nexample of strings:";
$shippingAddresses = [
    "5 North Wintergreen Dr.\nBuffalo, NY 14215",
    "1 Cambridge Rd.\nOrlando, FL 32806",
    "22 Ashley Lane\nHarrison Township, MI 48045",
    "9149 Oakland St.\nEphrata, PA 17522",
    "5 North Wintergreen Dr.\nBuffalo, NY 14215",
];
$shippingAddressesByLength = new \List\SortedLinked\StringByLengthSortedLinkedList();

foreach ($shippingAddresses as $address) {
    $shippingAddressesByLength->add($address);
}

$shortestAddress = $shippingAddressesByLength->toValueArray()[0];
$longestAddress = $shippingAddressesByLength->toValueArray()[array_key_last(
    $shippingAddressesByLength->toValueArray(),
)];

echo sprintf("\nshortest address is: %s", $shortestAddress);
echo sprintf("\nlongest address is: %s", $longestAddress);
