<?php

require __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use App\Core\Application;
use App\Service\Database;

// Load env variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// error handling
error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG'] ?? '0');

// dependency injection
$container = new Container();

// db connection LazyLoaded factory functions
$container->set(PDO::class, function () {
  $dsn = sprintf(
    "mysql:host=%s;dbname=%s;charset=utf8mb4",
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME']
  );

  return new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
});

// Register Database service
$container->set(Database::class, function (Container $c) {
  return new Database($c->get(PDO::class));
});

$app = new Application($container);


$app->run();
