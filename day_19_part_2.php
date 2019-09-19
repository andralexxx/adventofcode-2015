<?php
/**
 * Created by PhpStorm.
 * User: andralex
 * Date: 8/9/17
 * Time: 9:07 AM
 */
$in = 'Al => ThF
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
e => OMg';
$result_molecule = 'CRnCaCaCaSiRnBPTiMgArSiRnSiRnMgArSiRnCaFArTiTiBSiThFYCaFArCaCaSiThCaPBSiThSiThCaCaPTiRnPBSiThRnFArArCaCaSiThCaSiThSiRnMgArCaPTiBPRnFArSiThCaSiRnFArBCaSiRnCaPRnFArPMgYCaFArCaPTiTiTiBPBSiThCaPTiBPBSiRnFArBPBSiRnCaFArBPRnSiRnFArRnSiRnBFArCaFArCaCaCaSiThSiThCaCaPBPTiTiRnFArCaPTiBSiAlArPBCaCaCaCaCaSiRnMgArCaSiThFArThCaSiThCaSiRnCaFYCaSiRnFYFArFArCaSiRnFYFArCaSiRnBPMgArSiThPRnFArCaSiRnFArTiRnSiRnFYFArCaSiRnBFArCaSiRnTiMgArSiThCaSiThCaFArPRnFArSiRnFArTiTiTiTiBCaCaSiRnCaCaFYFArSiThCaPTiBPTiBCaSiThSiRnMgArCaF';

//// Test input.
//$in = 'e => H
//e => O
//H => HO
//H => OH
//O => HH';
//$result_molecule = 'HOHOHO';

// Convert input data strings into usable data structure.
$in = explode("\n", $in);
$generates = $reduces = [];
foreach ($in as $row) {
  list($a, $b) = explode(' => ', $row);
  $generates[$a][] = $b;
  $reduces[$b] = $a;
}

//ksort($reduces);
uksort($reduces, function ($a, $b) {
  $a = strlen($a);
  $b = strlen($b);
  if ($a == $b) return 0;
  return ($a < $b) ? 1: -1;
});
print_r($reduces);

/**
 * @param $replacements
 * @param $subject
 */
function generate_new_molecules($replacements, $subject) {
  $pattern = '/(';
  $pattern .= implode(')|(', array_keys($replacements));
  $pattern .= ')/';

  $pieces = preg_split($pattern, $subject, -1, PREG_SPLIT_DELIM_CAPTURE);
  $pieces = array_filter($pieces);

  // Count for replacements.
  $out = [];
  foreach ($pieces as $key => $piece) {
    if (array_key_exists($piece, $replacements)) {
      foreach ($replacements[$piece] as $replacement) {
        $new_molecule = $pieces;
        $new_molecule[$key] = $replacement;
        $out[] = implode('', $new_molecule);
      }
    }
  }
  return $out;
}

/**
 * @param $replacements
 * @param $subject
 */
function reduce_molecule($replacements, $subject) {
  $pattern = '/(';
  $pattern .= implode(')|(', array_keys($replacements));
  $pattern .= ')/';

  $subject = preg_split($pattern, $subject, -1, PREG_SPLIT_DELIM_CAPTURE);
  $subject = array_filter($subject);

  // Count for replacements.
  foreach ($replacements as $from => $to) {
    foreach ($subject as $key => $piece) {
      if ($piece == $from) {
        $subject[$key] = $to;
        return implode($subject);
      }
    }
  }
  return implode($subject);
}

$steps = 0;
do {
  $found = FALSE;
  printf("molecule: %s\n", $result_molecule);
  $new_molecule = reduce_molecule($reduces, $result_molecule);
  if ($new_molecule != $result_molecule) {
    $found = TRUE;
    $steps++;
  }
  $result_molecule = $new_molecule;
  printf("step: %d\n", $steps);
} while ($found);

//printf("molecule: %s\n", $result_molecule);
//printf("steps: %d\n", $steps);


