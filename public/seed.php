<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Seeder\ProductSeeder;
use DI\Container;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set up database connection
$dsn = sprintf(
  "mysql:host=%s;dbname=%s;charset=utf8mb4",
  $_ENV['DB_HOST'],
  $_ENV['DB_NAME']
);

$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
]);

// Run the seeder
$seeder = new ProductSeeder($pdo);
$seeder->run();

echo "Database seeded successfully!\n";
