<?php

use App\Infrastructure\Framework\Api\ApiRequest;
use App\Infrastructure\Framework\Api\ApiRequestMatcher;
use App\Infrastructure\Framework\Api\FrontController;

require '../vendor/autoload.php';
require '../src/assemble.php';

$routes = include '../src/routes.php';

$apiController = FrontController::createInstance();
foreach ($routes as list($method, $path, $handlerClassName)) {
    $apiController->registerHandler(
        $requestHandlerFactory->createRequestHandler($handlerClassName),
        new ApiRequestMatcher($method, $path)
    );
}

$apiController->handle(ApiRequest::fromGlobals())->render();
