# Citrus Management System

A modern web application built with Laravel 12, Vue 3, and Tailwind CSS for managing citrus product supply chains, vendors, and purchase orders.

## What is missing
- **Dockerfile**: Didn't feel confident in containerizing my project
- **Product Price Management**: Didn't get around to that
- **Postgres missing**: Same reason why the its why its not in a container

## Database Layout
- I chose the make a venders table with a type, distributor and supplier
- I separated the products from the prices
- DDL is here [https://github.com/johndug/citruse/blob/main/database_ddl.txt]

## Summary

I was a little foggy around the PM contacts the FSA to confirm requirements
And does the purchase_orders table need a distributer and a supplier?

## AI Assistance

- all tailwind was done by claude
- and generally I needed more assistance with the FE than the BE

## Features

- **User Management**: Role-based authentication (Admin, Manager, Sales)
- **Vendor Management**: Supplier and distributor management with contact information
- **Product Catalog**: Product management with yearly pricing
- **Purchase Orders**: Complete order lifecycle management with status tracking
- **API-First**: RESTful API with Laravel Sanctum authentication

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
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
DB_CONNECTION=sqlite 
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
