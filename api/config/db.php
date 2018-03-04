<?php
$host = 'localhost';
$user = 'root';
$pw = '';
$dbname = 'movie-database';

$mysqli=mysqli_connect($host, $user, $pw, $dbname);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

// Change character set to utf8
mysqli_set_charset($mysqli,"utf8");

?>