<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->post('/read/{type}', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->addInfo("Read request of $args[type]");

    $data = $request->getParsedBody();
    print_r($data);
    return $response;
    // Render index view
    // return $this->renderer->render($response, 'index.phtml', $args);
});
