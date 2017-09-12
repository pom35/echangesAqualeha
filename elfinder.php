<?php
include_once dirname(__FILE__).'/vendor/firebase/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;
define('ROOT_PATH', '');
define('FILES_PATH', ROOT_PATH.'../files');
define('FILES_PATH_ABS', str_replace('\\', '/', realpath(FILES_PATH)));

session_start();
//function disconnectToAd : Déconnexion au serveur de l'ad
function disconnectToAD($ldapbind) {
	return ldap_unbind($ldapbind);
}

function getUserAD($ldapcon, $login) {
	
	$ldaprdn  = 'OU=Utilisateurs,DC=AQUALEHA,DC=LAN'; // DN ou RDN LDAP
	$filtre="(|(sAMAccountName=".$login."))";
	$restriction = array("sn", "givenname", "mail", "dn", "division");
	
	$result = ldap_search($ldapcon, $ldaprdn, $filtre, $restriction);
	$info = ldap_get_entries($ldapcon, $result);
	
	return $info;
}

if(isset($_GET['logout'])){
    session_destroy();
    header('Location: elfinder.php');
    exit();
}

include_once dirname(__FILE__).'/checkToken.php';

if(!$has_token && !isset($_SESSION['authorized'])){
	include_once dirname(__FILE__).'/login.php';
} else {
	include_once dirname(__FILE__).'/home.php';
}

