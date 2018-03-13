<?php

require_once '../api/controllers/genre_controller.php';

//GENRE INDEX
$app->get('/api/genre', $get_genre_index);

//GET MOVIES BY GENRE
$app->get('/api/genre/{genre}', $get_movies_by_genre);

//RENAME GENRE
$app->patch('/api/genre', $rename_genre);

