<?php 
include_once dirname(__FILE__).'/vendor/firebase/php-jwt/src/JWT.php';
include_once dirname(__FILE__).'/php/'.'logger.class.php';
use \Firebase\JWT\JWT;
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

	if(!$badToken){
		$result->path = str_replace('\\', '/', $result->path);
		$result->path = str_replace(FILES_PATH_ABS, '', $result->path);
		$result->path = str_replace($result->login, '', $result->path);
		$path = FILES_PATH.$result->path.$result->login;
		$isExpired = strtotime($result->dt_exp) < time();
		if(is_dir($path) && !$isExpired){
			$has_token = true;
			$_SESSION['ELFINDER_AUTH_USER'] = $result->path.$result->login;
			$_SESSION['authorized'] = true;
			//on enregistre le token pour les logs
			$_SESSION['token'] = $jwt;
		}
	}else{
		//TODO log du token
		$logFile = FILES_PATH.'/.temp/log_'.date('Y-m-d').'.txt';
		$myLogger = new elFinderLogger($logFile);
		$log = 'bad token => ['.date('d.m H:s')."] : $jwt\n";
		$myLogger->write($log);
	}
}