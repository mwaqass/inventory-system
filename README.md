# 🏪 Inventory Management System

A modern, interactive inventory management system built with Laravel and Tailwind CSS. This application provides a comprehensive solution for managing products, orders, suppliers, warehouses, and stock movements with a beautiful, responsive interface.

![Inventory System](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js)

## ✨ Features

### 🎯 Core Functionality
- **Product Management**: Add, edit, and track products with SKUs, categories, and pricing
- **Order Management**: Process customer orders with status tracking
- **Purchase Orders**: Manage supplier orders and procurement
- **Stock Movements**: Track inventory changes and stock transfers
- **Category Management**: Organize products into categories
- **Supplier Management**: Maintain supplier information and relationships
- **Warehouse Management**: Manage multiple storage locations

### 🎨 Modern UI/UX
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile
- **Interactive Sidebar**: Modern sidebar navigation with smooth animations
- **Real-time Feedback**: Loading states, form validation, and user feedback
- **Modern Components**: Cards, buttons, and forms with hover effects
- **Search & Filter**: Advanced filtering capabilities across all modules

### 🔐 Security & Authentication
- **User Authentication**: Secure login and registration system
- **Password Strength**: Real-time password strength indicators
- **Session Management**: Secure session handling
- **CSRF Protection**: Built-in CSRF protection for all forms

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL/PostgreSQL database
- Web server (Apache/Nginx)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/inventory-system.git
   cd inventory-system
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Access the application**
   Open your browser and navigate to `http://localhost:8000`

### Default Login Credentials
- **Email**: admin@inventory.com
- **Password**: password

## 📁 Project Structure

```
inventory-system/
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   │   ├── auth/           # Authentication views
│   │   ├── components/     # Reusable components
│   │   ├── layouts/        # Layout templates
│   │   └── [modules]/      # Module-specific views
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/                 # Application routes
├── public/                 # Public assets
└── storage/                # File storage
```

## 🎨 Technology Stack

### Backend
- **Laravel 10.x**: PHP framework for robust backend development
- **Eloquent ORM**: Database abstraction and model management
- **Blade Templating**: Server-side templating engine
- **Artisan CLI**: Command-line interface for Laravel

### Frontend
- **Tailwind CSS 3.x**: Utility-first CSS framework
- **Alpine.js 3.x**: Lightweight JavaScript framework for interactivity
- **Vite**: Modern build tool for fast development
- **Responsive Design**: Mobile-first approach

### Database
- **MySQL/PostgreSQL**: Relational database support
- **Migrations**: Version-controlled database schema
- **Seeders**: Sample data for development

## 🔧 Configuration

### Environment Variables
Key environment variables in `.env`:

```env
APP_NAME="Inventory System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_system
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Customization
- **Branding**: Update app name and logo in `resources/views/layouts/`
- **Colors**: Modify Tailwind CSS theme in `tailwind.config.js`
- **Components**: Customize reusable components in `resources/views/components/`

## 📊 Features Overview

### Dashboard
- **Welcome Section**: Personalized greeting with current date
- **Statistics Cards**: Key metrics with hover animations
- **Recent Activities**: Latest system activities
- **Low Stock Alerts**: Products requiring attention
- **Quick Actions**: Fast access to common tasks

### Product Management
- **Product Catalog**: Complete product listing with search and filters
- **Category Organization**: Hierarchical product categorization
- **Stock Tracking**: Real-time inventory levels
- **Price Management**: Cost and selling price tracking
- **SKU Management**: Unique product identifiers

### Order Processing
- **Order Creation**: Streamlined order entry process
- **Status Tracking**: Order lifecycle management
- **Customer Management**: Customer information and history
- **Invoice Generation**: Professional invoice creation

### Supplier Management
- **Supplier Directory**: Complete supplier information
- **Contact Management**: Multiple contact methods
- **Order History**: Purchase order tracking
- **Performance Metrics**: Supplier evaluation tools

### Warehouse Management
- **Multi-location Support**: Multiple warehouse locations
- **Stock Transfers**: Inter-warehouse movements
- **Location Tracking**: Product location management
- **Capacity Planning**: Warehouse utilization tracking

## 🛠️ Development

### Development Commands
```bash
# Start development server
php artisan serve

# Watch for asset changes
npm run dev

# Build for production
npm run build

# Run tests
php artisan test

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Code Style
This project follows Laravel and PSR-12 coding standards:
- Use Laravel's built-in coding style
- Follow PSR-12 for PHP code
- Use Tailwind CSS utility classes
- Implement responsive design patterns

### Database Management
```bash
# Create new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed
```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=ProductTest

# Run with coverage
php artisan test --coverage
```

### Test Structure
- **Feature Tests**: Test complete user workflows
- **Unit Tests**: Test individual components
- **Browser Tests**: Test user interactions

## 🚀 Deployment

### Production Setup
1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Database Setup**
   ```bash
   php artisan migrate --force
   ```

3. **Asset Compilation**
   ```bash
   npm run build
   ```

4. **Cache Optimization**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Server Requirements
- PHP 8.1+
- MySQL 5.7+ or PostgreSQL 10+
- Composer
- Node.js 16+
- Web server (Apache/Nginx)

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Commit your changes**: `git commit -m 'Add amazing feature'`
4. **Push to the branch**: `git push origin feature/amazing-feature`
5. **Open a Pull Request**

### Contribution Guidelines
- Follow Laravel coding standards
- Write tests for new features
- Update documentation as needed
- Ensure responsive design compatibility
- Test across different browsers

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Team**: For the amazing PHP framework
- **Tailwind CSS**: For the utility-first CSS framework
- **Alpine.js**: For lightweight JavaScript interactivity
- **Vite**: For the modern build tool

## 📞 Support

If you encounter any issues or have questions:

- **Issues**: [GitHub Issues](https://github.com/yourusername/inventory-system/issues)
- **Documentation**: Check the `/docs` folder for detailed guides
- **Email**: support@inventory-system.com

## 🔄 Changelog

### Version 1.0.0 (Current)
- ✅ Modern sidebar navigation
- ✅ Interactive dashboard with statistics
- ✅ Product management with advanced filtering
- ✅ Order processing system
- ✅ Supplier and warehouse management
- ✅ Responsive design for all devices
- ✅ Real-time form validation
- ✅ Modern authentication system

---

**Made with ❤️ using Laravel and Tailwind CSS**
