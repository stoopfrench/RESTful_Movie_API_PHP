<?php

require_once '../api/controllers/year_controller.php';

//YEAR INDEX
$app->get('/api/year', $get_year_index);

//GET MOVIES BY YEAR
$app->get('/api/year/{year}', $get_movies_by_year);




