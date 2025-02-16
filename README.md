# Translation Management Service

## Overview
This project is a **Translation Management Service** built with Laravel. It provides an API-driven service to manage translations for multiple locales, allowing you to store, retrieve, update, and search translations efficiently. The service is designed to be scalable, secure, and performant, with features like token-based authentication, JSON export, and support for large datasets.

---

## Features
- **CRUD Operations**: Create, read, update, and delete translations.
- **Multi-Locale Support**: Store translations for multiple locales (e.g., `en`, `fr`, `es`).
- **Tagging**: Tag translations for context (e.g., `web`, `mobile`).
- **Search and Filter**: Search translations by key, locale, tag, or content.
- **Token-Based Authentication**: Secure the API using JWT (JSON Web Tokens).
- **Scalable Database Schema**: Optimized for handling large datasets (100k+ records).
- **OpenAPI Documentation**: API documentation using OpenAPI/Swagger.

---

## Technologies Used
- **Laravel**: PHP framework for building the API.
- **MySQL/PostgreSQL**: Database for storing translations.
- **JWT**: Token-based authentication.
- **OpenAPI/Swagger**: API documentation.
- **PHPUnit**: Testing framework for unit and feature tests. (Created the tests but due to shortage of time couldn't able to complete the testing part)

---

## Setup Instructions

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL/PostgreSQL

---

### Step 1: Clone the Repository
```bash
git clone git@github.com:NahyanBinKhalid/digtoktms.git tms
cd tms
```

---

### Step 2: Installing Dependencies
```bash
composer install
npm install
```
---

### Step 3: Environment Setup
1. Copy .env.example file to .env
```bash
cp .env.example .env
```

2. Generate Key
```bash
php artisan key:generate
```

3. Add Your DB Info
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tms
DB_USERNAME=root
DB_PASSWORD=password
```

4. generate fresh documentation for your apis
```bash
php artisan l5-swagger:generate
```

5. Run Migration and Seeders
```bash
php artisan migrate:refresh --seed
```

6. Access APIs on the following url
```bash
{base_url}/api/documentation
```
You application is ready to use
---

### Step : Explaining Application Structure
- Used Laravel's features at best of my knowledge
- Implemente Repository Design Pattern
- Use Annotations in Models
- Used Eloquent ORM

### Step : Explaining DB Structure
- I have created 2 major tables 'users' and 'translations'
- I have added locale key in same translations table in non normalize form to reduce query complexity