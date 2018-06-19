<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->post('/read/{type}', 'ReadabilityController:getExtractedContent');

?>


