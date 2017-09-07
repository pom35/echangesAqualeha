<?php
// error_reporting(0); // Set E_ALL for debuging
error_reporting(E_ALL); // Set E_ALL for debuging
define('ROOT_PATH', '../');
define('FILES_PATH', ROOT_PATH.'../files');

/***** auth section *****/
$admins = array('admin' => true);

session_start();

if(isset($_GET['logout'])){
	session_destroy();
// 	header('Location: elfinder.php');
	echo '{"uname": ""}';
	exit();
}

/******************************/

$username = $_SESSION['ELFINDER_AUTH_USER'];
if (isset($_GET['status'])) {
	echo '{"uname": "'.(isset($username)? $username: '').'"}';
	exit();
}

$isAdmin = isset($admins[$username]);
$isUser = $username? true : false;
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'logger.class.php';
include_once dirname(__FILE__).'/../vendor/firebase/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

//TODO génération du token 
// $array = array('login' => $username, 'dt_exp' => date('Y-m-d'));
// $jwt = JWT::encode(json_encode($array), 'eb4fa84c1f979473eb1cd2745019c79f');
// echo $jwt;


$logFile = 'c:/files/temp/log_'.date('Y-m-d').'.txt';
$myLogger = new elFinderLogger($logFile);
/**
 * si c'est un dossier => ok
 * sinon, créer le dossier et n'afficher que celui là
 */
if($isAdmin){
	$path =  FILES_PATH.'/';
	// Documentation for connector options:
	// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
	$opts = array(
			// 'debug' => true,
			'roots' => array(
					array(
							'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
							'path'          => FILES_PATH,                 		// path to files (REQUIRED)
							'URL'           => dirname($_SERVER['PHP_SELF']) . '/'.$path, // URL to files (REQUIRED)
							'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
							'uploadAllow'   => array('image', 'text/plain'),// Mimetype `image` and `text/plain` allowed to upload
							'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
							'accessControl' => 'rwaccess',
							'uploadMaxSize' => 100000000
					)
			)
	);
	
}elseif(!isset($_SESSION['token'])){
	$division = '';
	if($_SESSION['user_info'][0]['division']){
		$division = $_SESSION['user_info'][0]['division'][0].'/';
	}
	$divisionPath = FILES_PATH.'/'.$division;
	if(!is_dir($divisionPath)){
		mkdir($divisionPath);
	}
	$path =  FILES_PATH.'/'.$division.$username.'/';
	if(!is_dir($path)){
		mkdir($path);
	}
	// Documentation for connector options:
	// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
	$opts = array(
			// 'debug' => true,
			'roots' => array(
					array(
							'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
							'path'          => $path,                 		// path to files (REQUIRED)
							'URL'           => dirname($_SERVER['PHP_SELF']) . '/'.$path, // URL to files (REQUIRED)
							'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
							'uploadAllow'   => array('image', 'text/plain'),// Mimetype `image` and `text/plain` allowed to upload
							'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
							'accessControl' => 'rwaccess',
							'uploadMaxSize' => 100000000,
					)
			)
	);
}else{
	$path =  FILES_PATH.'/'.$username.'/';
	$opts = array(
			// 'debug' => true,
			'roots' => array(
					array(
							'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
							'path'          => $path,                 		// path to files (REQUIRED)
							'URL'           => dirname($_SERVER['PHP_SELF']) . '/'.$path, // URL to files (REQUIRED)
							'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
							'uploadAllow'   => array('image', 'text/plain'),// Mimetype `image` and `text/plain` allowed to upload
							'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
							'accessControl' => 'roaccess',
					)
			)
	);
}
$opts['bind'] = array(
		'mkdir mkfile rename duplicate upload rm paste file' => array($myLogger, 'log'),
);
// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();



function rwaccess($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
	? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
	:  null;                                    // else elFinder decide it itself
}

function roaccess($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
	? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
	: ($attr == 'read' || $attr == 'locked');   // else read only
}