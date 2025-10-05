<div align="center">

```
███████╗██╗   ██╗██████╗  ██████╗
██╔════╝██║   ██║██╔══██╗██╔═══██╗
███████╗██║   ██║██████╔╝██║   ██║
╚════██║██║   ██║██╔═══╝ ██║   ██║
███████║╚██████╔╝██║     ╚██████╔╝
╚══════╝ ╚═════╝ ╚═╝      ╚═════╝
```

### **The Social Network for the Terminal**

*A raw, unfiltered space for hackers. No algorithms. No distractions. Pure signal.*

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/php-8.4%2B-777BB4.svg?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/laravel-12-FF2D20.svg?logo=laravel)](https://laravel.com)
[![Built with Laravel](https://img.shields.io/badge/built%20with-Laravel%20Boost-FF2D20)](https://laravel.com/docs/boost)

---

> **⚠️ This is the API Backend**
> For the CLI client, head over to **[supo-dev/cli](https://github.com/supo-dev/cli)**

</div>

---

## 🔥 What is Supo?

Supo is a terminal-exclusive social network that brings back the raw energy of early Twitter—before algorithms decided what you should see. It's built by developers, for developers.

- **📝 Post directly from your terminal** — No web browsers required
- **⚡ Lightning-fast interactions** — Follow, like, and engage instantly
- **🎯 Pure chronological feed** — See what matters, when it happens
- **🔐 Built with modern security** — Laravel Sanctum authentication
- **🚀 Vim bindings for navigation** — Because your fingers never leave home row
- **💬 Code snippets as first-class citizens** — Share code like you share thoughts

This is the API backend that powers it all. RESTful, secure, and blazingly fast.

---

## 🏗️ Architecture

```
┌──────────────────────────────────────────────────────────┐
│                     Supo CLI Client                      │
│        (Terminal UI with Vim bindings & colors)          │
└────────────────────────┬─────────────────────────────────┘
                         │
                    HTTPS/JSON
                         │
┌────────────────────────▼─────────────────────────────────┐
│                  Supo API (This Repo)                    │
│                                                           │
│  ┌─────────────┐  ┌──────────┐  ┌──────────────────┐   │
│  │   Laravel   │  │ Sanctum  │  │  Queues & Jobs   │   │
│  │     12      │  │   Auth   │  │   (Async Ops)    │   │
│  └─────────────┘  └──────────┘  └──────────────────┘   │
│                                                           │
│  ┌─────────────┐  ┌──────────┐  ┌──────────────────┐   │
│  │ Eloquent    │  │  Pest 4  │  │  Laravel Pint    │   │
│  │  ORM        │  │  Tests   │  │   Formatting     │   │
│  └─────────────┘  └──────────┘  └──────────────────┘   │
└───────────────────────────────────────────────────────────┘
                         │
                         │
                    ┌────▼────┐
                    │  SQLite │
                    │  / MySQL│
                    └─────────┘
```

---

## ✨ Features

### 🔐 **Authentication & Authorization**
- User registration with email verification
- Secure session management with Laravel Sanctum
- Username-based profiles
- Account deletion with cascade cleanup

### 📮 **Posts & Content**
- Create, read, and delete posts
- Rich text support for terminal rendering
- Code snippet formatting
- Chronological feed ordering

### 👥 **Social Interactions**
- Follow/unfollow users
- Like/unlike posts
- Home feed (following) and Explore feed
- User profile pages with post history

---

## 🛠️ Installation

### Prerequisites

- PHP 8.4+
- Composer
- SQLite or MySQL
- Node.js & npm (for frontend assets)

### Quick Start

```bash
# Clone the repository
git clone https://github.com/supo-dev/api.git
cd api

# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Start development server
composer run dev
```

The API will be available at `http://localhost:8000`

---

## 🧪 Testing

```bash
# Run full test suite
composer test

# Run specific test types
composer test:unit          # Unit tests with coverage
composer test:types         # Static analysis with PHPStan
composer test:lint          # Code style checks
composer test:type-coverage # Ensure 100% type coverage

# Format code
composer lint               # Fix code style issues
```

We maintain **100% code coverage** and **100% type coverage**.

---

## 📡 API Endpoints

### Authentication
```http
POST   /sessions                    # Login
DELETE /sessions                    # Logout
GET    /sessions                    # Check session
POST   /users                       # Register
DELETE /users                       # Delete account
POST   /email/send-verification     # Resend verification
POST   /email/verify                # Verify email
```

### Posts
```http
GET    /posts/{post_id}             # Get post by ID
POST   /posts                       # Create new post
DELETE /posts/{post_id}             # Delete post
```

### Social
```http
POST   /follows/{user_id}           # Follow user
DELETE /follows/{user_id}           # Unfollow user
POST   /likes/{post_id}             # Like post
DELETE /likes/{post_id}             # Unlike post
```

### Users & Feeds
```http
GET    /users/{user_id}             # Get user profile
PUT    /users/{user_id}             # Update profile
GET    /feeds/{feed_type}           # Get feed (home/explore)
```

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

<div align="center">

**Built with ❤️ by terminal enthusiasts**

*Because not everything needs a GUI*

</div>
