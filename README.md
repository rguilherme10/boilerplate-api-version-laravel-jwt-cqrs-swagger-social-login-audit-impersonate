# Backend API

This is the backend API for the application, built with Laravel.

## Features

- **Versioned API**: The API is versioned to allow for backward compatibility and smooth transitions.
- **CQRS Pattern**: Implements Command Query Responsibility Segregation (CQRS) for better separation of concerns and scalability.
- **JWT Authentication**: Secure API authentication using JSON Web Tokens.
- **Auditing**: Comprehensive auditing of data changes using `owen-it/laravel-auditing`.
- **Activity Log**: Detailed activity logging using `spatie/laravel-activitylog`.
- **Impersonate**: Functionality to impersonate other users for administrative purposes.
- **Social Login**: Integration with social providers for user authentication.

## API Documentation

The API documentation is available via Swagger UI.

- **Swagger UI**: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation) (assuming your local server runs on port 8000)

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/rguilherme10/boilerplate-api-version-laravel-jwt-cqrs-swagger-social-login-audit-impersonate.git
   cd boilerplate-api-version-laravel-jwt-cqrs-swagger-social-login-audit-impersonate
   ```

2. **Install Composer dependencies:**

   ```bash
   composer install
   ```

3. **Install Node.js dependencies:**

   ```bash
   npm install
   ```

4. **Create a copy of the `.env.example` file and name it `.env`:**

   ```bash
   cp .env.example .env
   ```

5. **Generate an application key:**

   ```bash
   php artisan key:generate
   ```

6. **Configure your database in the `.env` file.**
    DB_CONNECTION=sqlite
    DB_DATABASE={PATH}\\db.sqlite

7. **Run database migrations:**

   ```bash
   php artisan migrate
   ```

8. **(Optional) Seed the database:**

   ```bash
   php artisan db:seed
   ```

9. **Start the development server:**

    ```bash
    php artisan serve
    ```

    And for Vite assets:

    ```bash
    npm run dev
    ```

## Configuration

### SWAGGER DOCUMENTATION

```bash
php artisan l5-swagger:generate
```

### JWT Authentication

The JWT secret key needs to be generated:

```bash
php artisan jwt:secret
```