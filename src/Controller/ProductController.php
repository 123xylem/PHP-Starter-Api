<?php

namespace App\Controller;

use App\Service\Database;
use App\Model\Product;

class ProductController
{
  public function __construct(private Database $db) {}

  public function index(): array
  {
    return [
      'status' => 200,
      'data' => array_map(
        fn(Product $product) => $product->toArray(),
        $this->db->getProducts()
      )
    ];
  }

  public function show(int $id): array
  {
    if (!$product = $this->db->getProduct($id)) {
      return ['status' => 404, 'error' => 'Product not found'];
    }
    return ['status' => 200, 'data' => $product->toArray()];
  }

  public function create(array $data): array
  {
    $errors = $this->validate($data);
    if (!empty($errors)) {
      return ['status' => 422, 'errors' => $errors];
    }

    $product = $this->db->createProduct($data);
    return ['status' => 201, 'data' => $product->toArray()];
  }

  public function update(int $id, array $data): array
  {
    if (!$this->db->getProduct($id)) {
      return ['status' => 404, 'error' => 'Product not found'];
    }

    $errors = $this->validate($data);
    if (!empty($errors)) {
      return ['status' => 422, 'errors' => $errors];
    }

    $this->db->updateProduct($id, $data);
    return ['status' => 200, 'data' => ['updated' => true]];
  }

  public function delete(int $id): array
  {
    if (!$this->db->getProduct($id)) {
      return ['status' => 404, 'error' => 'Product not found'];
    }

    $this->db->deleteProduct($id);
    return ['status' => 200, 'data' => ['deleted' => true]];
  }

  private function validate(array $data): array
  {
    $errors = [];

    if (empty($data['name'])) {
      $errors['name'] = 'Name is required';
    }

    if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
      $errors['price'] = 'Price must be a positive number';
    }

    return $errors;
  }
}
