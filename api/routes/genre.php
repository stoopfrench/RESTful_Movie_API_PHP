<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//GET ALL MOVIES
$app->get('/api/genre', function(Request $request, Response $response){
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

	return $response->withJson($newGenreArray,200);

});

//GET MOVIE BY ID
$app->get('/api/genre/{genre}', function(Request $request, Response $response){

});
