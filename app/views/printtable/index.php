<?php
$leftPad = function($str, $max, $pad = 2) {
	return str_pad($str, $max+$pad, ' ', STR_PAD_LEFT);
};
?>
<?php if (is_array($data) && count($data) > 0): ?>
<?php
//header
$header = '';
$header .= '|';
foreach ($index as $idx) {
	$header .= $leftPad($idx, @$padding[$idx]) . ' |';
}

$lineLength = strlen($header);
echo str_pad('', $lineLength, '='). "\n";
echo $header . "\n";
echo str_pad('', $lineLength, '-') . "\n";
?>
<?php 
foreach ($data as $row) {
	echo '|';
	foreach ($index as $idx) {
		if (!empty($row[$idx])) {
			echo $leftPad($row[$idx], $padding[$idx]). ' |';
			continue;
		}
		echo $leftPad('', $padding[$idx]). ' |';
	}
	echo "\n";
}
echo str_pad('', $lineLength, '='). "\n";
?>
<?php else: ?>
Nothing to show
<?php endif; ?>