<?php
include_once dirname(__FILE__).'/vendor/firebase/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;
define('ROOT_PATH', '');
define('FILES_PATH', ROOT_PATH.'../files');

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
		$path = FILES_PATH.'/'.$result->login;
		$isExpired = strtotime($result->dt_exp) > time();
		
		if(is_dir($path) && !$isExpired){
			$has_token = true;
			$_SESSION['ELFINDER_AUTH_USER'] = $result->login;
			$_SESSION['authorized'] = true;
			$_SESSION['token'] = true;
		}
	}
}

if(!$has_token && !isset($_SESSION['authorized'])){
	
	if(isset($_POST['submit'])){
		$ldaphost = 'dc1-aqualeha';
		$ldapconn= ldap_connect($ldaphost) or die("Could not connect to $ldaphost");
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		
		if ($ldapconn) {
			// Connexion au serveur LDAP
			$ldapbind = ldap_bind($ldapconn, $_POST['username'].'@aqualeha', $_POST['password']);
			// Vérification de l'authentification
			
			if ($ldapbind) {
				//Connexion LDAP réussie
				
				$_SESSION['user_info'] = getUserAD($ldapconn, $_POST['username']);
				$_SESSION['authorized'] = true;
				$_SESSION['ELFINDER_AUTH_USER'] = $_POST['username'];
				disconnectToAD($ldapconn);
			} else {
				session_destroy();
			}
			header('Location: elfinder.php');
			exit();
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />
	</head>
<body>
	<form action='' method='post' autocomplete='off'>
		<p>Login: <input type="text" name="username" value=""></p>
		<p>Mot de passe: <input type="password" name="password" value=""></p>
		<p><input type="submit" name="submit" value="Login"></p>    
	</form>
</body>
<?php } else { ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

	<!-- Section CSS -->
	<!-- jQuery UI (REQUIRED) -->
	<link rel="stylesheet" href="jquery/jquery-ui-1.12.0.css" type="text/css">

	<!-- elfinder css -->
	<link rel="stylesheet" href="css/commands.css"    type="text/css">
	<link rel="stylesheet" href="css/common.css"      type="text/css">
	<link rel="stylesheet" href="css/contextmenu.css" type="text/css">
	<link rel="stylesheet" href="css/cwd.css"         type="text/css">
	<link rel="stylesheet" href="css/dialog.css"      type="text/css">
	<link rel="stylesheet" href="css/fonts.css"       type="text/css">
	<link rel="stylesheet" href="css/navbar.css"      type="text/css">
	<link rel="stylesheet" href="css/places.css"      type="text/css">
	<link rel="stylesheet" href="css/quicklook.css"   type="text/css">
	<link rel="stylesheet" href="css/statusbar.css"   type="text/css">
	<link rel="stylesheet" href="css/theme.css"       type="text/css">
	<link rel="stylesheet" href="css/toast.css"       type="text/css">
	<link rel="stylesheet" href="css/toolbar.css"     type="text/css">

	<!-- Section JavaScript -->
	<!-- jQuery and jQuery UI (REQUIRED) -->
	<script src="jquery/jquery-1.12.4.js" type="text/javascript" charset="utf-8"></script>
	<script src="jquery/jquery-ui-1.12.0.js" type="text/javascript" charset="utf-8"></script>

	<!-- elfinder core -->
	<script src="js/elFinder.js"></script>
	<script src="js/elFinder.version.js"></script>
	<script src="js/jquery.elfinder.js"></script>
	<script src="js/elFinder.mimetypes.js"></script>
	<script src="js/elFinder.options.js"></script>
	<script src="js/elFinder.options.netmount.js"></script>
	<script src="js/elFinder.history.js"></script>
	<script src="js/elFinder.command.js"></script>
	<script src="js/elFinder.resources.js"></script>

	<!-- elfinder dialog -->
	<script src="js/jquery.dialogelfinder.js"></script>

	<!-- elfinder default lang -->
	<script src="js/i18n/elfinder.en.js"></script>

	<!-- elfinder ui -->
	<script src="js/ui/button.js"></script>
	<script src="js/ui/contextmenu.js"></script>
	<script src="js/ui/cwd.js"></script>
	<script src="js/ui/dialog.js"></script>
	<script src="js/ui/fullscreenbutton.js"></script>
	<script src="js/ui/navbar.js"></script>
	<script src="js/ui/navdock.js"></script>
	<script src="js/ui/overlay.js"></script>
	<script src="js/ui/panel.js"></script>
	<script src="js/ui/path.js"></script>
	<script src="js/ui/places.js"></script>
	<script src="js/ui/searchbutton.js"></script>
	<script src="js/ui/sortbutton.js"></script>
	<script src="js/ui/stat.js"></script>
	<script src="js/ui/toast.js"></script>
	<script src="js/ui/toolbar.js"></script>
	<script src="js/ui/tree.js"></script>
	<script src="js/ui/uploadButton.js"></script>
	<script src="js/ui/viewbutton.js"></script>
	<script src="js/ui/workzone.js"></script>

	<!-- elfinder commands -->
	<script src="js/commands/archive.js"></script>
	<script src="js/commands/back.js"></script>
	<script src="js/commands/copy.js"></script>
	<script src="js/commands/cut.js"></script>
	<script src="js/commands/chmod.js"></script>
	<script src="js/commands/colwidth.js"></script>
	<script src="js/commands/download.js"></script>
	<script src="js/commands/duplicate.js"></script>
	<script src="js/commands/edit.js"></script>
	<script src="js/commands/empty.js"></script>
	<script src="js/commands/extract.js"></script>
	<script src="js/commands/forward.js"></script>
	<script src="js/commands/fullscreen.js"></script>
	<script src="js/commands/getfile.js"></script>
	<script src="js/commands/help.js"></script>
	<script src="js/commands/hidden.js"></script>
	<script src="js/commands/home.js"></script>
	<script src="js/commands/info.js"></script>
	<script src="js/commands/mkdir.js"></script>
	<script src="js/commands/mkfile.js"></script>
	<script src="js/commands/netmount.js"></script>
	<script src="js/commands/open.js"></script>
	<script src="js/commands/opendir.js"></script>
	<script src="js/commands/paste.js"></script>
	<script src="js/commands/places.js"></script>
	<script src="js/commands/quicklook.js"></script>
	<script src="js/commands/quicklook.plugins.js"></script>
	<script src="js/commands/reload.js"></script>
	<script src="js/commands/rename.js"></script>
	<script src="js/commands/resize.js"></script>
	<script src="js/commands/restore.js"></script>
	<script src="js/commands/rm.js"></script>
	<script src="js/commands/search.js"></script>
	<script src="js/commands/selectall.js"></script>
	<script src="js/commands/selectinvert.js"></script>
	<script src="js/commands/selectnone.js"></script>
	<script src="js/commands/sort.js"></script>
	<script src="js/commands/undo.js"></script>
	<script src="js/commands/up.js"></script>
	<script src="js/commands/upload.js"></script>
	<script src="js/commands/view.js"></script>

	<!-- elfinder 1.x connector API support (OPTIONAL) -->
	<script src="js/proxy/elFinderSupportVer1.js"></script>

	<!-- Extra contents editors (OPTIONAL) -->
	<script src="js/extras/editors.default.js"></script>

	<!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
	<script src="js/extras/quicklook.googledocs.js"></script>
	
	<!-- elFinder Basic Auth JS -->
		<script src="js/elfinderBasicAuth.js"></script>

	<!-- elfinder initialization  -->
	<script>
		(function($){
			var i18nPath = 'js/i18n',
				start = function(lng) {
					$().ready(function() {
						var elf = $('#elfinder').elfinder({
							// Documentation for client options:
							// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
							baseUrl : './',
							lang : lng,
							url	 : 'php/connector.php'	// connector URL (REQUIRED)
						}).elfinder('instance');
					});
				},
				loct = window.location.search,
				full_lng, locm, lng;
			
			// detect language
			if (loct && (locm = loct.match(/lang=([a-zA-Z_-]+)/))) {
				full_lng = locm[1];
			} else {
				full_lng = (navigator.browserLanguage || navigator.language || navigator.userLanguage);
			}
			lng = full_lng.substr(0,2);
			if (lng == 'ja') lng = 'jp';
			else if (lng == 'pt') lng = 'pt_BR';
			else if (lng == 'zh') lng = (full_lng.substr(0,5) == 'zh-tw')? 'zh_TW' : 'zh_CN';
	
			if (lng != 'en') {
				$.ajax({
					url : i18nPath+'/elfinder.'+lng+'.js',
					cache : true,
					dataType : 'script'
				})
				.done(function() {
					start(lng);
				})
				.fail(function() {
					start('fr');
				});
			} else {
				start(lng);
			}
		})(jQuery);
		

	</script>
	
</head>
<body>
	<div id="elfinder"></div>
</body>
</html>


<?php } ?>