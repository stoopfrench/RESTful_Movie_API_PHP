<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//GENRE INDEX --------------------------------------------------------------
$get_genre_index = function(Request $request, Response $response) {
	require_once('../api/config/db.php');

	parse_str($_SERVER['QUERY_STRING'], $queries[]);

	$query = "SELECT genre, COUNT(genre) AS count 
		FROM genres 
		GROUP BY genre 
		ORDER BY count DESC";
	
	try {
		$result = $mysqli->query($query);

		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		if(array_key_exists('sort', $queries[0])) {
			if ($queries[0]['sort'] === 'genre') {
				usort($data, function($a,$b) {
					return strcmp($a['genre'], $b['genre']);
				});
			}
		}		

	    $responseData = array_map(function($value) {
			return [	
				"genre" => $value['genre'],
				"movies" => $value['count'],
	   			"request" => [
	   				"type" => "GET",
	   				"description" => "get a list of movies from this Genre",
	   				"url" => "/api/genre/" . $value['genre']
	   			]
			];
		},$data);

		return $response->withJson([
			"results" => count($data),
			"data" => $responseData
		],200);

	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);
	}
};

//GET MOVIES BY GENRE ------------------------------------------------------
$get_movies_by_genre = function(Request $request, Response $response) {
	require_once('../api/config/db.php');
	
	$genre = $request->getAttribute('genre');

	try {
		$query = "SELECT movies.* 
			FROM movies INNER JOIN genres ON genres.title = movies.title
			WHERE genres.genre = '$genre' 
			GROUP BY title 
			ORDER BY title";

		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		$responseData = array_map(function($value) {
			return [
				"title" => $value['title'],
				"year" => $value['year'],
				"id" => $value['id'],
				"request" => [
					"type" => "GET",
					"description" => "Get details about this movie",
					"url" => "/api/title/" . $value['id']
				]
			];
		},$data);

		return $response->withJson([
			"genre" => $genre,
			"results" => count($data),
			"data" => $responseData
		],200);

	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);		
	}
};

// RENAME A GENRE ----------------------------------------------------------
$rename_genre = function(Request $request, Response $response) {
	require_once('../api/config/db.php');

	$genre = $request->getParsedBody()['genre'];
	$newName = $request->getParsedBody()['newName'];

	if($newName === null || $genre === null) {
		return $response->withJson([
			"error" => [
				"message" => "Invalid patch format",
				"template" => [
					"genre" => "<genre to rename>",
					"newName" => "<new name for genre>"
				]
			]
		],500);
	}
	
	$checkQuery = "SELECT EXISTS(SELECT 1 FROM genres WHERE genre = '$genre') AS mycheck";

	$checkQueryResult = $mysqli->query($checkQuery);
	$checkQueryData = $checkQueryResult->fetch_assoc();

	if($checkQueryData['mycheck'] === '0') {
		return $response->withJson([
			"error" => [
				"message" => "Genre not found"
			]
		],404);		
	}

	try {

		$query = "UPDATE `genres` SET `genre` = ? WHERE `genre` = '$genre'";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $newName);

		$stmt->execute();
			
		return $response->withJson([
			"message" => $genre . " has been renamed " . $newName,
			"request" => [
				"type" => "GET",
				"description" => "get a list of all movies",
				"url" => "/api/genre/". $newName
			]
		],200);

	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);	
	}
};






