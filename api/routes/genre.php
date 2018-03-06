<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../api/controllers/genre_controller.php';

//GENRE INDEX
$app->get('/api/genre', $get_genre_index);

//GET MOVIES BY GENRE
$app->get('/api/genre/{genre}', $get_movies_by_genre);

//RENAME A GENRE
// $app->patch('/api/genre/{genre}', $rename_genre);
