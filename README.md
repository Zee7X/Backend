
---

# Laravel Business & Bookmark API

A RESTful API project with **admin and user management**, **businesses**, **categories**, and **user bookmarks** using Laravel 10 and Sanctum authentication.

---

## Table of Contents

* [Features](#features)
* [Requirements](#requirements)
* [Installation](#installation)
* [Environment Setup](#environment-setup)
* [Database Setup](#database-setup)
* [Running Migrations and Seeders](#running-migrations-and-seeders)
* [Storage Link](#storage-link)
* [Logo Assets](#logo-assets)
* [Default Admin Credentials](#default-admin-credentials)
* [Running the Application](#running-the-application)
* [API Endpoints](#api-endpoints)
* [License](#license)

---

## Features

* Admin and user authentication using Laravel Sanctum.
* CRUD for **Businesses** and **Categories** (Admin only).
* User **Bookmarks** for businesses.
* Business listing with slug-based route.
* Upload and display business **logos/images**.
* Clean architecture with service and repository layers.

---

## Requirements

* PHP >= 8.1
* Composer
* MySQL / MariaDB
* Laravel 10.x

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/Zee7x/Backend.git
cd Backend
```

2. Install PHP dependencies:

```bash
composer install
```

---

## Environment Setup

1. Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

2. Update `.env` with your database and app settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

3. Generate Laravel application key:

```bash
php artisan key:generate
```

---

## Database Setup

Create a database in MySQL matching the `DB_DATABASE` value in your `.env` file.

---

## Running Migrations and Seeders

1. Run migrations:

```bash
php artisan migrate
```

2. Seed the database with default data, including admin:

```bash
php artisan db:seed
```

---

## Storage Link

To make uploaded logos and images publicly accessible, create a symbolic link from
`storage/app/public` → `public/storage`:

```bash
php artisan storage:link
```

> After this, uploaded logos can be accessed via URL:
> `http://127.0.0.1:8000/storage/logos/{filename}`

---

## Logo Assets

Business logo images are provided in a compressed file named `logos.zip`.
Unzip and move them to the **storage** directory so they appear correctly in the app.

1. Extract the `logos.zip` file:

```bash
unzip logos.zip
```

2. Move the extracted folder into the Laravel storage path:

```bash
mv logos storage/app/public/logos
```

3. After running `php artisan storage:link`, the public URL path will be:

```
public/storage/logos/
```

4. Verify the structure:

```
storage/
 └── app/
     └── public/
         └── logos/
             ├── restaurant1.jpg
             ├── cafe1.jpg
             ├── fitness1.jpg
             └── ...
```

5. Set proper permissions if needed:

```bash
chmod -R 775 storage
```

> 💡 **Tip:** Do this step **before running seeders**, so all seeded businesses display their logo correctly.

---

## Default Admin Credentials

The seeder creates a default admin account:

| Field    | Value                                   |
| -------- | --------------------------------------- |
| Email    | [admin@test.com](mailto:admin@test.com) |
| Password | password                                |

> Use these credentials to log in to admin endpoints.

---

## Running the Application

Start the development server:

```bash
php artisan serve
```

API base URL: `http://127.0.0.1:8000/api`

---

## API Endpoints

### Admin Authentication

| Method | Endpoint           | Description |
| ------ | ------------------ | ----------- |
| POST   | `/api/admin/login` | Admin login |

### User Authentication

| Method | Endpoint               | Description                 |
| ------ | ---------------------- | --------------------------- |
| POST   | `/api/register`        | User registration           |
| POST   | `/api/login`           | User login                  |
| POST   | `/api/forgot-password` | Request password reset      |
| POST   | `/api/reset-password`  | Reset password              |
| POST   | `/api/logout`          | Logout user (requires auth) |

---

### User Routes (Auth Required)

| Method | Endpoint                                   | Description         |
| ------ | ------------------------------------------ | ------------------- |
| GET    | `/api/user/bookmarks`                      | List user bookmarks |
| POST   | `/api/businesses/{business:slug}/bookmark` | Add a bookmark      |
| DELETE | `/api/businesses/{business:slug}/bookmark` | Remove a bookmark   |

---

### Businesses (User)

| Method | Endpoint                          | Description            |
| ------ | --------------------------------- | ---------------------- |
| GET    | `/api/businesses`                 | List all businesses    |
| GET    | `/api/businesses/{business:slug}` | View a single business |

---

### Admin Routes (Auth + Admin Middleware)

#### Businesses

| Method | Endpoint                           | Description                  |
| ------ | ---------------------------------- | ---------------------------- |
| GET    | `/api/admin/businesses`            | List businesses (DataTables) |
| POST   | `/api/admin/businesses`            | Create a business            |
| PUT    | `/api/admin/businesses/{business}` | Update a business            |
| DELETE | `/api/admin/businesses/{business}` | Delete a business            |

#### Categories

| Method | Endpoint                           | Description       |
| ------ | ---------------------------------- | ----------------- |
| GET    | `/api/admin/categories`            | List categories   |
| POST   | `/api/admin/categories`            | Create a category |
| PUT    | `/api/admin/categories/{category}` | Update a category |
| DELETE | `/api/admin/categories/{category}` | Delete a category |

---

### Postman Collection

You can import the API collection to Postman to test all endpoints:

* Collection: [`postman/Backend Test.postman_collection.json`](postman/Backend Test.postman_collection.json)

Steps:

1. Open Postman → Click **Import** → Choose **File** → Select `Backend Test.postman_collection.json`.
2. Optionally, set environment variables for `base_url` and `Authorization` token.
3. Start testing all admin and user API endpoints.

---

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---
