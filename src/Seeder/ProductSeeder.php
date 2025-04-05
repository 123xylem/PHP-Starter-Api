<?php

namespace App\Seeder;

use App\Util\Seeder;

class ProductSeeder extends Seeder
{
  public function run(): void
  {
    $this->createTable('products', [
      'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
      'name' => 'VARCHAR(255) NOT NULL',
      'description' => 'TEXT',
      'price' => 'DECIMAL(10,2) NOT NULL',
      'category' => 'VARCHAR(100)',
      'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
    ]);

    $products = [
      [
        'name' => 'Laptop Pro',
        'description' => 'High-performance laptop for professionals',
        'price' => 1299.99,
        'category' => 'Electronics'
      ],
      [
        'name' => 'Wireless Headphones',
        'description' => 'Noise-cancelling wireless headphones',
        'price' => 199.99,
        'category' => 'Electronics'
      ],
      [
        'name' => 'Smart Watch',
        'description' => 'Fitness tracking smart watch',
        'price' => 249.99,
        'category' => 'Wearables'
      ],
      [
        'name' => 'Coffee Maker',
        'description' => 'Programmable coffee maker',
        'price' => 89.99,
        'category' => 'Kitchen'
      ],
      [
        'name' => 'Desk Chair',
        'description' => 'Ergonomic office chair',
        'price' => 299.99,
        'category' => 'Furniture'
      ]
    ];

    $stmt = $this->db->prepare("
            INSERT INTO products (name, description, price, category)
            VALUES (:name, :description, :price, :category)
        ");

    foreach ($products as $product) {
      // Ensure price is properly formatted as decimal
      $product['price'] = (float) $product['price'];
      $stmt->execute($product);
    }
  }
}
