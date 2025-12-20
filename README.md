# TurboPOS Backend

TurboPOS is a comprehensive Point of Sale (POS) system backend built with Laravel framework. It provides RESTful APIs for managing inventory, sales transactions, customers, and business operations for retail businesses.

## Features

### Core Functionality
- **Authentication & Authorization**: JWT-based authentication with user management
- **Product Management**: Complete product catalog with categories, pricing, and stock tracking
- **Customer Management**: Customer database with transport information
- **Sales Transactions**: Process sales with multiple payment methods and discount handling
- **Purchase Orders**: Manage supplier orders and inventory replenishment
- **Stock Opname**: Physical inventory counting and variance tracking
- **Reporting**: Comprehensive business reports and analytics
- **Barcode Generation**: Built-in barcode generation for products

### Technical Features
- RESTful API architecture
- JWT authentication with middleware protection
- Soft deletes for data integrity
- Database transactions for data consistency
- Pagination and filtering on all list endpoints
- Comprehensive validation and error handling
- Laravel Sanctum for API token management

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL/PostgreSQL database

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd turbopos-backend
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Configure your database and other settings in `.env` file.

5. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start the Development Server**
   ```bash
   composer run dev
   ```

## API Documentation

### Authentication
```
POST /api/login
```
Body: `{ "username": "string", "password": "string" }`

### Protected Endpoints (Require JWT Token)

#### Users
```
GET    /api/user           # List users with pagination
POST   /api/user           # Create user
GET    /api/user/{id}      # Get user details
PUT    /api/user/{id}      # Update user
DELETE /api/user/{id}      # Delete user (soft delete)
```

#### Categories
```
GET    /api/category       # List categories
POST   /api/category       # Create category
PUT    /api/category/{id}  # Update category
DELETE /api/category/{id}  # Delete category
```

#### Products
```
GET    /api/product        # List products with filters
POST   /api/product        # Create product
GET    /api/product/{id}   # Get product details
PUT    /api/product/{id}   # Update product
DELETE /api/product/{id}   # Delete product
```

#### Customers
```
GET    /api/customer       # List customers
POST   /api/customer       # Create customer
GET    /api/customer/{id}  # Get customer details
PUT    /api/customer/{id}  # Update customer
DELETE /api/customer/{id}  # Delete customer
```

#### Purchase Orders
```
GET    /api/purchase-order      # List purchase orders
POST   /api/purchase-order      # Create purchase order
GET    /api/purchase-order/{id} # Get purchase order details
PUT    /api/purchase-order/{id} # Update purchase order
DELETE /api/purchase-order/{id} # Delete purchase order
PUT    /api/purchase-order/{id}/status # Update order status
```

#### Sales Transactions
```
GET    /api/sales-transaction      # List sales transactions
POST   /api/sales-transaction      # Create sales transaction
GET    /api/sales-transaction/{id} # Get transaction details
PUT    /api/sales-transaction/{id} # Update transaction
DELETE /api/sales-transaction/{id} # Delete transaction
PUT    /api/sales-transaction/{id}/status # Update transaction status
```

#### Stock Opname
```
GET    /api/opname         # List opname records
POST   /api/opname         # Create opname record
GET    /api/opname/{id}    # Get opname details
```

#### Reports
```
GET /api/report/dashboard         # Dashboard summary
GET /api/report/purchase-order    # Purchase order reports
GET /api/report/sales-transaction # Sales transaction reports
GET /api/report/profit-loss-item  # Profit/loss by item
GET /api/report/profit-loss-category # Profit/loss by category
GET /api/report/stock             # Stock reports
```

#### Settings
```
GET /api/setting          # Get application settings
PUT /api/setting          # Update settings (admin only)
```

### Common Query Parameters
- `limit`: Number of items per page (default: 10)
- `page`: Page number for pagination
- `search`: Search term for relevant fields
- `user_id`: Filter by user ID
- `transaction_at_from/to`: Date range filters
- `status`: Filter by status (pending, completed, cancelled)

## Database Schema

### Main Tables
- `users` - System users with roles
- `categories` - Product categories
- `products` - Product catalog with pricing and stock
- `customers` - Customer information
- `customer_transports` - Customer delivery addresses
- `distributors` - Supplier information
- `purchase_orders` & `purchase_order_details` - Purchase management
- `sales_transactions` & `sales_transaction_details` - Sales management
- `opnames` & `opname_details` - Stock opname records
- `settings` - Application configuration

## Development

### Code Style
This project uses Laravel Pint for code formatting:
```bash
./vendor/bin/pint
```

### Testing
Run the test suite:
```bash
php artisan test
```

### API Testing
Use tools like Postman or Insomnia for API testing. Import the collection from `/docs` directory if available.

## Security
- JWT tokens expire and require refresh
- Password hashing using Laravel's secure methods
- Input validation on all endpoints
- SQL injection protection via Eloquent ORM
- XSS protection via Laravel's built-in features

## Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and ensure code style compliance
5. Submit a pull request

## License
This project is licensed under the MIT License.
