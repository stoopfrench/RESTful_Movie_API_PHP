<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

//YEAR INDEX ---------------------------------------------------------------
$get_year_index = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	$yearArray = [];

	$query = "SELECT year FROM movies";
	$result = $mysqli->query($query);

	while($row = $result->fetch_assoc()) {
		$data[] = $row;
	}
	foreach ($data as $value) {
		$yearString = $value['year'];
		$years = explode("|", $yearString);
		foreach ($years as $key => $value) {
			array_push($yearArray, $years[$key]);
		}
	}
	$uniqueYears = array_unique($yearArray);
	$newYearArray = array_values($uniqueYears);
	$yearCount = array_count_values($yearArray);
	arsort($yearCount);

    usort($newYearArray, function ($a, $b)  use ($yearCount) {

        return $yearCount[$a] <= $yearCount[$b] ?  1 : -1;
    });

    $responseData = array_map(function($value){
		return [	
			"genre" => $value,
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
};

//GET MOVIES BY YEAR -------------------------------------------------------
$get_movies_by_year = function(Request $request, Response $response){
	require_once('../api/config/db.php');
	
	$year = $request->getAttribute('year');

	$query = "SELECT * FROM movies WHERE year = $year";
	$result = $mysqli->query($query);

	while($row = $result->fetch_assoc()) {
		$data[] = $row;
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

};