<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//GET ALL MOVIES -----------------------------------------------------------
$get_all_titles = function(Request $request, Response $response) {
	require_once('../api/config/db.php');

	$query = "SELECT * , COUNT(year) AS yearCount FROM movies GROUP BY title ORDER BY yearCount";

	// -- $yearCountQuery = "SELECT year, COUNT(year) AS yearCount FROM movies GROUP BY year ORDER BY yearCount DESC";
		
	try {

		// $yearCountResult = $mysqli->query($yearCountQuery);

		// while($row = $yearCountResult->fetch_assoc()) {
		// 	$yearCountData[] = $row;
		// }
		// var_dump($yearCountData);

		$result = $mysqli->query($query);

		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		var_dump($data);

		// var_dump($data);


/*	    $responseData = array_map(function($value){
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

		return $response->withJson([
				"results" => count($data),
		   		"data" => $responseData
			],200);*/

	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);
	}
};

//GET MOVIE BY ID ----------------------------------------------------------
$get_movie_by_id = function(Request $request, Response $response) {
	require_once('../api/config/db.php');
	
	$id = $request->getAttribute('id');

	$query = "SELECT movies.* , GROUP_CONCAT(genres.genre SEPARATOR '|') AS combGenres, years.year 
	FROM movies INNER JOIN genres ON genres.title = movies.title INNER JOIN years ON years.title = movies.title 
	WHERE movies.id = '$id'";

	try {

		$result = $mysqli->query($query);
		$data[] = $result->fetch_assoc();

		if ($data[0]['title'] === null && $data[0]['year'] === null && $data[0]['genres'] === null && $data[0]['id'] === null) {
			return $response->withJson([
				"error" => [
					"message" => "No movie found with that ID"
				]
			],404);
		}

		return $response->withJson([
			"results" => count($data),
			"data" => [
				"title" => $data[0]['title'],
				"year" => $data[0]['year'],
				"genres" => $data[0]['combGenres'],
				"id" => $data[0]['id'],
				"created" => $data[0]['created']
			],
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

	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);
	}
};

//CREATE MOVIE -------------------------------------------------------------
$create_new_movie = function(Request $request, Response $response) {
	require_once('../api/config/db.php');

	$requestGenres = $request->getParsedBody()['genres'];
	$splitRequestGenres = preg_split("/[\s,]+/", $requestGenres);
	$joinedGenres = implode('|', $splitRequestGenres);

	if($request->getParsedBody()['title'] === null || $request->getParsedBody()['year'] === null || $request->getParsedBody()['genres'] === null) {
		return $response->withJson([
			"error" => [
				"message" => "Invalid post request",
				"template" => "{ title: <new name(string)>, year: <new year(number)>, genres: <new genres ( seperated by , )(string)> }"
			]
		], 500);
	}

	$moviesQuery = "INSERT INTO movies (`title`) VALUES (?)";

	try {
		$stmt = $mysqli->prepare($moviesQuery);
		$stmt->bind_param("s", $a);

		$a = $request->getParsedBody()['title'];

		$stmt->execute();

	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);
	}

	$genresQuery = "INSERT INTO genres (`genre`, `title`) VALUES (?,?)";
	
	try {
		
		$stmt = $mysqli->prepare($genresQuery);
		$stmt->bind_param("ss", $a, $b);
		
		foreach ($splitRequestGenres as $value) {
			$a = $value;
			$b = $request->getParsedBody()['title'];

			$stmt->execute();
		}
	
	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);
	}

	$yearsQuery = "INSERT INTO years (`year`,`title`) VALUES (?,?)";

	try {
		
		$stmt = $mysqli->prepare($yearsQuery);
		$stmt->bind_param("ss", $a, $b);
		
			$a = $request->getParsedBody()['year'];
			$b = $request->getParsedBody()['title'];

			$stmt->execute();
	
	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);
	}
		return $response->withJson([
			"message" => "New Movie has been Created",
			"created" => [
				"title" => $request->getParsedBody()['title'],
				"year" => $request->getParsedBody()['year'],
				"genres" => $joinedGenres,
			],
			"requests" => [
				"type" => "GET",
				"description" => "get a list of all movies",
				"url" => "/api/titles"
			]
		],201);
};

//UPDATE MOVIE -------------------------------------------------------------
$update_movie_by_id = function(Request $request, Response $response) {
	require_once('../api/config/db.php');

	$id = $request->getAttribute('id');	
	$updates = $request->getParsedBody();
	$movieCheckQuery = "SELECT EXISTS(SELECT 1 FROM movies WHERE id = $id) AS mycheck";

	try {
		$movieCheckResult = $mysqli->query($movieCheckQuery);
		$movieCheckData = $movieCheckResult->fetch_assoc();

		if($movieCheckData['mycheck'] === '0') {
			return $response->withJson([
				"error" => [
					"message" => "No movie found with that ID"
				]
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

	} catch(Throwable $t) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $t
			]
		],500);
	}
};

//DELETE MOVIE -------------------------------------------------------------
$delete_movie_by_id = function(Request $request, Response $response) {
	require_once('../api/config/db.php');

	$id = $request->getAttribute('id');

	$movieCheckQuery = "SELECT EXISTS(SELECT 1 FROM movies WHERE id = $id) AS mycheck";

	$movieCheckResult = $mysqli->query($movieCheckQuery);
	$movieCheckdata = $movieCheckResult->fetch_assoc();

	if($movieCheckdata['mycheck'] === '0') {
		return $response->withJson([
			"error" => [
				"message" => "No movie found with that ID"
			]
		],404);
	}

	$query = "DELETE movies.*, genres.*, years.* 
				FROM movies INNER JOIN genres ON genres.title = movies.title INNER JOIN years ON years.title = movies.title 
				WHERE id = $id";

	try {
		
		$result = $mysqli->query($query);

		return $response->withJson([
			"message" => "Movie has been Deleted",
			"request" => [
				"type" => "GET",
				"description" => "get a new list of all movies",
				"url" => "/api/titles"
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




