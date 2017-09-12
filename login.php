<?php 
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
	<?php if(isset($isExpired) && $isExpired):?>
		<div style="color:red">Ce lien a expiré</div>
	<?php endif;?>
	<form action='' method='post' autocomplete='off'>
		<p>Login: <input type="text" name="username" value=""></p>
		<p>Mot de passe: <input type="password" name="password" value=""></p>
		<p><input type="submit" name="submit" value="Login"></p>    
	</form>
</body>