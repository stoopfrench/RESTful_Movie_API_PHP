<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//YEAR INDEX ---------------------------------------------------------------
$get_year_index = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$query = "SELECT year, COUNT(year) AS count 
				FROM years 
				GROUP BY year 
				ORDER BY count DESC";

	try {
		$result = $mysqli->query($query);

		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
	    $responseData = array_map(function($value){
			return [	
				"year" => $value['year'],
				"movies" => $value['count'],
				"request" => [
					"type" => "GET",
					"description" => "get a list of movies from this Year",
					"url" => "/api/genre/" . $value['year']
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

//GET MOVIES BY YEAR -------------------------------------------------------
$get_movies_by_year = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$year = $request->getAttribute('year');

	$query = "SELECT movies.*, GROUP_CONCAT(genres.genre SEPARATOR '|') AS combGenres 
				FROM movies INNER JOIN genres ON genres.title = movies.title INNER JOIN years ON years.title = movies.title 
				WHERE years.year = '$year' 
				GROUP BY title 
				ORDER BY title";
	
	try{
		$result = $mysqli->query($query);

		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}

		if(count($data) === 0) {
			return $response->withJson([
				"error" => [
					"message" => "Year not found",
					"request" => [
						"type" => "GET",
						"description" => "Get a list of Years",
						"url" => "/api/year"
					]
				]
			],404);
		}
	    $responseData = array_map(function($value){
			return [	
				"title" => $value['title'],
	   			"id" => $value['id'],
	   			"genres" => $value['combGenres'],
	   			"request" => [
	   				"type" => "GET",
	   				"description" => "get details about movie with this ID",
	   				"url" => "/api/titles/" . $value['id']
	   			]
			];
		},$data);

		return $response->withJson([
			"year" => $year,
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




