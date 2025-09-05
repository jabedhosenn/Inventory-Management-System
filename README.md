# 📦 Inventory Management System (Laravel) Backend

An **Inventory Management System** built with **Laravel 12, Laravel Breeze, Blade, MySQL, and Bootstrap**.  
It provides **role-based access (Admin/User)**, product & category management, suppliers, orders (create/remove), and a clean dashboard separation via custom middleware.  

---

## ✨ Features

- 🔑 **Authentication** with Laravel Breeze (login, registration, password reset)  
- 👥 **Role-based dashboards**: Admin vs User  
- 🛡 **Custom middleware** to protect admin routes  
- 📦 **Products & Categories CRUD**  
- 🏢 **Suppliers & product–supplier relations**  
- 📝 **Orders**: create orders, add/remove items, soft delete/cancel orders  
- 📱 **Responsive UI** with Bootstrap 5 (coexists with Breeze defaults)  
- 🌱 **Database seeding** for roles, admin user, and sample data  

---

## 🧱 Tech Stack

- **Backend:** PHP 8.2+, Laravel 11  
- **Auth:** Laravel Breeze (Blade)  
- **Database:** MySQL 8+  
- **Frontend:** Blade, Bootstrap 5, Vanilla JS  

---

## 🔧 Prerequisites

Make sure you have the following installed:

- [PHP 8.2+](https://www.php.net/downloads)  
- [Composer](https://getcomposer.org/)  
- [MySQL 8+](https://dev.mysql.com/downloads/)  
- [Node.js 18+ & NPM](https://nodejs.org/)  

---

## 🚀 Installation & Setup

1. **Clone the repository**  
   ```bash
   git clone https://github.comjabedhosenn/Inventory-Management-System-Backend.git
   cd inventory-management
   ```

2. **Install dependencies**  
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**  
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update `.env` with your **database credentials**.  

4. **Run migrations & seeders**  
   ```bash
   php artisan migrate --seed
   ```

5. **Build assets**  
   ```bash
   npm run dev
   ```

6. **Start local server**  
   ```bash
   php artisan serve
   ```

   Now visit 👉 [http://localhost:8000](http://localhost:8000)  

---

## 👤 Default Admin Access

Seeder creates an admin account:  

- **Email:** `admin@gmail.com`  
- **Password:** `11111111`  

---

## 📂 Project Structure

- `app/Models` → Models (Product, Category, Supplier, Order)  
- `app/Http/Controllers` → Controllers for resources  
- `resources/views` → Blade templates (Admin/User dashboards)  
- `routes/web.php` → Route definitions  
- `database/seeders` → Role/User/Data seeders  

---


## 📜 License

This project is licensed under the **MIT License**.  
