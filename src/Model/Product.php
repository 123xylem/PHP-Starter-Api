<?php

namespace App\Model;

class Product
{
  public function __construct(
    public ?int $id = null,
    public string $name = '',
    public string $description = '',
    public float $price = 0.0,
    public string $category = '',
    public ?string $created_at = null
  ) {}

  public static function fromArray(array $data): self
  {
    return new self(
      $data['id'] ?? null,
      $data['name'] ?? '',
      $data['description'] ?? '',
      (float) ($data['price'] ?? 0),
      $data['category'] ?? '',
      $data['created_at'] ?? null
    );
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'price' => $this->price,
      'category' => $this->category,
      'created_at' => $this->created_at
    ];
  }
}
