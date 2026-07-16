# QuoteMatch (Olance)

Global freelancing / quote marketplace — Laravel + Inertia (React).

Private repo for TheNovasoft. Full app lives under **`Files/`**.

---

## What you need

| Tool | Notes |
|------|--------|
| [Laragon](https://laragon.org/) (Windows) or PHP 8.2+ / MySQL / Nginx-Apache | Laragon is easiest on Windows |
| [Composer](https://getcomposer.org/) | Required (`vendor/` is **not** in Git) |
| [Node.js 18+](https://nodejs.org/) + npm | Only if you rebuild frontend |
| [Git](https://git-scm.com/) | To clone |

---

## Setup (Windows + Laragon)

### 1. Clone

```bash
cd C:\laragon\www
git clone https://github.com/TheNovasoft/Qoute-Match.git quotematch
```

You need access to this **private** repo (ask an owner to add you as collaborator).

### 2. Install PHP packages

```bash
cd C:\laragon\www\quotematch\Files\core
composer install
```

### 3. Environment file

```bash
copy .env.example .env
php artisan key:generate
```

Edit `Files\core\.env` for MySQL (Laragon defaults):

```env
APP_NAME=QuoteMatch
APP_URL=http://quotematch.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=olance
DB_USERNAME=root
DB_PASSWORD=
```

Comment out or remove `DB_CONNECTION=sqlite` if it is still set.

### 4. Import database

1. Start **MySQL** in Laragon.
2. Import dump:

```bash
# From Files folder (dump creates database olance)
mysql -u root < database\olance.sql
```

Or HeidiSQL / phpMyAdmin → import `Files\database\olance.sql`.

### 5. Storage link + cache

```bash
cd C:\laragon\www\quotematch\Files\core
php artisan storage:link
php artisan config:clear
```

### 6. Point the web server at `Files` (not `core`)

Document root must be the folder that contains `index.php` + `assets` + `build`:

```
C:\laragon\www\quotematch\Files
```

**Laragon:** Menu → Apache/Nginx → create site / virtual host with root = `...\quotematch\Files`.  
Typical URL: `http://quotematch.test` (match `APP_URL`).

**Quick test without vhost:**

```bash
cd C:\laragon\www\quotematch\Files\core
php artisan serve
```

Open the URL shown; set `APP_URL` to match.

### 7. Frontend (usually already built)

`Files\build` is already in the repo. Only rebuild if you change React/JS:

```bash
cd C:\laragon\www\quotematch\Files\core
npm install
npm run build
```

---

## Folder map

```
Qoute-Match/
├── Documentation/     # Product / install docs from package
└── Files/             # ← web root
    ├── index.php
    ├── SETUP.txt      # Short local checklist
    ├── assets/
    ├── build/         # Compiled frontend
    ├── database/
    │   └── olance.sql # Full MySQL dump
    ├── docs/          # Extra guides (e.g. module UML test guide)
    ├── install/       # Installer assets (if used)
    └── core/          # Laravel app (artisan, app/, routes/, …)
```

---

## Portals (after import)

| Role | Typical path |
|------|----------------|
| Public site | `/` |
| Guest post job | `/post-job` |
| Buyer | `/buyer/login` |
| Freelancer | `/freelancer/login` |
| Admin | `/admin` |

Use accounts from the imported `olance` database, or create new ones via register.

---

## Common problems

| Symptom | Fix |
|---------|-----|
| Blank / no CSS | Document root is `Files`, not `Files\core`. Confirm `Files\build` exists. |
| 500 error | `php artisan key:generate`, writable `storage` + `bootstrap\cache`, `php artisan config:clear` |
| DB connection error | `.env` MySQL settings + `olance.sql` imported |
| `Class not found` / missing packages | Run `composer install` in `Files\core` |
| Private clone fails | Get collaborator access on GitHub |

More detail: `Files/SETUP.txt`.

---

## Note on this GitHub tree

Seeing only `Documentation/`, `Files/`, and `.gitignore` is expected.  
Open **`Files/`** for the application. This README is the setup entry point.
