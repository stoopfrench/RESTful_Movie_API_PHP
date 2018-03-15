<?php

$dotenv = new Dotenv\Dotenv('../');
$dotenv->load();

$host = getenv('DBHOST');
$user = getenv('DBUSER');
$pw = getenv('DBPW');
$dbname = getenv('DBNAME');

$mysqli=mysqli_connect($host, $user, $pw, $dbname);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_set_charset($mysqli,"utf8");





