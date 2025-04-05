# PHP Starter API 

## Best practice bare bones PHP Api

Modern REST API built with:
- PSR-4 autoloading
- Dependency Injection (PHP-DI)
- FastRoute for routing
- PDO for db operations
- Clean architecture (Controller/Service/Model)
- Type safety & strict types
- Environment-based config

## API Flow

1. **Entry Point** (`public/index.php`)
   - Bootstrap DI container
   - Register service factories
   - Initialize Application

2. **Routing** (`Application.php` + `routes.php`)
   - FastRoute dispatcher matches URL/method
   - Maps to controller@method
   - Extracts route params

3. **Request Processing**
   - Request object parses input
   - Container resolves dependencies
   - Controller handles business logic
   - Service layer manages data
   - Model handles data structure

4. **Response**
   - JSON serialization
   - HTTP status codes
   - Consistent response format

## How It Works

### 1. Request Comes In
- User sends request to endpoint (eg: `GET /api/products`)
- Request hits `public/index.php`

### 2. Setup Phase
- Loads env vars (db creds, debug mode)
- Creates container (stores "blueprints" for creating services)
- Sets up db connection blueprint
- Creates app instance

### 3. Routing
- App looks at URL & HTTP method
- Matches to controller method in `routes.php`
- Eg: `GET /api/products` → `ProductController@index`

### 4. Processing Request
- Creates Request object with all data
- Gets needed services from container
- Calls controller method with params
- Controller uses Database service to talk to db
- Database converts db rows to Product objects

### 5. Sending Response
- Controller returns array with status & data
- App converts to JSON
- Sends back to user

## Endpoints

```
GET    /api/products          - List all products
GET    /api/products/{id}     - Get single product
POST   /api/products          - Create product
PUT    /api/products/{id}     - Update product
DELETE /api/products/{id}     - Delete product
```

## Setup

1. Copy `.env.example` to `.env`
2. Set db creds in `.env`
3. Run `composer install`
4. Run `php public/seed.php` to setup db
5. Start server: `php -S localhost:8000 -t public`

## Project Structure

```
src/
  ├── Controller/    - Handles requests
  ├── Model/         - Data structures
  ├── Service/       - Business logic
  ├── Util/          - Helper tools
  ├── Core/          - Framework stuff
  └── routes.php     - URL mappings
```

## Key Files

- `public/index.php` - Entry point
- `src/Core/Application.php` - Main app logic
- `src/Controller/ProductController.php` - Product endpoints
- `src/Service/Database.php` - Db operations
- `src/Model/Product.php` - Product data structure
