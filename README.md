# 🏪 Inventory Management System

A modern inventory management system built with Laravel and Tailwind CSS. Features product management, order processing, supplier tracking, and stock movements with a beautiful responsive interface.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css)

## ✨ Features

- **Product Management**: Add, edit, and track products with categories
- **Order Processing**: Customer orders with status tracking
- **Purchase Orders**: Supplier orders and procurement
- **Stock Movements**: Inventory changes and transfers
- **Category & Supplier Management**: Organize products and suppliers
- **Warehouse Management**: Multiple storage locations
- **Modern UI**: Responsive design with interactive sidebar
- **User Authentication**: Secure login system

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+, Composer, Node.js, MySQL/PostgreSQL

### Installation

1. **Clone and install**
   ```bash
   git clone https://github.com/yourusername/inventory-system.git
   cd inventory-system
   composer install
   npm install
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_system
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Setup database and start**
   ```bash
   php artisan migrate --seed
   npm run build
   php artisan serve
   ```

5. **Access the application**
   Open `http://localhost:8000`

### Default Login
- **Email**: admin@inventory.com
- **Password**: password

## 🛠️ Development

```bash
# Start development server
php artisan serve

# Watch for asset changes
npm run dev

# Run tests
php artisan test

# Clear caches
php artisan cache:clear
```

## 📁 Project Structure

```
inventory-system/
├── app/Http/Controllers/     # Controllers
├── app/Models/              # Eloquent models
├── database/migrations/      # Database schema
├── resources/views/         # Blade templates
├── routes/                  # Application routes
└── public/                  # Public assets
```

## 🎨 Tech Stack

- **Backend**: Laravel 10.x, Eloquent ORM, Blade
- **Frontend**: Tailwind CSS, Alpine.js, Vite
- **Database**: MySQL/PostgreSQL with migrations
- **Authentication**: Laravel's built-in auth system

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open Pull Request

## 📝 License

MIT License - see [LICENSE](LICENSE) file for details.

---

**Made with ❤️ using Laravel and Tailwind CSS**
