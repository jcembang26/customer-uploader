# Customer Uploader Web App


A Laravel + Doctrine application to import data from 3rd Party API. 

---

## Prerequisites
- PHP 8.2.12
- XAMPP v3.3.0
- Laravel installed
- Composer version 2.8.9

---

## Getting Started

### 1. Clone the repository

```bash
git clone https://github.com/jcembang26/customer-uploader.git
cd customer-uploader
```

### 2. Install dependencies

```bash
composer update
```

### 3. Create .env

- Create .env file and copy the content from .env.example then update variables

```env
APP_URL=YOUR_SITE_URL
DB_CONNECTION=CONNECTION_YOU_WANT_TO_USE 

# e.g. mysql

# DB Credentials
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=customer_uploader
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate key

```bash
php artisan key:generate
```

### 5. DB and Migrations

- Create database name `customer_uploader`
- Run migration

```bash
php artisan migrate
```