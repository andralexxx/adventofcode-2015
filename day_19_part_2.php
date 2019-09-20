<?php
/**
 * @file
 * Created by PhpStorm.
 * User: andralex
 * Date: 8/9/17
 * Time: 9:07 AM.
 */

$input = <<<'INPUT'
Al => ThF
Al => ThRnFAr
B => BCa
B => TiB
B => TiRnFAr
Ca => CaCa
Ca => PB
Ca => PRnFAr
Ca => SiRnFYFAr
Ca => SiRnMgAr
Ca => SiTh
F => CaF
F => PMg
F => SiAl
H => CRnAlAr
H => CRnFYFYFAr
H => CRnFYMgAr
H => CRnMgYFAr
H => HCa
H => NRnFYFAr
H => NRnMgAr
H => NTh
H => OB
H => ORnFAr
Mg => BF
Mg => TiMg
N => CRnFAr
N => HSi
O => CRnFYFAr
O => CRnMgAr
O => HP
O => NRnFAr
O => OTi
P => CaP
P => PTi
P => SiRnFAr
Si => CaSi
Th => ThCa
Ti => BP
Ti => TiTi
e => HF
e => NAl
e => OMg
INPUT;
$target = 'CRnCaCaCaSiRnBPTiMgArSiRnSiRnMgArSiRnCaFArTiTiBSiThFYCaFArCaCaSiThCaPBSiThSiThCaCaPTiRnPBSiThRnFArArCaCaSiThCaSiThSiRnMgArCaPTiBPRnFArSiThCaSiRnFArBCaSiRnCaPRnFArPMgYCaFArCaPTiTiTiBPBSiThCaPTiBPBSiRnFArBPBSiRnCaFArBPRnSiRnFArRnSiRnBFArCaFArCaCaCaSiThSiThCaCaPBPTiTiRnFArCaPTiBSiAlArPBCaCaCaCaCaSiRnMgArCaSiThFArThCaSiThCaSiRnCaFYCaSiRnFYFArFArCaSiRnFYFArCaSiRnBPMgArSiThPRnFArCaSiRnFArTiRnSiRnFYFArCaSiRnBFArCaSiRnTiMgArSiThCaSiThCaFArPRnFArSiRnFArTiTiTiTiBCaCaSiRnCaCaFYFArSiThCaPTiBPTiBCaSiThSiRnMgArCaF';

//// Test input.
//$input = <<<'INPUT'
//e => H
//e => O
//H => HO
//H => OH
//O => HH
//INPUT;
//
//$target = 'HOHOHO';

// Convert input data strings into usable data structure.
$replacements = explode(PHP_EOL, $input);
foreach ($replacements as $key => $replacement) {
  list($from, $to) = explode(' => ', $replacement);
  $replacements[$from][] = $to;
  unset($replacements[$key]);
}

/**
 * Get possible distinct molecules after one replacement.
 *
 * @param string $molecule
 *   String containing molecule to replace.
 *
 * @return array
 *   Associative array of possible replacements where
 */
function generate_new_level($molecule = 'e', $level = 0) {
  global $replacements;
  $new_level = $level + 1;

  $pieces = preg_split('/([e]|[A-Z][a-df-z]?)/', $molecule, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

  $result = [];
  foreach ($pieces as $key => $piece) {
    if (array_key_exists($piece, $replacements)) {
      foreach ($replacements[$piece] as $replacement) {
        $new_molecule = $pieces;
        $new_molecule[$key] = $replacement;
        $new_molecule = implode('', $new_molecule);
        $result[$new_molecule] = $new_level;
      }
    }
  }

  return $result;
}

/**
 * Recursive search for target.
 *
 * @param array $molecules
 *   Molecules to compare with target.
 * @param bool $found_at
 *   Previosly found position.
 *
 * @return bool|mixed
 */
function search_molecule($molecules = ['e' => 0], $found_at = FALSE) {
  global $target;

  foreach ($molecules as $molecule => $level) {
//    printf('Checked molecule %s at level: #%d' . PHP_EOL, $molecule, $level);
    if (strlen($molecule) > strlen($target)) {
      return FALSE;
    }
    if ($molecule === $target) {
      printf('found molecule at: %d' . PHP_EOL, $level);
      return $level;
    }

    $next_level = generate_new_level($molecule, $level);
    $search_molecule = search_molecule($next_level);

    if (!is_numeric($found_at) && is_numeric($search_molecule)) {
      $found_at = $search_molecule;
    }
    if (is_numeric($found_at) && is_numeric($search_molecule)) {
      $found_at = min($search_molecule, $found_at);
    }
  }

  return $found_at;
}

$result = search_molecule();
printf('Found at level: #%d' . PHP_EOL, $result);
