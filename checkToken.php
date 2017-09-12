<?php 
$has_token = false;
// check la présence d'un token
if(isset($_GET['t'])){
	$badToken = false;
	try {
		$jwt= $_GET['t'];
		$token = JWT::decode($jwt, 'eb4fa84c1f979473eb1cd2745019c79f', array('HS256'));
		$result = json_decode($token);
	} catch (Exception $e) {
		$badToken = true;
	}
	
	$result->path = str_replace('\\', '/', $result->path);
	$result->path = str_replace(FILES_PATH_ABS, '', $result->path);
	$result->path = str_replace($result->login, '', $result->path);
	if(!$badToken){
		$path = FILES_PATH.$result->path.$result->login;
		$isExpired = strtotime($result->dt_exp) < time();
		if(is_dir($path) && !$isExpired){
			$has_token = true;
			$_SESSION['ELFINDER_AUTH_USER'] = $result->path.$result->login;
			$_SESSION['authorized'] = true;
			//on enregistre le token pour les logs
			$_SESSION['token'] = $jwt;
		}
	}
}