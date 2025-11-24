# Archer

[![Updated Badge](https://img.shields.io/github/last-commit/kaminskia1/archer)](https://github.com/kaminskia1/archer/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.1-blue)](https://www.php.net/)
[![Symfony](https://img.shields.io/badge/Symfony-5.2-black)](https://symfony.com/)

A comprehensive web application built on Symfony 5 for managing software licensing, eCommerce operations, and customer support. Archer provides a complete solution for digital product vendors, featuring storefront management, subscription-based licensing, and integrated payment processing.

## 🚀 Features

### 💰 Commerce Module
- **Digital Storefront**: Full-featured online store for digital products
- **Flexible Checkout Flow**: Streamlined purchase process with session management
- **Multiple Payment Gateways**: Extensible payment gateway system (PayPal, Stripe, BTCPay support planned)
- **Invoice Management**: Comprehensive invoice tracking and payment state management
- **Discount Codes**: Promotional code system for marketing campaigns
- **Subscription Management**: Recurring subscription handling with expiry tracking

### 🔐 Software Licensing (SLM)
- **License Key Generation**: Automated license key creation and distribution
- **Subscription-Based Licensing**: Time-based license management with duration support
- **License Redemption**: User-friendly redemption flow for purchased licenses
- **Heartbeat Support**: License validation and monitoring system
- **Multi-Product Support**: Manage licenses across multiple software packages

### 👥 User Management
- **Role-Based Access Control (RBAC)**: Hierarchical permission system
  - User, Subscriber, Seller, Moderator, Admin roles
- **Registration Codes**: Invite-only registration system
- **User Authentication**: Secure login with API token support
- **User Infractions**: Moderation and ban system
- **Profile Management**: User dashboard with subscription overview

### 📊 Admin Dashboard
- **EasyAdmin Integration**: Powerful admin interface for data management
- **Module System**: Enable/disable features via modular architecture
- **Analytics & Reporting**: Track sales, subscriptions, and user activity
- **Payment Gateway Configuration**: Manage multiple payment processors
- **User & Group Management**: Centralized user administration

### 🔌 API System
- **RESTful API**: JSON-based API for external integrations
- **API Authentication**: Token-based authentication system
- **Secure Endpoints**: Encrypted data transmission (AES-256-CFB)
- **Subscription Validation**: Real-time license verification endpoints
- **Module-Based Routing**: API endpoints organized by feature module

### 📝 Logging System
- **Comprehensive Audit Trail**: Track all system activities
- **Authentication Logging**: Monitor login attempts and sessions
- **Command Logging**: Record CLI command execution
- **User Action Tracking**: Monitor user infractions and subscription changes

### 🎨 Modern Frontend
- **Bootstrap 4**: Responsive, mobile-first design
- **Stimulus.js**: Lightweight JavaScript framework
- **Webpack Encore**: Modern asset management
- **FontAwesome**: Icon library integration
- **SCSS Support**: Customizable styling system

## 📋 Prerequisites

- **PHP** >= 8.1
- **Composer** >= 2.0.0
- **Node.js** & **npm/yarn** (for frontend assets)
- **MySQL/PostgreSQL** (database server)
- **Apache/Nginx** (web server)

### PHP Extensions Required
- `ext-ctype`
- `ext-iconv`
- `ext-json`
- `ext-openssl`

## 🛠️ Installation

### Standard Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/kaminskia1/archer.git
   cd archer
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install frontend dependencies**
   ```bash
   npm install
   # or
   yarn install
   ```

4. **Configure environment variables**
   ```bash
   cp .env .env.local
   ```
   
   Edit `.env.local` and configure:
   - `APP_ENV=prod` (or `dev` for development)
   - `DATABASE_URL` - Your database connection string
   - Additional settings as needed

5. **Create database schema**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

6. **Initialize the application**
   ```bash
   php bin/console archer:setup
   ```
   
   This command will:
   - Create default user roles (User, Subscriber, Seller, Moderator, Admin)
   - Initialize system modules (Commerce, Support, IRC, Linker, Logger, API)
   - Register available payment gateways

7. **Build frontend assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

8. **Start the application**
   ```bash
   symfony server:start
   # or
   php -S localhost:8000 -t public/
   ```

### Docker Installation

Archer includes Docker support for easy deployment.

1. **Build and start the container**
   ```bash
   docker compose up --build
   ```

2. **Access the application**
   
   The application will be available at `http://localhost:9000`

3. **Run setup inside container**
   ```bash
   docker compose exec server php bin/console archer:setup
   ```

See [README.Docker.md](README.Docker.md) for detailed Docker deployment instructions.

## ⚙️ Configuration

### Module System

Archer uses a modular architecture. Enable/disable modules through the admin panel or database:

- **Commerce**: eCommerce and licensing features
- **Support**: Support ticket system
- **IRC**: IRC integration features
- **Linker**: External service linking
- **Logger**: Activity logging system
- **API**: RESTful API endpoints

### Payment Gateways

Configure payment gateways in the admin panel:

1. Navigate to **Commerce** → **Gateway Instances**
2. Create a new gateway instance
3. Select gateway type (PayPal, Stripe, etc.)
4. Enter API credentials and configuration
5. Enable the gateway for checkout

### User Roles & Permissions

Default role hierarchy (lowest to highest):
1. **ROLE_USER** - Basic authenticated user
2. **ROLE_SUBSCRIBER** - Active subscription holder
3. **ROLE_SELLER** - Can manage products (if enabled)
4. **ROLE_MODERATOR** - User management capabilities
5. **ROLE_ADMIN** - Full system access
6. **ROLE_BANNED** - Restricted user (separate from hierarchy)

## 🖥️ CLI Commands

Archer provides several console commands for administration:

```bash
# Setup and initialize the application
php bin/console archer:setup

# Create a new user
php bin/console archer:create-user

# Reset user password
php bin/console archer:reset-password

# Authenticate a user (generate token)
php bin/console archer:authenticate-user

# Get user subscription information
php bin/console archer:get-subscription

# Apply infraction to user
php bin/console archer:infract-user

# Encode data with CPH algorithm
php bin/console archer:cph-encode
```

## 🔌 API Endpoints

### Authentication
```
POST /api/core/auth
```

### User Subscriptions (Secure)
```
GET /api/secure/core/subscriptions
```

### Commerce
```
GET /api/commerce/packages          # List available packages
POST /api/commerce/checkout          # Process checkout
POST /api/commerce/checkout/callback # Payment callback handler
```

### Module-Specific APIs

Each module exposes its own API endpoints when enabled:
- `/api/commerce/*` - Commerce operations
- `/api/support/*` - Support tickets
- `/api/irc/*` - IRC integration
- `/api/linker/*` - External service linking
- `/api/logger/*` - Log retrieval

All API responses are in JSON format. Secure endpoints require API token authentication.

## 🏗️ Project Structure

```
archer/
├── assets/              # Frontend assets (JS, SCSS, images)
├── bin/                 # Console executable and utilities
├── config/              # Application configuration
│   ├── packages/        # Bundle configuration
│   └── routes/          # Routing configuration
├── migrations/          # Database migrations
├── public/              # Web root
│   └── index.php        # Front controller
├── src/
│   ├── Command/         # Console commands
│   ├── Controller/      # HTTP controllers
│   │   ├── Commerce/    # eCommerce controllers
│   │   ├── Core/        # Core system controllers
│   │   ├── IRC/         # IRC integration
│   │   ├── Linker/      # External linking
│   │   ├── Logger/      # Logging controllers
│   │   └── Support/     # Support system
│   ├── Entity/          # Doctrine entities
│   ├── Enum/            # Enumeration classes
│   ├── EventSubscriber/ # Symfony event subscribers
│   ├── Form/            # Form types
│   ├── Model/           # Trait models
│   ├── Module/          # Business logic modules
│   ├── Repository/      # Doctrine repositories
│   └── Security/        # Authentication & authorization
├── templates/           # Twig templates
├── tests/               # Test suite
└── vendor/              # Composer dependencies
```

## 🧪 Development

### Running in Development Mode

1. **Set development environment**
   ```bash
   echo "APP_ENV=dev" >> .env.local
   ```

2. **Enable debug toolbar**
   
   The Symfony profiler and debug toolbar are automatically enabled in dev mode.

3. **Watch and rebuild assets**
   ```bash
   npm run watch
   ```

4. **Run development server**
   ```bash
   symfony server:start
   ```

### Asset Development

```bash
# Development build with source maps
npm run dev

# Watch for changes
npm run watch

# Production build (minified)
npm run build
```

### Database Operations

```bash
# Create a migration
php bin/console make:migration

# Execute migrations
php bin/console doctrine:migrations:migrate

# Validate schema
php bin/console doctrine:schema:validate
```

## 🧪 Testing

```bash
# Run test suite
php bin/phpunit

# Run specific test
php bin/phpunit tests/YourTest.php
```

## 📚 Documentation

- [Commerce & Licensing Documentation](docs/CommerceLicensing.md)
- [Project Wiki](https://github.com/kaminskia1/archer/wiki)
- [TODO List](TODO.md)

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Built with [Symfony 5.2](https://symfony.com/)
- Admin interface powered by [EasyAdmin](https://github.com/EasyCorp/EasyAdminBundle)
- RESTful API support by [FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)
- Frontend styled with [Bootstrap 4](https://getbootstrap.com/)

## 📞 Support

For issues and questions:
- [GitHub Issues](https://github.com/kaminskia1/archer/issues)
- [Project Wiki](https://github.com/kaminskia1/archer/wiki)

---

**Note**: This project is under active development. See [TODO.md](TODO.md) for planned features and improvements.
