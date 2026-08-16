# Inventory Management System (IMS)

A modern, API-first **Inventory Management System (IMS)** built with **Laravel 12**, **Laravel Sanctum**, **Blade**, **Bootstrap**, and **MySQL**.

The system is designed to help small and medium-sized businesses manage products, categories, stock movements, invoices, customers, and inventory alerts through a clean and scalable architecture.

> **Project Status:** MVP / Active Development

---

## Overview

The Inventory Management System focuses on accurate inventory tracking, streamlined sales management, and a reliable API foundation.

The application follows an **API-first architecture**, where core business logic is implemented through REST APIs and the Blade frontend consumes those APIs. This approach keeps the system modular and makes it easier to extend the application with a SPA or mobile application in the future.

The original project requirements define the MVP around authentication, category management, product management, stock management, invoicing, discounts, and low-stock monitoring.

---

## Features

### Authentication
- User registration
- Secure login
- Token-based authentication with Laravel Sanctum
- Protected API routes
- Logout functionality
- API versioning with `/api/v1`

### Category Management
- Create categories
- View category list
- View individual category
- Update categories
- Delete categories
- Enable/disable category status
- Protection against deleting categories containing products

### Product Management
- Create products
- View product list
- View individual product
- Update products
- Delete products
- Category-based organization
- SKU/product code
- Product unit
- Product price
- Product image support
- Product status
- Low-stock threshold
- Current stock tracking
- Additional product attributes such as color, size, and weight

### Stock Management
- View stock movements
- Stock IN
- Stock OUT / manual adjustment
- Product-level stock tracking
- Optional stock remarks
- Negative stock prevention
- Invoice-based stock deduction
- Stock movement history

### 🧾 Invoice Management
- Create invoices
- View invoice list
- View invoice details
- Update draft invoices
- Change invoice status
- Delete invoices
- Multiple products per invoice
- Automatic subtotal calculation
- Item-level discounts
- Invoice-level discounts
- Fixed and percentage discounts
- Automatic stock deduction when an invoice is finalized

The PRD specifies that invoice confirmation must validate available stock and permanently deduct the corresponding inventory.

### Customer Management
- Create customers
- View customer list
- View individual customer
- Update customers
- Delete customers
- Customer name
- Email
- Mobile number
- Address
- Description

> Customer Management is currently implemented in the API collection. It was originally listed as outside the MVP scope in the PRD, so this represents an extension of the original MVP specification.

### Low Stock Monitoring
- Configurable product-level threshold
- Low-stock detection
- Dashboard/product-level warning capability
- Helps prevent stock-out situations

The defined rule is:

```text
current_stock <= low_stock_threshold
```



### 📈 Dashboard
- Dashboard summary endpoint
- Inventory-related summary data
- Centralized overview of system information

---

## Architecture

The project follows an **API-first, modular architecture**.

```text
                    ┌─────────────────────┐
                    │     Blade UI        │
                    │  Bootstrap / JS     │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │     REST API        │
                    │     /api/v1         │
                    └──────────┬──────────┘
                               │
             ┌─────────────────┼─────────────────┐
             ▼                 ▼                 ▼
       Authentication     Business Logic      Validation
       Laravel Sanctum    Controllers/Models  Form Requests
             │                 │                 │
             └─────────────────┼─────────────────┘
                               ▼
                    ┌─────────────────────┐
                    │       MySQL         │
                    │     Database        │
                    └─────────────────────┘
```

The development strategy explicitly prioritizes backend/API development before the UI and emphasizes separation between backend and frontend.

---

## 📄 Project Documentation

The complete project documentation, including the project requirements, development strategy, and related details, is available below:

**Project Documentation:**  
[View Documentation on Google Drive](https://drive.google.com/file/d/1cHYIS_Iv-WbLN86fXM9UWwbxY4XkLKjc/view?usp=sharing)

---

## Technology Stack

| Technology | Purpose |
|---|---|
| **PHP 8+** | Backend programming language |
| **Laravel 12** | Backend framework |
| **Laravel Sanctum** | API authentication |
| **Blade** | Server-side frontend |
| **Bootstrap** | UI styling and responsive layout |
| **JavaScript** | Frontend interactions |
| **MySQL** | Relational database |
| **REST API** | Application communication layer |
| **Vite** | Frontend asset bundling |
| **Composer** | PHP dependency management |
| **NPM** | Frontend dependency management |
| **Git & GitHub** | Version control |
| **Postman** | API development and testing |

The PRD defines Laravel 12, Blade, Sanctum, API-first architecture, and MySQL/PostgreSQL as the core technology choices.

---

## Project Structure

```text
Inventory-Management-System/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Models/
│   └── Services/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── api.php
│   └── web.php
│
├── storage/
│
├── tests/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

# Installation & Setup

## 1. Clone the Repository

```bash
git clone https://github.com/jabedhosenn/Inventory-Management-System.git
```

```bash
cd Inventory-Management-System
```

---

## 2. Install PHP Dependencies

```bash
composer install
```

---

## 3. Install Frontend Dependencies

```bash
npm install
```

---

## 4. Create Environment File

Copy the example environment file:

```bash
cp .env.example .env
```

For Windows:

```bash
copy .env.example .env
```

---

## 5. Generate Application Key

```bash
php artisan key:generate
```

---

## 6. Configure Database

Update your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_management_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the database before running the migrations.

---

## 7. Run Migrations

```bash
php artisan migrate
```

If seed data is available:

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate --seed
```

---

## 8. Configure Storage

```bash
php artisan storage:link
```

---

## 9. Start Laravel Development Server

```bash
php artisan serve
```

The application will typically be available at:

```text
http://127.0.0.1:8000
```

---

## 10. Start Vite

In another terminal:

```bash
npm run dev
```

For a production asset build:

```bash
npm run build
```

---

# Demo Login

Use the following account for local/demo testing:

```text
Email: info@jabed.com
Password: 123456
```

> **Security:** These credentials are intended only for local/demo use. Do not use this password for a production deployment or expose production credentials in source control.

---

# API Documentation

The project exposes versioned REST APIs under:

```text
/api/v1
```

The supplied Postman collection contains the following API groups:

| Module | Endpoints |
|---|---|
| Authentication | Register, Login, Logout |
| Category | CRUD |
| Product | CRUD |
| Stock Movements | List, Stock IN, Stock Adjustment |
| Invoice | CRUD, Status Change |
| Dashboard | Summary |
| Customer | CRUD |

The collection uses a `{{BASE_URL}}` variable and Bearer token authentication for protected endpoints.

---

## Authentication API

### Register

```http
POST /api/v1/register
```

### Login

```http
POST /api/v1/login
```

### Logout

```http
POST /api/v1/logout
Authorization: Bearer {token}
```

The Postman collection confirms the authentication flow and Sanctum-style bearer-token usage for protected requests.

---

# Category API

```http
GET    /api/v1/categories
POST   /api/v1/categories
GET    /api/v1/categories/{id}
PUT    /api/v1/categories/{id}
DELETE /api/v1/categories/{id}
```

Example request:

```json
{
    "name": "S Series",
    "description": "These are the S Series from Samsung",
    "status": true
}
```

The API collection defines the complete Category CRUD flow.

---

# 📦 Product API

```http
GET    /api/v1/products
POST   /api/v1/products
GET    /api/v1/products/{id}
PUT    /api/v1/products/{id}
DELETE /api/v1/products/{id}
```

Example:

```json
{
    "category_id": 1,
    "product_name": "iPhone 17",
    "sku": "SKU-007",
    "unit": "pcs",
    "image_path": null,
    "low_stock_threshold": 5,
    "color": "Midnight Black",
    "size": "6.1",
    "weight": 180.00,
    "price": 90000.00,
    "status": true,
    "stock_qty": 0
}
```

The current collection supports additional product attributes such as color, size, weight, and stock quantity in addition to the core PRD fields.

---

# Stock API

### Get Stock Movements

```http
GET /api/v1/stocks
```

### Stock IN

```http
POST /api/v1/stocks/in
```

Example:

```json
{
    "product_id": 1,
    "quantity": 5,
    "note": "New stock added."
}
```

### Stock Adjustment

```http
POST /api/v1/stocks/adjustment
```

Example:

```json
{
    "product_id": 1,
    "quantity": 3,
    "type": "OUT",
    "note": "Damaged.",
    "invoice_id": null
}
```

These endpoints correspond to the Stock Movements section of the supplied Postman collection.

---

# Invoice API

```http
GET    /api/v1/invoices
POST   /api/v1/invoices
GET    /api/v1/invoices/{id}
PUT    /api/v1/invoices/{id}
PUT    /api/v1/invoices/{id}
DELETE /api/v1/invoices/{id}
```

### Invoice Status

The collection includes a dedicated status-change endpoint:

```http
PUT /api/v1/invoices/{id}
```

Example:

```json
{
    "status": "finalized"
}
```

### Example Invoice

```json
{
    "invoice_no": null,
    "invoice_date": "2026-08-07",
    "items": [
        {
            "product_id": 1,
            "quantity": 2,
            "unit_price": 200.00,
            "discount_type": "fixed",
            "discount_value": 20.00
        },
        {
            "product_id": 4,
            "quantity": 2,
            "unit_price": 300.00,
            "discount_type": "percent",
            "discount_value": 10.00
        }
    ],
    "discount_type": "fixed",
    "discount_value": 20.00,
    "status": "draft"
}
```

The PRD defines both item-level and invoice-level discount calculations and requires stock validation before invoice confirmation.

---

# Dashboard API

```http
GET /api/v1/dashboard/summary
```

The current Postman collection includes a protected dashboard summary endpoint.

---

# Customer API

```http
GET    /api/v1/customers
POST   /api/v1/customers
GET    /api/v1/customers/{id}
PUT    /api/v1/customers/{id}
DELETE /api/v1/customers/{id}
```

Example:

```json
{
    "name": "Jabed",
    "email": "jabed@gmail.com",
    "mobile": "017017017",
    "address": "Dhaka",
    "description": "This is Jabed from Dhaka"
}
```

The supplied API collection currently includes complete Customer CRUD endpoints.

---

# Invoice Calculation Logic

The system follows the defined calculation flow:

### Item Level

```text
line_subtotal = quantity × unit_price

line_total = line_subtotal − item_discount_amount
```

### Invoice Level

```text
invoice_subtotal = sum(line_total)

grand_total = invoice_subtotal − invoice_discount_amount
```

Discounts must not exceed the applicable subtotal.

---

# Core Business Rules

The system is designed around the following rules:

- Stock can never be negative.
- Stock quantity is system-calculated.
- Invoice confirmation deducts stock.
- Category deletion is restricted when products exist.
- Product deletion is restricted when stock exists.
- Discounts must be validated.
- Stock must be available before invoice confirmation.
- Low-stock status is triggered when current stock is at or below the configured threshold.

---

# API Testing with Postman

A Postman collection is included for testing the API.

### Import Collection

1. Open Postman.
2. Select **Import**.
3. Import:

```text
IMS Collection.postman_collection.json
```

4. Configure the `BASE_URL` variable.

Example:

```text
http://127.0.0.1:8000
```

5. Authenticate using the login endpoint.
6. Copy/use the returned token as the Bearer token for protected endpoints.

The supplied collection is based on Postman's Collection v2.1 schema and uses `{{BASE_URL}}` throughout the API requests.

---

# Development Roadmap

The project follows a phased development strategy:

```text
Phase 0
Planning & Setup
       ↓
Phase 1
Authentication & Base API
       ↓
Phase 2
Category & Product
       ↓
Phase 3
Stock Management
       ↓
Phase 4
Invoice Management
       ↓
Phase 5
Low Stock Alerts
       ↓
Phase 6
Blade UI Integration
       ↓
Phase 7
Testing & Stabilization
       ↓
Phase 8
Deployment & Handover
```

The development strategy defines this sequence to support incremental development, validation, maintainability, and future scalability.

---

# Testing & Quality

Testing focuses on:

- API request validation
- Authentication
- CRUD operations
- Stock edge cases
- Negative stock prevention
- Invoice calculations
- Discount calculations
- Invoice confirmation
- Automatic stock deduction
- Low-stock scenarios
- Database consistency
- Error handling
- Performance optimization

These areas correspond to the project's planned Testing & Stabilization phase.

---

# Security

The project uses:

- Laravel Sanctum authentication
- Protected API routes
- Request validation
- Centralized error handling
- API versioning
- Database constraints and business rules
- Transaction-based invoice confirmation

The PRD identifies secure authentication, request validation, centralized error handling, clean architecture, optimized queries, and scalability as non-functional requirements.

---

# Future Enhancements

Planned future improvements include:

- Role-based access control
- Supplier management
- Purchase orders
- Stock audit logs
- Advanced low-stock notifications
- Sales returns
- Stock returns
- Customer management expansion
- Profit & loss reporting
- PDF invoice export
- Excel export
- Multi-warehouse support
- POS integration
- Mobile application
- SaaS / multi-tenant architecture

These extensions are part of the project's longer-term roadmap.

---

# Contributing

Contributions are welcome.

### Recommended workflow

```bash
git checkout -b feature/your-feature
```

Make your changes, test them, and commit:

```bash
git add .
git commit -m "feat: add your feature"
```

Push the branch:

```bash
git push origin feature/your-feature
```

Then open a Pull Request.

---

# License

This project is developed as an Inventory Management System project and is currently intended for educational, development, and portfolio purposes.

---

# Author

**Jabed Hossain**

Software Engineer

GitHub:  
https://github.com/jabedhosenn

---

## Project Goals

The primary goals of this project are:

- Accurate inventory management
- Reliable stock tracking
- Clean API architecture
- Secure authentication
- Maintainable Laravel code
- Responsive and usable frontend
- Scalable architecture for future expansion

The overall strategy is to deliver a simple, reliable, and scalable MVP while maintaining a strong foundation for future inventory, accounting, warehouse, POS, and mobile capabilities.
