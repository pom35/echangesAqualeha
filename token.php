<?php 

include_once dirname(__FILE__).'/vendor/firebase/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

//génération du token
$array = array('login' => $_POST['filename'], 'path'=>$_POST['filepath'], 'dt_exp' => $_POST['date_exp']);
$jwt = JWT::encode(json_encode($array), 'eb4fa84c1f979473eb1cd2745019c79f');
echo $jwt;