# NovaCart

This is a practice project built with Laravel, demonstrating a simple e-commerce application. It includes features for both customers and administrators, such as product browsing, a shopping cart, an admin dashboard for managing products and orders, and a complete user authentication system.

## Key Features

### Customer-Facing

- **Product Catalog**: Browse and view products with details and images.
- **User Authentication**: Secure registration, login, and password reset functionality.
- **Shopping Cart**: Add, update, and remove products from the cart.
- **Checkout Process**: A simple workflow for placing orders.
- **Customer Dashboard**: View order history and manage personal reviews.
- **Product Reviews**: Submit, edit, and view reviews for products.

### Admin Panel (`/admin`)

- **Dashboard**: An overview of site activity.
- **Product Management**: Full CRUD (Create, Read, Update, Delete) functionality for products, managed with Blade and HTMX.
- **Category Management**: Full CRUD functionality for product categories.
- **Order Management**: View order details, update statuses, and cancel orders.
- **Review Management**: View and moderate customer-submitted reviews.

## Technology Stack

- **Backend**: 
  - PHP 8.3
  - Laravel 12
  - Laravel Sanctum (for API authentication)

- **Frontend**:
  - Blade Templates
  - Tailwind CSS 4
  - Vite
  - Ky for small, consistent JSON requests
  - Chart.js
  - HTMX for lightweight HTML interactions
  - Native browser dialogs and local SVG icons

## Installation and Setup

Follow these steps to get the project up and running on your local machine.

1.  **Clone the repository**:
    ```bash
    git clone <your-repository-url>
      cd novacart
    ```

2.  **Install dependencies**:
    ```bash
    composer install
    npm install
    ```

3.  **Environment Configuration**:
    - Copy the example environment file:
      ```bash
      cp .env.example .env
      ```
    - Generate a new application key:
      ```bash
      php artisan key:generate
      ```

4.  **Database Setup**:
    - The default `.env.example` uses SQLite and file-based local drivers, so no MySQL/Redis service is required for development.
    - If you use MySQL, update the `DB_*` values in `.env` before migrating.
    - Run the database migrations and seeders to create the necessary tables:
      ```bash
      php artisan migrate --seed
      ```
    - Create an administrator explicitly with Tinker (the app never ships with a default password):
      ```bash
      php artisan tinker
      >>> App\\Models\\Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('change-this'), 'role' => 'admin']);
      ```

5.  **API authentication**:
    - Sanctum tokens are created by the /api/login endpoint. Send the returned bearer token to /api/me and /api/logout.
      ```bash
      php artisan passport:install
      ```

6.  **Public files**:
    ```bash
    php artisan storage:link
    ```

## Running the Application

This project includes a convenient script to start all necessary development services concurrently.

```bash
composer run dev
```

This command will:
- Start the PHP development server (`php artisan serve`).
- Start the queue listener (`php artisan queue:listen`).
- Compile frontend assets with Vite in watch mode (`npm run dev`).

The application will be available at `http://127.0.0.1:8000`.
