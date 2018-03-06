<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//GET ALL MOVIES -----------------------------------------------------------
$get_all_titles = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	$yearArray = [];

	$query = "SELECT * FROM movies ORDER BY year";
		
	try {
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

	    $responseData = array_map(function($value){
			return [	
				"title" => $value['title'],
				"year" => $value['year'],
				"id" => $value['id'],
				"request" => [
					"type" => "GET",
					"description" => "get details about movie by ID",
					"url" => "/api/titles/" . $value['id']
				]
			];
		},$data);
	   	$newResponse = [
	   		"results" => count($data),
	   		"data" => $responseData
	   	];

		return $response->withJson($newResponse,200);

	} catch(Error $e) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $e
			]
		],500);
	}
};

//GET MOVIE BY ID ----------------------------------------------------------
$get_movie_by_id = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$id = $request->getAttribute('id');

	$query = "SELECT * FROM movies WHERE id = $id";

	try {
		$result = $mysqli->query($query);

		$data[] = $result->fetch_assoc();

		if($data === null) {
			return $response->withJson([
				"error" => [
					"message" => "No movie found with that ID"
				]
			],404);
		}

		return $response->withJson([
			"results" => count($data),
			"data" => $data[0],
			"requests" => [
				"All" => [
					"type" => "GET",
					"description" => "Get a list of all movies",
					"url" => "/api/titles"
				],
				"Update" => [
					"type" => "PATCH",
					"description" => "Update this movie",
					"url" => "/api/titles/" . $data[0]['id']
				],
				"Delete" => [
					"type" => "DELETE",
					"description" => "Delete this movie",
					"url" => "/api/titles/" . $data[0]['id']
				]
			]
		],200);

	} catch(Error $e) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $e
			]
		],500);
	}
};

//CREATE MOVIE -------------------------------------------------------------
$create_new_movie = function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$query = "INSERT INTO movies (`title`,`year`,`genres`) VALUES(?,?,?)";

	try {
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sss", $a, $b, $c);

		$a = $request->getParsedBody()['title'];
		$b = $request->getParsedBody()['year'];
		
		$requestGenres = $request->getParsedBody()['genres'];
		$splitRequestGenres = preg_split("/[\s,]+/", $requestGenres);
		$c = implode("|", $splitRequestGenres);

		$stmt->execute();

		return $response->withJson([
			"message" => "New Movie has been Created",
			"created" => [
					"title" => $a,
					"year" => $b,
					"genres" => $c
				],
			"requests" => [
				"type" => "GET",
				"description" => "get a list of all movies",
				"url" => "/api/titles"
			]
		],201);

	} catch(Error $e) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $e
			]
		],500);
	}
};

//UPDATE MOVIE -------------------------------------------------------------
$update_movie_by_id = function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$id = $request->getAttribute('id');
	
	$updates = $request->getParsedBody();
	$movieQuery = "SELECT * FROM movies WHERE id = $id";

	try {
		$result = $mysqli->query($movieQuery);
		$data[] = $result->fetch_assoc();
		$movie = $data[0];

		if($movie === null) {
			return $response->withJson([
				"error" => "No movie found with that ID"
			],404);
		}

		$query = "UPDATE `movies` SET `title` = ?, `year` = ?, `genres` = ? WHERE `movies`.`id` = $id";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("sss", $a, $b, $c);

		$a = (array_key_exists('title', $updates)) ? $updates['title'] : $movie['title'];
		$b = (array_key_exists('year', $updates)) ? $updates['year'] : $movie['year'];
		$c = (array_key_exists('genres', $updates)) ? $updates['genres'] : $movie['genres'];

		$stmt->execute();

		return $response->withJson([
			"message" => "Movie has been updated",
			"updates" => $updates,
			"request" => [
				"type" => "GET",
				"description" => "get a list of all movies",
				"url" => "/api/titles/".$movie['id']
			]
		],200);

	} catch(Error $e) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $e
			]
		],500);
	}
};

//DELETE MOVIE -------------------------------------------------------------
$delete_movie_by_id = function(Request $request, Response $response){
	require_once('../api/config/db.php');

	$id = $request->getAttribute('id');
	$movieQuery = "SELECT * FROM movies WHERE id = $id";

	try {
		$movieResult = $mysqli->query($movieQuery);
		$data = $movieResult->fetch_assoc();
		if($data === null) {
			return $response->withJson([
				"error" => "No movie found with that ID"
			],404);
		}
		$query = "DELETE FROM movies WHERE id = $id";

		$result = $mysqli->query($query);
		
		return $response->withJson([
			"message" => "Movie has been Deleted",
			"request" => [
				"type" => "GET",
				"description" => "get a new list of all movies",
				"url" => "/api/titles"
			]
		],200);

	} catch(Error $e) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $e
			]
		],500);
	}
};









