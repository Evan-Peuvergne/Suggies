<?php
header('Content-Type: text/html');

	require './config.php';
	require './get_session.php';

$token = get_new_token();

// echo '<pre>';
// print_r($token);
// echo '</pre>';

$url = "https://www.themoviedb.org/authenticate/".$token->request_token."?redirect_to=http://test.lorem.ovh";

if (!empty($_GET['request_token']) && ($_GET['approved'] == true))
{
	$approved_token = $_GET['request_token'];
	$user = array();
	$user['session_id'] = get_session_id($approved_token)->session_id; // Stocke une chaîne de caractère qui donne la permission d'accès
	$user['info'] = get_user_info($user['session_id']); // Stocke un objet avec les infos de l'utilisateur (on utilise id et username)
	echo '<pre>';
	print_r($user);
	echo '</pre>';
			
	post_watch_list($user['info']->id, 1399, $user['session_id']);

	echo '<pre>';
	print_r($user);
	echo '</pre>';
			
}
else 
{
	get_new_token();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<div id="container">

		<a href="<?=$url?>">Hello you come over here</a>

	</div>


</body>
</html>
