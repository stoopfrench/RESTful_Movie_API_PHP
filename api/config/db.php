<?php

$app_env = getenv('APP_ENV');
if($app_env === 'testing') {
	$dotenv = new Dotenv\Dotenv('../','.env.testing');
	$dotenv->overload();
} else {
	$dotenv = new Dotenv\Dotenv('../');
	$dotenv->load();	
}

$host = getenv('DBHOST');
$user = getenv('DBUSER');
$pw = getenv('DBPW');
$dbname = getenv('DBNAME');

$mysqli=mysqli_connect($host, $user, $pw, $dbname);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}





