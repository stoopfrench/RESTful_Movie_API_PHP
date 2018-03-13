<?php

$host = 'localhost';
$user = 'root';
$pw = '';
$dbname = 'movie_database';

$mysqli=mysqli_connect($host, $user, $pw, $dbname);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_set_charset($mysqli,"utf8");





