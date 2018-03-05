<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//GET ALL MOVIES
$app->get('/api/genre', function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$query = "SELECT genres FROM movies";
	$result = $mysqli->query($query);


	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	foreach ($data as $key => $value) {
		$genreString = $value['genres'];
		$genres = explode("|", $genreString);
		var_dump($genres);
	}

		// foreach ($genreData as $key => $value) {
		// 	$genreString = $value;
		// 	$genres = explode("|", $genreString);
		// 	var_dump($genres);
		// }
	// $newResponse = $response->withJson($data,200);

	// return $newResponse;
});

//GET MOVIE BY ID
$app->get('/api/genre/{genre}', function(Request $request, Response $response){

});
