<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//GET ALL MOVIES
$app->get('/api/genre', function(Request $request, Response $response){
	require_once('../api/config/db.php');
	$genreArray = array();

	$query = "SELECT genres FROM movies";
	$result = $mysqli->query($query);


	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	foreach ($data as $key => $value) {
		$genreString = $value['genres'];
		$genres = explode("|", $genreString);
		for($i = 0, $size = count($genres); $i < $size; ++$i) { 
			array_push($genreArray, $genres[$i]);
		}
	}
	$uniqueGenres = array_unique($genreArray);

	return $response->withJson($uniqueGenres);

});

//GET MOVIE BY ID
$app->get('/api/genre/{genre}', function(Request $request, Response $response){

});
