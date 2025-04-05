<?php

namespace App\Service;

use PDO;
use App\Model\Product;


// Db service to handle db operations decoupled from the model
class Database
{
  public function __construct(private PDO $pdo) {}

  public function getProducts(): array
  {
    $stmt = $this->pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    return array_map(
      fn($row) => Product::fromArray($row),
      $stmt->fetchAll(PDO::FETCH_ASSOC)
    );
  }

  public function getProduct(int $id): ?Product
  {
    $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      return Product::fromArray($row);
    }
    return null;
  }

  public function createProduct(array $data): Product
  {
    $stmt = $this->pdo->prepare("
            INSERT INTO products (name, description, price, category)
            VALUES (?, ?, ?, ?)
        ");

    $stmt->execute([
      $data['name'],
      $data['description'],
      $data['price'],
      $data['category']
    ]);

    $data['id'] = $this->pdo->lastInsertId();
    return Product::fromArray($data);
  }

  public function updateProduct(int $id, array $data): bool
  {
    $stmt = $this->pdo->prepare("
            UPDATE products 
            SET name = ?, description = ?, price = ?, category = ?
            WHERE id = ?
        ");

    return $stmt->execute([
      $data['name'],
      $data['description'],
      $data['price'],
      $data['category'],
      $id
    ]);
  }

  public function deleteProduct(int $id): bool
  {
    $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
  }
}
