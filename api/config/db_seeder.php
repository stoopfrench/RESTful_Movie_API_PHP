<?php

$servername = "localhost:3306";
$username = "root";

$mysqli = mysqli_connect($servername, $username);

if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

// drop old database
$dropSql = "DROP DATABASE IF EXISTS movie_database";
if (mysqli_query($mysqli, $dropSql)) {
    echo "Database was successfully dropped\n";
} else {
    echo 'Error dropping database: ' . mysql_error() . "\n";
}

// create database
$createSql = "CREATE DATABASE movie_database";
if (mysqli_query($mysqli, $createSql)) {
    echo "Database 'movie_database' created successfully\n";
} else {
    echo "Error creating database: " . mysqli_error($mysqli);
}

mysqli_close($mysqli);

$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "movie_database";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// create movies table
$tableSql = "CREATE TABLE movies (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
title VARCHAR(255) NOT NULL,
year INT(11) NOT NULL,
created TIMESTAMP,
UNIQUE (title)
) CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($mysqli->query($tableSql) === TRUE) {
    echo "Table 'movies' created successfully\n";
} else {
    echo "Error creating this table: " . $mysqli->error . '\n';
}

// create genres table
$genreSql = "CREATE TABLE genres (
genre VARCHAR(500) NOT NULL,
title VARCHAR(255) NOT NULL
) CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($mysqli->query($genreSql) === TRUE) {
	echo "Table 'genres' created successfully\n";
} else {
	echo "Error creating this table: " . $mysqli->error;
}

/*// create year table
$yearSql = "CREATE TABLE years (
year int(11) NOT NULL,
title VARCHAR(255) NOT NULL
) CHARACTER SET utf8 COLLATE utf8_general_ci";

if ($mysqli->query($yearSql) === TRUE) {
	echo "Table 'years' created successfully\n";
} else {
	echo "Error creating this table: " . $mysqli->error;
}*/

$movieCount = 0;
$genreCount = 0;
// $yearCount = 0;

// Parse .csv file
$handle = fopen("../../htdocs/movie-api/api/config/sample_csvs/Movie-List.csv", "r");
if ($handle) { 
    while (($data = fgetcsv($handle)) !== false) {

    	$id = $data[0];
    	$title = mysqli_real_escape_string($mysqli,$data[1]);
    	$year = $data[2];
    	$genres = $data[3];

    	$insertMovies = "INSERT INTO movies (`id`,`title`,`year`) VALUES ('$id','$title','$year')";

    	if(mysqli_query($mysqli, $insertMovies)) {
    		++$movieCount;
    	} else {
		    echo "Error creating table: " . mysqli_error($mysqli);
    	}

    	$genreArray = explode("|",$genres);
    	foreach($genreArray as $key => $value) {

    		$insertGenres = "INSERT INTO genres (`genre`,`title`) VALUES ('$value','$title')";

	    	if(mysqli_query($mysqli, $insertGenres)) {
	    		++$genreCount;
	    	} else {
			    echo "Error creating table: " . mysqli_error($mysqli);
	    	}
    	}

/*    	$insertYears = "INSERT INTO years (`year`,`title`) VALUES ('$year','$title')";
    	
    	if(mysqli_query($mysqli, $insertYears)) {
    		++$yearCount;
    	} else {
		    echo "Error creating table: " . mysqli_error($mysqli);
    	}*/

    }  
	echo $movieCount . " lines were imported into 'movies' successfully\n";
	echo $genreCount . " lines were imported into 'genres' successfully\n";
	
    fclose($handle);
};

mysqli_close($mysqli);

