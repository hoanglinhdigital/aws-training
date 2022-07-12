# Task Assignment App

This is a web application which help tranfer, assign and manage tasks between multiple agents.

# Requirements

Package requirement:

-   PHP 7.x
-   Composer
-   Laravel 8.x
-   MySQL (Installation with XAMPP/MySQL Installer)

# Usage

## 1. Install package

### 1.1 Install composer.phar if you don't have it.

### 1.2 Using composer to install package:

```
composer install
```

### 1.3 Setup environment variables

Copy a version `.env.example` -> `.env`

### 1.4 Generate key for the project

```
php artisan key:generate
```

### 1.5 Run the server in development mode:

```
php artisan serve
```

## 2. Database - Migration

To generate database schema, you will need to run migration.

### 2.1 Create a database with the corresponding name in `.env` file

### 2.2 Generate database schema with this command:

```
php artisan migrate
```

### 2.3 Create a new migration file with this:

```
php artisan make:migration <migration_name>
```

Detail see [here](https://laravel.com/docs/8.x/migrations)
