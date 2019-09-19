<?php

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

$replacements = explode(PHP_EOL, $input);
foreach ($replacements as $key => $replacement) {
  list($from, $to) = explode(' => ', $replacement);
  $replacements[$from][] = $to;
  unset($replacements[$key]);
}

/**
 * @param string $input
 *
 * @return array
 */
function generate_new_level($input = 'e', $level = 0) {
  global $replacements;
  $new_level = $level + 1;

  $pieces = preg_split('/([e]|[A-Z][a-df-z]?)/', $input, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

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

$new_molecules = generate_new_level($target);
exit(count($new_molecules));
