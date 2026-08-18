# NovaCart

NovaCart is a small Laravel 12 store built as a first Laravel practice project. It demonstrates a complete Blade-based shopping flow with a customer area, an admin area, seeded demo data, and progressively enhanced interactions that remain usable as ordinary HTML.

## What is included

Customer features include product browsing, search and filtering, cart and checkout, order history, cancellation of pending orders, profile/password settings, light/dark/system appearance, and product reviews.

The admin area at `/dashboard` includes dashboard metrics, product and category CRUD, order status management, review moderation with an integrated modal, and customer account management with suspend/restore controls.

![NovaCart customer dashboard](docs/screenshots/customer-dashboard-light.png)

![NovaCart admin products](docs/screenshots/admin-products-light.png)

![NovaCart review moderation modal](docs/screenshots/admin-review-modal-light.png)

## Stack

- PHP 8.3+ and Laravel 12
- Blade, Tailwind CSS 4, Vite, and Chart.js
- HTMX for server-rendered partial updates
- Ky for small JSON requests; no jQuery or DataTables
- Laravel Sanctum for API token authentication
- Lightweight local SVG icons rendered through one Blade component
- SQLite by default for local development; MySQL is also supported

The frontend intentionally keeps JavaScript small: HTMX handles HTML updates, Ky handles JSON mutations, and Alpine is not required for the main store flows. Small vanilla modules cover modals, popovers, theme selection, toasts, and form helpers.

## Local setup

From the project directory (the local folder is now named `NovaCart`):

```bash
composer install
npm install
copy .env.example .env       # Windows PowerShell: Copy-Item .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open <http://127.0.0.1:8000>. For active frontend development, use `npm run dev` in a second terminal. The seeded demo database is SQLite and contains categories, products, customers, orders, and reviews so every main screen has useful content immediately.

### Demo accounts

| Area | Email | Password |
| --- | --- | --- |
| Admin | `admin@novacart.test` | `password` |
| Customer | `demo@novacart.test` | `password` |
| Customer | `maya@novacart.test` | `password` |
| Customer | `alex@novacart.test` | `password` |

Never use these credentials outside local development.

## Tests and quality checks

```bash
php artisan test --compact
npm run build
```

The GitHub Actions workflow runs Composer installation, `npm ci`, the production Vite build, SQLite migrations, and the Laravel test suite.

## Project notes

- `database/seeders/DemoDataSeeder.php` is the single source of the local showcase data.
- `docs/REPORT.md` records the modernization audit, fixes, and verification results.
- `docs/screenshots/` contains screenshots captured from the running local application.
- The original repository is [Sanguine3/NovaCart](https://github.com/Sanguine3/NovaCart).
