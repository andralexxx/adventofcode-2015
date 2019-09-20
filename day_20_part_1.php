<?php

/**
 * @file
 * Created by PhpStorm.
 * User: andralex
 * Date: 20/9/19
 * Time: 9:07 AM.
 */

$target = 29000000;
$house = 1;

/**
 * Calculate how many presents a house get.
 *
 * @param int $n
 *   Number of house.
 *
 * @return int
 *   Number of presents.
 */
function caltulatePresents($n) {
  $summ = 0;
  for ($i = 1; $i * $i <= $n; $i++) {
    if ($n % $i == 0) {
      if ($n / $i == $i) {
        $summ += $i;
      }
      else {
        $summ += $i + ($n / $i);
      }
    }
  }
  return (int) $summ * 10;
}

$result = 0;
while ($result < $target) {
  $result = caltulatePresents($house);
  $house++;
}
$house--;

printf('Result is %d for house #%d', $result, $house);
