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

	return $response->withJson($newYearArray,200);
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

	$newResponse = $response->withJson($data,200);

	return $newResponse;
};