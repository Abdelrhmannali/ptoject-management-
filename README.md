
# Laravel Project Management API

Production-ready REST API for a simple Project/Task management domain with Users.
Implements CRUD, relationships, validation, resources, pagination, filters, sorting, soft deletes, restore, seeders/factories, and a complete Postman collection.

## Prerequisites
- PHP 8.2+
- Composer 2+
- MySQL 8+ (or PostgreSQL 14+)
- Node.js 18+ (optional for tooling)
- Git

## Tech Stack
- Laravel 12
- Eloquent ORM
- MySQL 
- API Resources, Form Requests
- Soft Deletes

## Setup
```bash
git clone https://github.com/Abdelrhmannali/ptoject-management-.git 
cd project-management-api

# environment
cp .env.example .env
php artisan key:generate

# configure DB in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=project_api
# DB_USERNAME=root
# DB_PASSWORD=

composer install

# run migrations + seeders
php artisan migrate --seed

# start server
php artisan serve --host=0.0.0.0 --port=8000



## Postman
استورد الـ collection من:
`postman/Project Management API.postman_collection.json`
