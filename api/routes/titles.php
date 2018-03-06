<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../api/controllers/titles_controller.php';

//GET ALL MOVIES
$app->get('/api/titles', $get_all_titles);

//GET MOVIE BY ID
$app->get('/api/titles/{id}', $get_movie_by_id);

//CREATE MOVIE
$app->post('/api/titles', $create_new_movie);

//UPDATE MOVIE
$app->patch('/api/titles/{id}', $update_movie_by_id);

//DELETE MOVIE
$app->delete('/api/titles/{id}', $delete_movie_by_id);








