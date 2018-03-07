<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//YEAR INDEX ---------------------------------------------------------------
$get_year_index = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$yearArray = [];
	$query = "SELECT year FROM movies";

	try {
		$result = $mysqli->query($query);

		while($row = $result->fetch_assoc()) {
			$data[] = $row;
		}
		foreach ($data as $value) {
			array_push($yearArray, $value['year']);
		}
		$uniqueYears = array_unique($yearArray);
		$newYearArray = array_values($uniqueYears);
		$yearCount = array_count_values($yearArray);
		arsort($yearCount);

	    usort($newYearArray, function ($a, $b)  use ($yearCount) {

	        return $yearCount[$a] <= $yearCount[$b] ?  1 : -1;
	    });

	    $responseData = array_map(function($value) use ($yearCount){
			return [	
				"genre" => $value,
				"movies" => $yearCount[$value],
				"request" => [
					"type" => "GET",
					"description" => "get a list of movies from this Year",
					"url" => "/api/genre/" . $value
				]
			];
		},$newYearArray);

		return $response->withJson([
			"results" => count($newYearArray),
			"data" => $responseData
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

//GET MOVIES BY YEAR -------------------------------------------------------
$get_movies_by_year = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$year = $request->getAttribute('year');
	$query = "SELECT * FROM movies WHERE year = $year";
	
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

		usort($data, function($a,$b) {
			return strcmp($a['title'],$b['title']);
		});

	    $responseData = array_map(function($value){
			return [	
				"title" => $value['title'],
	   			"year" => $value['year'],
	   			"genres" => $value['genres'],
	   			"id" => $value['id'],
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

	} catch(Error $e) {
		return $response->withJson([
			"error" => [
				"message" => "Something has gone wrong",
				"error" => $e
			]
		],500);		
	}

};