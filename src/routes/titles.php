<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//GET ALL MOVIES
$app->get('/api/titles', function(Request $request, Response $response){
	$sql = "SELECT * FROM movies";

	try{
		$db = new db();
		$db = $db->connect();

		$stmt = $db->query($sql);
		$movies = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($movies);

	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

//GET MOVIE BY ID
$app->get('/api/titles/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	$sql = "SELECT FROM movies WHERE id = $id";

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

//CREATE MOVIE
$app->post('/api/titles', function(Request $request, Response $response){
	$title = $request->getParam('title');
	$year = $request->getParam('year');
	$genres = $request->getParam('genres');

	$sql = "INSERT INTO movies (title,year,genres) VALUES(:title,:year,:genres)";

	try{
		$db = new db();
		$db = $db->connect();

		$stmt = $db->prepare($sql);

		$stmt->bindParam(':title',$title);
		$stmt->bindParam(':year',$year);
		$stmt->bindParam(':genres',$genres);

		$stmt->execute();

		echo '{"notice": {"text": "Movie Created"}';

	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

//UPDATE MOVIE
$app->put('/api/titles/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	$title = $request->getParam('title');
	$year = $request->getParam('year');
	$genres = $request->getParam('genres');

	$sql = "UPDATE movies SET 
				title = :title,
				year = :year,
				genres = :genres
			WHERE id = $id";

	try{
		$db = new db();
		$db = $db->connect();

		$stmt = $db->prepare($sql);

		$stmt->bindParam(':title',$title);
		$stmt->bindParam(':year',$year);
		$stmt->bindParam(':genres',$genres);

		$stmt->execute();

		echo '{"notice": {"text": "Movie Updated"}';

	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});

//DELETE MOVIE
$app->delete('/api/titles/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	$sql = "DELETE FROM movies WHERE id = $id";

	try{
		$db = new db();
		$db = $db->connect();

		$stmt = $db->prepare($sql);
		$stmt->execute();
		$db = null;

		echo '{"notice": {"text": "Movie Deleted"}';


	} catch(PDOException $e){
		echo '{"error": {"text": '.$e->getMessage().'}';
	}
});








