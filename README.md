# Student Attendance Management System — Laravel 10

## ICST University

---

## ⚠️ IMPORTANT — You already have a working setup!

Since you already ran `composer install`, `php artisan migrate`, and have a working `.env`,
**DO NOT** copy the `.env` from this ZIP over yours. Only copy these folders/files
(overwriting old broken ones), then re-run composer:

```
app/
bootstrap/
config/
database/
public/
resources/
routes/
artisan
composer.json
```

---

## Fresh Setup (if starting from scratch)

### 1. Extract into htdocs
```
C:\xampp\htdocs\laravel-attendance
```

### 2. Install Dependencies
```bash
cd C:\xampp\htdocs\laravel-attendance
composer install
```

### 3. Environment Setup
```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env`:
```
DB_DATABASE=attendance_management_system
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Create Database
Open phpMyAdmin → Create database named `attendance_management_system`

### 5. Migrate & Seed
```bash
php artisan migrate
php artisan db:seed
```

### 6. Run
```bash
php artisan serve
```
Open: http://127.0.0.1:8000

---

## Login Credentials

| Role | Username/Email | Password |
|---|---|---|
| Admin | admin | Admin@12345 |
| Student | aafrin@icst.edu | student123 |
| Student | hazeem@icst.edu | student123 |
| Student | nifra@icst.edu | student123 |

---

## Troubleshooting

**"Could not open input file: artisan"**
→ The `artisan` file is missing from project root. Make sure it's directly inside `laravel-attendance/`, not in a subfolder.

**"require_once ... public/index.php failed"**
→ `public/index.php` is missing. Re-copy the `public/` folder from this ZIP.

**500 Error**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**"Class not found"**
```bash
composer dump-autoload
```
