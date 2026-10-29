# RJ SHOP Lite — Installation Guide

This document provides step-by-step instructions for installing RJ SHOP Lite on a local machine and on a live server (shared hosting or VPS).

## Requirements

The following must be available on your server:

- **PHP** >= 8.2
- **Composer** >= 2.10
- **Node.js** >= 18
- **MySQL** 8+ or **SQLite**
- PHP extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `fileinfo`

---

## 1. Local Installation (Development)

Run the following commands in your terminal (CMD / PowerShell / Terminal) in order:

```bash
# 1. Clone the repository
git clone https://github.com/mr-ruhid/dtf-website.git
cd dtf-website

# 2. Install Composer dependencies
composer install

# 3. Create the .env file
cp .env.example .env

# 4. Generate the application key (APP_KEY)
php artisan key:generate

# 5. Install and build frontend assets
npm install
npm run build

# 6. Set up the database and load initial data (Admin, Widgets)
php artisan migrate --seed

# 7. Create the symbolic link for uploaded images
php artisan storage:link
```

To start the local development server:

```bash
composer dev
```

This command runs the PHP server, the queue worker, and Vite simultaneously.

---

## 2. Production Installation (Shared Hosting — Hostinger, cPanel, etc.)

Follow these steps if your hosting provider does not allow you to point the Document Root to the `public` directory (that is, all files must be placed directly inside `public_html`).

### A. Upload the Files

1. Upload all project files (excluding `vendor` and `node_modules`) to your hosting's `public_html` directory.
2. Open a terminal (via SSH or the hosting control panel's "Terminal" section) and navigate to `public_html`.

### B. Configuration and Permissions

1. Open the `.env` file and enter your details:

   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=database_name
   DB_USERNAME=database_user
   DB_PASSWORD=database_password
   ```

2. Make sure a `.htaccess` file exists in the root directory. It redirects requests to the `public` directory and blocks access to the `.env` file.

3. Grant write permissions to the `storage` and `bootstrap/cache` directories:

   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

### C. Database and System Setup

Run the following commands in the terminal:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
npm install && npm run build
```

### D. Clear Caches

Once everything is set up, clear the caches:

```bash
php artisan optimize:clear
```

---

## 3. First Login and Security

After installation, sign in to the Admin Panel:

- **URL:** `https://your-domain.com/admin/login`
- **Email:** `admin@rjshop.com`
- **Password:** `admin123`

> **IMPORTANT:** After your first login, go to the **Profile** section and change your password immediately. Never leave the system running in production with the default password.

---

## 4. System Updates

When new versions are released, you do not need to upload files manually:

1. Sign in to the Admin Panel.
2. Go to **Settings -> System Update**.
3. The system will automatically check for the latest release on GitHub.
4. Click **Install Update** to apply it.

> **Note:** Before updating, always create a database backup from **Settings -> Backup**.

---

## 5. Troubleshooting

| Problem | Solution |
|---|---|
| 403 Forbidden | Check that the `.htaccess` file exists and that the Document Root is configured correctly. |
| 500 Server Error | Verify the values in your `.env` file and the permissions of the `storage` directory. |
| Images appear broken | Run `php artisan storage:link` in the terminal. |
| "Database file does not exist" | In `.env`, set `DB_DATABASE` to the full (absolute) path of your SQLite file. |
| Changes do not take effect | Delete `bootstrap/cache/config.php` or run `php artisan optimize:clear`. |
