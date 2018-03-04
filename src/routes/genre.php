<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//GET ALL MOVIES
$app->get('/api/genre', function(Request $request, Response $response){
	$sql = "SELECT genres FROM movies";

	try{
		$db = new db();
		$db = $db->connect();

		$stmt = $db->query($sql);
		$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$db = null;
		echo json_encode($movies);

	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

//GET MOVIE BY ID
$app->get('/api/genre/{genre}', function(Request $request, Response $response){
	$genre = $request->getAttribute('genre');
	$sql = "SELECT FROM movies WHERE genres = $genre";

	try{
		$db = new db();
		$db = $db->connect();

		$stmt = $db->query($sql);
		$movie = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($movie);

	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});
