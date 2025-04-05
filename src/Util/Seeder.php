<?php

namespace App\Util;

use PDO;

abstract class Seeder
{
  protected PDO $db;

  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  abstract public function run(): void;

  protected function createTable(string $table, array $columns): void
  {
    $columnDefinitions = [];
    foreach ($columns as $name => $definition) {
      $columnDefinitions[] = "$name $definition";
    }

    $sql = "CREATE TABLE IF NOT EXISTS $table (
            " . implode(",\n", $columnDefinitions) . "
        )";

    $this->db->exec($sql);
  }
}
