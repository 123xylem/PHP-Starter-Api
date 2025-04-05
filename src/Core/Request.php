<?php

namespace App\Core;

class Request
{
  private array $server;
  private array $query;
  private array $post;
  private ?string $body;
  private ?array $json;

  public function __construct(
    array $server,
    array $query,
    array $post,
    ?string $body = null
  ) {
    $this->server = $server;
    $this->query = $query;
    $this->post = $post;
    $this->body = $body;
    $this->json = $body ? json_decode($body, true) : null;
  }

  public function getMethod(): string
  {
    return $this->server['REQUEST_METHOD'];
  }

  public function getPath(): string
  {
    return parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
  }

  public function getQuery(string $key = null, $default = null)
  {
    if ($key === null) {
      return $this->query;
    }
    return $this->query[$key] ?? $default;
  }

  public function getPost(string $key = null, $default = null)
  {
    if ($key === null) {
      return $this->post;
    }
    return $this->post[$key] ?? $default;
  }

  public function getJson(): ?array
  {
    return $this->json;
  }

  public function getHeader(string $name): ?string
  {
    $headerName = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
    return $this->server[$headerName] ?? null;
  }
}
