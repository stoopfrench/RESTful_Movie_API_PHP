<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//GET ALL MOVIES
$app->get('/api/titles', function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$query = "SELECT * FROM movies";
	$result = $mysqli->query($query);


	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	$newResponse = $response->withJson($data,200);

	return $newResponse;
});

//GET MOVIE BY ID
$app->get('/api/titles/{id}', function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$id = $request->getAttribute('id');

	$query = "SELECT * FROM movies WHERE id = $id";
	$result = $mysqli->query($query);

	$data[] = $result->fetch_assoc();

	$newResponse = $response->withJson($data,200);

	return $newResponse;
});

//CREATE MOVIE
$app->post('/api/titles', function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$query = "INSERT INTO movies (`title`,`year`,`genres`) VALUES(?,?,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sss", $a, $b, $c);

	$a = $request->getParsedBody()['title'];
	$b = $request->getParsedBody()['year'];
	$c = $request->getParsedBody()['genres'];

	$stmt->execute();
	
	$newResponse = $response->withJson($data,201);

	return $newResponse;
});

//UPDATE MOVIE
$app->patch('/api/titles/{id}', function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$id = $request->getAttribute('id');
	
	$updates = $request->getParsedBody();
	$movieQuery = "SELECT * FROM movies WHERE id = $id";
	$result = $mysqli->query($movieQuery);
	$data[] = $result->fetch_assoc();
	$movie = $data[0];

	$query = "UPDATE `movies` SET `title` = ?, `year` = ?, `genres` = ? WHERE `movies`.`id` = $id";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sss", $a, $b, $c);

	$a = (array_key_exists('title', $updates)) ? $updates['title'] : $data['title'];
	$b = (array_key_exists('year', $updates)) ? $updates['year'] : $movie['year'];
	$c = (array_key_exists('genres', $updates)) ? $updates['genres'] : $movie['genres'];

	$stmt->execute();

	$success = '{
		"notice": {
			"message": "Movie has been updated"
			}
		}';

	$decodedSuccess = json_decode($success,true);

	return $response->withJson($decodedSuccess,200);

});

//DELETE MOVIE
$app->delete('/api/titles/{id}', function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$id = $request->getAttribute('id');
	$query = "DELETE FROM movies WHERE id = $id";

	$result = $mysqli->query($query);

	$success = '{
		"notice":{
			"message":"Movie has been Deleted"
			}
		}';
	
	$decodedSuccess = json_decode($success,true);
	
	return $response->withJson($decodedSuccess,200);

});








