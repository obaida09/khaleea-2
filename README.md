# Khaleea 2 🛍️

**Khaleea 2** is a multi-shop e-commerce platform built with Laravel 11. It supports advanced features like product and order management, discount systems, user roles, seasonal filters, notifications, and more.

## 📦 Features

- ✅ Multi-guard authentication (Users / Shops / Admins)
- 🛍️ Manage products, sizes, categories, and seasons
- 🛒 Shopping cart supporting multiple shops
- 🎁 Coupons, discounts, and points system
- ⭐ Product rating and review system
- 💾 Save products and posts functionality
- 🔔 Real-time notifications when news is added
- 📱 WhatsApp code delivery for account confirmation and password reset
- 🖼️ Multiple images per product
- 📑 Complaint system for users to contact admins

## 🛠️ Tech Stack

| Tech | Description |
|------|-------------|
| Laravel 11 | PHP web framework |
| UUIDs | For all models to ensure security and scalability |
| Spatie | Role and permission management |
| Laravel Sanctum | API authentication |
| WebSockets | Real-time notification system |
| MySQL | Database |
| Postman | API testing |

## 🚀 Getting Started

```bash
git clone https://github.com/obaida09/khaleea-2.git
cd khaleea-2
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
