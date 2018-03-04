<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


//GET ALL MOVIES
$app->get('/api/titles', function(Request $request, Response $response){
	require_once('../src/config/db.php');

	$query = "SELECT * FROM movies";
	$result = $mysqli->query($query);


	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}

	header('Content-Type: application/json');
	echo json_encode($data);
});

//GET MOVIE BY ID
$app->get('/api/titles/{id}', function(Request $request, Response $response){
	require_once('../src/config/db.php');
	
	$id = $request->getAttribute('id');

	$query = "SELECT * FROM movies WHERE id = $id";
	$result = $mysqli->query($query);

	$data[] = $result->fetch_assoc();
	header('Content-Type: application/json');
	echo json_encode($data);
});

//CREATE MOVIE
$app->post('/api/titles', function(Request $request, Response $response){
	require_once('../src/config/db.php');

	$query = "INSERT INTO movies (`title`,`year`,`genres`) VALUES(?,?,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sss", $a, $b, $c);

	$a = $request->getParsedBody()['title'];
	$b = $request->getParsedBody()['year'];
	$c = $request->getParsedBody()['genres'];

	$stmt->execute();

	echo '{"notice": {"text": "Movie Created"}';
});

//UPDATE MOVIE
$app->patch('/api/titles/{id}', function(Request $request, Response $response){
	require_once('../src/config/db.php');

	$id = $request->getAttribute('id');

	$query = "UPDATE `movies` SET `title` = ?, `year` = ?, `genres` = ? WHERE `movies`.`id` = $id";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sss", $a, $b, $c);

	$a = $request->getParsedBody()['title'];
	$b = $request->getParsedBody()['year'];
	$c = $request->getParsedBody()['genres'];

	$stmt->execute();

	echo '{"notice": {"text": "Movie Created"}';
});

//DELETE MOVIE
$app->delete('/api/titles/{id}', function(Request $request, Response $response){
	require_once('../src/config/db.php');

	$id = $request->getAttribute('id');
	$query = "DELETE FROM movies WHERE id = $id";

	$result = $mysqli->query($query);

	echo '{"notice":{"text":"Movie Deleted"}';
});








