<?php
$time = microtime(TRUE);

require "class.cache.php";
define ('ROOT', dirname(__FILE__));
$Cache = new Cache(ROOT.'/cache/tmp', 60*24);
$Cache = new Cache(ROOT.'/cache/tmp', 60);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>mon test cache</title>
</head>
<body>
	
	<p>Ci dessous mon cache;</p>

<?php
	if (!$Cache->read('1668')){
		$Cache->write('1668', 'ma super 1668');
	}
	echo '<pre>';
	print_r(json_decode($Cache->read("1668")));
	echo '</pre>';
?>

	<p>temps de chargement : <?= round(microtime(TRUE) - $time,3);  ?> secondes  </p>

</body>
</html>