<?php

use FastRoute\RouteCollector;
use App\Controller\ProductController;

return function (RouteCollector $r) {
  // Product RESTful routes
  $r->get('/api/products', [ProductController::class, 'index']);
  $r->get('/api/products/{id:\d+}', [ProductController::class, 'show']);
  $r->post('/api/products', [ProductController::class, 'create']);
  $r->put('/api/products/{id:\d+}', [ProductController::class, 'update']);
  $r->delete('/api/products/{id:\d+}', [ProductController::class, 'delete']);
};
