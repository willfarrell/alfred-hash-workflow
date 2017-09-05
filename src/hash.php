<?php
//$query = 'PASSWORD_DEFAULT purple monkey dishwasher';
// ****************
error_reporting(0);
require_once('workflows.php');

$w = new Workflows();
if (!isset($query)) { $query = "{query}"; }

$password_algos = [PASSWORD_DEFAULT, PASSWORD_BCRYPT]; // ** Users can add more algos here
$algos = array_merge(hash_algos(), $password_algos);

// has algo set
if (strpos($query, " ") !== false) {
	$parts = explode(" ", $query);
	$algo_q = array_shift($parts);
	$string = implode(" ", $parts);
	
	foreach($algos as $algo) {
		$pos = strpos($algo, $algo_q);
		if ($pos !== false && $pos == 0) {
			
			if (in_array($algo, $password_algos)) {
				$hash = password_hash($string, $algo);
			} else {
				$hash = hash($algo, $string);
			}
			
			//echo "hash-$algo", $hash, "$algo", $hash, 'icon.png', 'yes\n';
			$w->result( "hash-$algo", $hash, "$algo", $hash, 'icon.png', 'yes' );
		}
	}
	
}

if ( count( $w->results() ) == 0 ) {
	foreach($algos as $algo) {
		if (in_array($algo, $password_algos)) {
			$hash = password_hash($query, $algo);
		} else {
			$hash = hash($algo, $query);
		}
		$w->result( "hash-$algo", $hash, "$algo", $hash, 'icon.png', 'yes' );
	}
	//$w->result( 'hash', $query, 'None', $query, 'icon.png', 'yes' );
}

echo $w->toxml();
// ****************
?>