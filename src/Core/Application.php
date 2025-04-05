<?php

namespace App\Core;

use DI\Container;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Application
{
  private Container $container;
  private array $middleware = [];

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  // Add middleware if needed
  // public function addMiddleware(string $middlewareClass): self
  // {
  //   $this->middleware[] = $middlewareClass;
  //   return $this;
  // }

  public function run(): void
  {
    // create dispatcher to handle routes
    $dispatcher = simpleDispatcher(function (RouteCollector $r) {
      $routes = require __DIR__ . '/../routes.php';
      $routes($r);
    });

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    //Get Route info by dispatching it to fast route dispatcher
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

    $response = $this->handleRoute($routeInfo);
    $this->sendResponse($response);
  }

  private function handleRoute(array $routeInfo): array
  {
    switch ($routeInfo[0]) {
      case Dispatcher::NOT_FOUND:
        return ['status' => 404, 'data' => ['error' => 'Not Found']];
      case Dispatcher::METHOD_NOT_ALLOWED:
        return ['status' => 405, 'data' => ['error' => 'Method Not Allowed']];
        //IF route Found get the handler and request params
      case Dispatcher::FOUND:
        [$handler, $vars] = [$routeInfo[1], $routeInfo[2]];

        // Apply middleware
        $request = new Request($_SERVER, $_GET, $_POST, file_get_contents('php://input'));
        foreach ($this->middleware as $middleware) {
          $middlewareInstance = $this->container->get($middleware);
          $request = $middlewareInstance->process($request);
        }

        // Extract data from request
        $requestData = [];
        if (in_array($request->getMethod(), ['POST', 'PUT'])) {
          $requestData = $request->getJson() ?? [];
        }

        // Merge URL parameters and request data
        $params = $vars;
        if (!empty($requestData)) {
          $params['data'] = $requestData;
        }

        // Execute controller action with params
        return $this->container->call($handler, $params);
    }

    // Default error case
    return ['status' => 500, 'data' => ['error' => 'Unknown dispatch status']];
  }

  private function sendResponse(array $response): void
  {
    $status = $response['status'] ?? 200;
    $data = $response['data'] ?? [];

    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
  }
}
