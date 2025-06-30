# Citrus Management System

A modern web application built with Laravel 12, Vue 3, and Tailwind CSS for managing citrus product supply chains, vendors, and purchase orders.

## Features

- **User Management**: Role-based authentication (Admin, Manager, Sales)
- **Vendor Management**: Supplier and distributor management with contact information
- **Product Catalog**: Product management with yearly pricing
- **Purchase Orders**: Complete order lifecycle management with status tracking
- **Modern UI**: Responsive design with Tailwind CSS
- **API-First**: RESTful API with Laravel Sanctum authentication

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue 3 with Composition API
- **State Management**: Pinia
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ or PostgreSQL 13+
- Git

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd citruse
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Setup

Copy the environment file and configure your database:

```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=citruse
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed the Database (Optional)

```bash
php artisan db:seed
```

### 8. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 9. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Development

### Running the Application

1. **Start Laravel server**:
   ```bash
   php artisan serve
   ```

2. **Start Vite dev server** (in another terminal):
   ```bash
   npm run dev
   ```

### Database Structure

The application includes the following main tables:

- **users**: User management with role-based access
- **vendors**: Supplier and distributor information
- **products**: Product catalog with codes and descriptions
- **product_prices**: Yearly pricing for products
- **purchase_orders**: Order management with status tracking

### API Endpoints

The application provides RESTful API endpoints:

- `POST /api/login` - User authentication
- `GET /api/user/me` - Get current user
- `POST /api/logout` - User logout
- `GET /api/products` - List products
- `POST /api/products` - Create product
- `PUT /api/products/{code}` - Update product
- `GET /api/vendors` - List vendors
- `POST /api/vendors` - Create vendor
- `PUT /api/vendors/{id}` - Update vendor
- `GET /api/purchase-orders` - List purchase orders
- `POST /api/purchase-orders` - Create purchase order

### User Roles

- **Admin**: Full access to all features
- **Manager**: Can manage purchase orders
- **Sales**: Limited access to view data

## Production Deployment

### 1. Environment Configuration

Set production environment variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### 2. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Build Assets

```bash
npm run build
```

### 4. Set File Permissions

```bash
chmod -R 755 storage bootstrap/cache
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue in the GitHub repository or contact the development team.
