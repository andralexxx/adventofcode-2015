<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/4/17
 * Time: 4:37 PM
 */

$in = 'London to Dublin = 464
London to Belfast = 518
Dublin to Belfast = 141';
$in = explode("\n", $in);
print_r($in);

// Build graph.
$graph = [];
foreach ($in as $row) {
  list($a, , $b, , $dist) = explode(' ', $row);
  $graph[$a][$b] = $dist;
  $graph[$b][$a] = $dist;
}
print_r($graph);
