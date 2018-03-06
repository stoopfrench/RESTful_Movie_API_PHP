<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//GENRE INDEX --------------------------------------------------------------
$get_genre_index = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	$genreArray = [];
	$genreCount = [];

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

    $responseData = array_map(function($value) use ($genreCount){
		return [	
			"genre" => $value,
			"movies" => $genreCount[$value],
   			"request" => [
   				"type" => "GET",
   				"description" => "get a list of movies from this Genre",
   				"url" => "/api/genre/" . $value
   			]
		];
	},$newGenreArray);

	return $response->withJson([
		"results" => count($newGenreArray),
		"data" => $responseData
	],200);
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

    $responseData = array_map(function($value){
		return [	
			"title" => $value['title'],
   			"year" => $value['year'],
   			"genres" => $value['genres'],
   			"id" => $value['id'],
   			"request" => [
   				"type" => "GET",
   				"description" => "get details about movie by ID",
   				"url" => "/api/titles/" . $value['id']
   			]
		];
	},$moviesByGenre);

	return $response->withJson([
		"genre" => $genre,
		"results" => count($moviesByGenre),
		"data" => $responseData
	],200);
};

//RENAME A GENRE
/*$rename_genre = function(Request $request, Response $response) {
	require_once('../api/config/db.php');

	$genre = $request->getAttribute('genre');
	$newGenre = $request->getParsedBody('newName');

	$query = "SELECT genres FROM movies ORDER BY id";
	$result = $mysqli->query($query);

	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	foreach ($data as $value) {
		$genreString = $value['genres'];
		$genres = explode("|", $genreString);
		// var_dump($genres);
		foreach ($genres as $key => $value) {
			// var_dump($key,$value);
			if($value === $genre) {
				// var_dump($key,$value);
				array_splice($genres, $key, 1, $newGenre);
			}
		}
	}
	var_dump($genres);
};*/





















