# Laravel E-Commerce Practice Project

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
- **Product Management**: Full CRUD (Create, Read, Update, Delete) functionality for products, managed via DataTables.
- **Category Management**: Full CRUD functionality for product categories.
- **Order Management**: View order details, update statuses, and cancel orders.
- **Review Management**: View and moderate customer-submitted reviews.

## Technology Stack

- **Backend**: 
  - PHP 8.3
  - Laravel 12
  - Laravel Passport (for API authentication)
  - Yajra DataTables (for admin tables)

- **Frontend**:
  - Blade Templates
  - Tailwind CSS 4
  - Vite
  - Chart.js
  - SweetAlert2 (for interactive alerts)

## Installation and Setup

Follow these steps to get the project up and running on your local machine.

1.  **Clone the repository**:
    ```bash
    git clone <your-repository-url>
    cd my-laravel-practice-app
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
    - Open the `.env` file and configure your database connection details (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
    - Run the database migrations and seeders to create the necessary tables and sample data:
      ```bash
      php artisan migrate --seed
      ```

5.  **Passport Setup**:
    - Install Laravel Passport for API authentication:
      ```bash
      php artisan passport:install
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
