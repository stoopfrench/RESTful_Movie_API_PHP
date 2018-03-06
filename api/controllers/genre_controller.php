<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//GENRE INDEX --------------------------------------------------------------
$get_genre_index = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	$genreArray = [];

	$query = "SELECT genres FROM movies";
	$result = $mysqli->query($query);

	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	foreach ($data as $value) {
		$genreString = $value['genres'];
		$genres = explode("|", $genreString);
		foreach ($genres as $key => $value) {
			array_push($genreArray, $genres[$key]);
		}
	}
	$uniqueGenres = array_unique($genreArray);
	$newGenreArray = array_values($uniqueGenres);
	$genreCount = array_count_values($genreArray);
	
	arsort($genreCount);

    usort($newGenreArray, function ($a, $b)  use ($genreCount) {
        return $genreCount[$a] <= $genreCount[$b] ?  1 : -1;
    });

	return $response->withJson($newGenreArray,200);
};

//GET MOVIES BY GENRE ------------------------------------------------------
$get_movies_by_genre = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	$moviesByGenre = [];
	
	$genre = $request->getAttribute('genre');

	$query = "SELECT * FROM movies";
	$result = $mysqli->query($query);

	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	foreach ($data as $value) {
		$genreString = $value['genres'];
		$genres = explode("|", $genreString);
		
		if(in_array($genre, $genres)) {
			array_push($moviesByGenre, $value);
		}
	}
	usort($moviesByGenre, function($a,$b) {
		return strcmp($a['title'],$b['title']);
	});
	
	$newResponse = $response->withJson($moviesByGenre,200);

	return $newResponse;
};

