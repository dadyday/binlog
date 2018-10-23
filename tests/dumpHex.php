<?php

function dumpHex($data) {
	$ret = '';
	$max = strlen($data);
	for ($l = 0; $l < $max; $l += 16) {
		$ret .= sprintf('%08X  ', $l);

		$hex = '';
		$chr = '';
		for ($p = $l; $p < min($max, $l+16); $p++) {
			$ch = ord($data[$p]);
			$hex .= sprintf('%02X ', $ch);
			$chr .= $ch > 32 ? chr($ch) : '.';
			if ($p == $l+7) $hex .= ' ';

		}
		$ret .= str_pad($hex, 48, ' ');
		$ret .= ' ';
		$ret .= str_pad($chr, 16, ' ');
		$ret .= "\n";
	}
	return $ret;
}
