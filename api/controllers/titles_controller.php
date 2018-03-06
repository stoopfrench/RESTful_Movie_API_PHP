<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//GET ALL MOVIES -----------------------------------------------------------
$get_all_titles = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	$yearArray = [];

	$query = "SELECT * FROM movies ORDER BY year";
	$result = $mysqli->query($query);

	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	foreach ($data as $year) {
		array_push($yearArray,$year['year']);	
	}
	$yearCount = array_count_values($yearArray);
	
	arsort($yearCount);

    usort($data, function ($a, $b)  use ($yearCount) {
    	if($a['year'] === $b['year']){
    		return strcmp($a['title'],$b['title']);
    	}
        return $yearCount[$a['year']] <= $yearCount[$b['year']] ?  1 : -1;
    });

	$newResponse = $response->withJson($data,200);

	return $newResponse;
};

//GET MOVIE BY ID ----------------------------------------------------------
$get_movie_by_id = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$id = $request->getAttribute('id');

	$query = "SELECT * FROM movies WHERE id = $id";
	$result = $mysqli->query($query);

	$data[] = $result->fetch_assoc();

	$newResponse = $response->withJson($data,200);

	return $newResponse;
};

//CREATE MOVIE -------------------------------------------------------------
$create_new_movie = function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$query = "INSERT INTO movies (`title`,`year`,`genres`) VALUES(?,?,?)";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param("sss", $a, $b, $c);

	$a = $request->getParsedBody()['title'];
	$b = $request->getParsedBody()['year'];
	$c = $request->getParsedBody()['genres'];

	$stmt->execute();

	$success = '{
		"notice": {
			"message": "Movie has been Created"
			}
		}';

	$decodedSuccess = json_decode($success,true);

	return $response->withJson($decodedSuccess,201);
};

//UPDATE MOVIE -------------------------------------------------------------
$update_movie_by_id = function(Request $request, Response $response){
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

	$a = (array_key_exists('title', $updates)) ? $updates['title'] : $movie['title'];
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
};

//DELETE MOVIE -------------------------------------------------------------
$delete_movie_by_id = function(Request $request, Response $response){
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
};









