<div align="center">

# RJ CMS Lite

**A lightweight Laravel CMS for print businesses**
DTF transfers · custom signage · product catalogs · orders · deliveries · support tickets · drag-and-drop homepage widgets

![Laravel](https://img.shields.io/badge/Laravel-12.69-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=black)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?style=flat-square&logo=vite&logoColor=white)
![License](https://img.shields.io/badge/License-Proprietary-lightgrey?style=flat-square)

[Repository](https://github.com/mr-ruhid/dtf-website) · [Author](https://github.com/mr-ruhid) · [Website](https://ruhidjavadov.site)

</div>

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Environment Variables](#environment-variables)
- [Running the Project](#running-the-project)
- [Default Admin Credentials](#default-admin-credentials)
- [Project Structure](#project-structure)
- [Widget System](#widget-system)
- [Admin AI Chat Widget](#admin-ai-chat-widget)
- [Design Studio (RJFrame)](#design-studio-rjframe)
- [Frontend Routes](#frontend-routes)
- [Admin Routes](#admin-routes)
- [API](#api)
- [Payments](#payments)
- [Storage and Backups](#storage-and-backups)
- [Production Deployment](#production-deployment)
- [Security Checklist](#security-checklist)
- [Troubleshooting](#troubleshooting)
- [Useful Artisan Commands](#useful-artisan-commands)
- [License](#license)
- [Credits](#credits)
- [Support the Project](#support-the-project)
- [Author](#author)

---

## Overview

RJ CMS Lite is a Laravel-based content and commerce system built for print shops. It combines a customer-facing storefront (catalog, online design studio, cart, checkout, order tracking) with a full admin panel (orders, deliveries, support, content, widgets, storage tools).

The homepage is assembled from **widgets**: predefined sections that an admin can reorder by drag-and-drop, enable or disable, and edit through per-widget settings.

---

## Features

- **Widget-based homepage**: 19 predefined sections, drag-to-reorder, per-widget settings editor
- **Product catalog**: products, models, categories, attributes, variants, print zones
- **Design Studio (RJFrame)**: online DTF gang sheet builder with canvas editor, text, shapes and image adjustments
- **Order management**: cart, checkout, receipts, order tracking, design downloads
- **Delivery system**: branches, delivery zones, delivery rates
- **Payments**: pluggable payment gateway registry (manual + extensible)
- **Support tickets**: threads, attachments, priorities, admin replies
- **Content**: blog, pages, sliders, menus, gallery, FAQ
- **Admin AI Chat widget**: embed any AI chat (HF Spaces, DeepAI, Kimi, ...) as iframe or popup, with a server-side proxy
- **2FA authentication**: email-based verification for admin login
- **Storage analyzer**: clean up temp files, orphaned uploads and order files
- **Backup and restore**: manual database backups
- **API**: order integration endpoint for external systems

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12.69, PHP 8.2 |
| Frontend | Tailwind CSS 4, Alpine.js 3, Vite 7 |
| Icons | Font Awesome 6 (CDN) |
| Canvas | Fabric.js 5.3 |
| Database | MySQL / SQLite |
| Cache / Queue / Session | database |
| Media | Local storage + Cloudinary (video only) |

---

## Requirements

- PHP >= 8.2
- Composer >= 2.10
- Node.js >= 18
- MySQL 8+ or SQLite
- PHP extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `fileinfo`

---

## Installation

```bash
git clone https://github.com/mr-ruhid/dtf-website.git
cd dtf-website

composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

Set your database credentials in `.env`, then run:

```bash
php artisan migrate --seed
php artisan storage:link
```

The seeders create the admin account, the homepage widgets, sliders and other starter data (see [Project Structure](#project-structure)).

---

## Environment Variables

```env
APP_NAME="RJ CMS Lite - All Print site"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rjshop
DB_USERNAME=root
DB_PASSWORD=

CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
```

| Group | Purpose |
|---|---|
| `APP_*` | Application name, environment, debug mode and public URL |
| `DB_*` | Database connection (MySQL or SQLite) |
| `CLOUDINARY_*` | Video uploads (only video is stored on Cloudinary) |
| `SESSION_DRIVER`, `QUEUE_CONNECTION`, `CACHE_STORE` | All use the `database` driver by default |
| `MAIL_*` | SMTP settings. Required for admin 2FA emails and notifications |

> **Note:** Because queue, session and cache use the database, make sure the migrations have been run before starting the app or a queue worker.

---

## Running the Project

**Development** (server + queue + logs + Vite in one command):

```bash
composer dev
```

**Production build:**

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Default Admin Credentials

```text
URL:      /admin/login
Email:    admin@rjshop.com
Password: admin123
```

> **Change these immediately after first login.** The account is created by `AdminSeeder`.

---

## Project Structure

```text
app/
├── Http/Controllers/Admin/    Admin panel controllers (27)
├── Http/Controllers/          Frontend controllers (12)
├── Http/Middleware/           admin, guest
├── Models/                    40 Eloquent models
├── Payment/Registry/          PaymentGatewayRegistry
├── Providers/                 AppServiceProvider, PaymentServiceProvider
├── Services/Storage/          StorageAnalyzer
└── helpers.php                human_size()

database/
├── migrations/                30 migrations
└── seeders/                   AdminSeeder, WidgetSeeder, SignWidgetSeeder, SliderSeeder, ...

resources/views/
├── admin/                     Admin panel (Blade + Alpine)
│   └── widget/                Per-widget editors
├── theme/rjshop-theme/        Frontend theme
│   ├── layouts/
│   ├── partials/
│   ├── rjshop/
│   ├── staticpages/
│   └── widgets/
└── emails/

routes/
├── web.php                    Frontend routes
└── admin.php                  Admin routes (prefix: /admin)
```

---

## Widget System

Homepage sections are stored in the `widgets` table. Each row holds `key`, `name`, `sort_order`, `is_active` and a JSON `settings` column. Admins reorder widgets by drag-and-drop; the order is saved to `sort_order`.

| Key | Name | Editable |
|---|---|---|
| `hero` | Hero Banner | via Sliders |
| `steps` | How It Works | yes |
| `build_or_upload` | Build or Upload | yes |
| `features` | Features | yes |
| `categories` | Shop by Category | via Services |
| `featured_products` | Featured Products | via Services |
| `testimonials` | Testimonials | via Services |
| `stats` | Statistics | via Services |
| `info` | Custom Info Section | yes |
| `slider_mid` | Mid Banner | via Sliders |
| `blog_preview` | Latest Blog Posts | via Blog |
| `faq_preview` | FAQ | via FAQs |
| `newsletter` | Newsletter Subscribe | via Services |
| `cta` | Call to Action | via Services |
| `live_chat` | Live Chat | via Services |
| `sign_hero` | Sign Hero | yes |
| `special_films_story` | Special Films Story | yes |
| `special_films_faq` | Special Films FAQ | yes |
| `admin_ai_chat` | Admin AI Chat | yes |

Manage widgets at `/admin/widgets`. Widgets marked "yes" have their own editor under `resources/views/admin/widget/`; the others are fed by the module named in the last column.

---

## Admin AI Chat Widget

Embeds a third-party AI chat inside the admin panel without an API key.

- **Modes**: iframe (slide panel) or popup (new window)
- **Presets**: Hugging Face Spaces, DeepAI, Kimi
- **Proxy**: `GET /admin/ai-proxy?url=...` fetches the target site server-side, strips `X-Frame-Options` / CSP headers and replaces frame-breakout code with no-ops
- **Fallback**: if the iframe stays empty, a message with an "Open in new window" button is shown

Configure it at `/admin/widgets/admin_ai_chat/edit`.

> The proxy is available to authenticated admins only. Use it with sites you trust.

---

## Design Studio (RJFrame)

Online DTF gang sheet builder powered by Fabric.js.

- Upload images (drag and drop / paste)
- Add text (100+ fonts, outlines, shadows, alignment)
- Add shapes (rectangle, circle, line, etc.)
- Image adjustments (brightness, contrast, saturation, blur, filters)
- Auto-arrange, fill sheet, snap to grid
- DPI validation and out-of-bounds warnings
- Save project (JSON) and download a print-ready PNG
- Admin mode for editing order artwork

Route: `/design` · Controller: `DesignController`

---

## Frontend Routes

| Method | URI | Name | Purpose |
|---|---|---|---|
| GET | `/` | `page.home` | Homepage |
| GET | `/about-us` | `page.about` | About |
| GET | `/contact-us` | `page.contact` | Contact |
| GET | `/faq` | `page.faq` | FAQ |
| GET | `/blog` | `blog.index` | Blog list |
| GET | `/blog/{slug}` | `blog.show` | Blog post |
| GET | `/gallery` | `gallery.index` | Gallery |
| GET | `/design` | `design.index` | DTF gang sheet builder |
| POST | `/design/temp-upload` | `design.temp-upload` | Temp upload |
| GET | `/product/{slug}` | `product.show` | Product page |
| GET | `/model/{slug}/{category?}` | `model.show` | Model page |
| GET/POST | `/cart/*` | `cart.*` | Cart actions |
| GET/POST | `/checkout` | `checkout.*` | Checkout |
| GET/POST | `/track` | `track.*` | Order tracking |
| GET | `/order/{token}/download/*` | `order.download.*` | Design downloads |

---

## Admin Routes

All admin routes are prefixed with `/admin` and protected by the `admin` middleware.

Main sections:

`dashboard` · `profile` · `widgets` · `menus` · `sliders` · `faqs` · `gallery` · `blog` · `pages` · `models` · `categories` · `attributes` · `print-zones` · `products` · `branches` · `delivery-zones` · `delivery-rates` · `orders` · `support` · `payments` · `settings` · `storage` · `services` · `ai-proxy`

---

## API

An order integration endpoint is available for external systems. After logging in to the admin panel, open `/admin/orders/api-docs` for the full documentation.

---

## Payments

Payments run through a pluggable gateway registry (`App\Payment\Registry\PaymentGatewayRegistry`), registered by `PaymentServiceProvider`. A manual gateway is included; additional gateways can be added by registering them in the registry. Manage payment methods in the admin panel under `/admin/payments`.

---

## Storage and Backups

- **Storage analyzer** (`/admin/storage`): finds temp files, orphaned uploads and order files that can be cleaned up
- **Backups**: create manual database backups from the admin panel
- **Media**: images and files are stored locally (run `php artisan storage:link`); only video uses Cloudinary

Download a backup and keep it off the server before running any cleanup or restore.

---

## Production Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Set `APP_URL` to your real HTTPS domain
3. Configure the database and SMTP (needed for admin 2FA emails)
4. Install dependencies and build assets:

   ```bash
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   ```

5. Migrate and link storage:

   ```bash
   php artisan migrate --force
   php artisan storage:link
   ```

6. Cache configuration, routes and views:

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

7. Point the web server document root to `public/`
8. Run a queue worker (the queue uses the `database` driver) and keep it alive with a process manager such as Supervisor:

   ```bash
   php artisan queue:work --tries=3
   ```

---

## Security Checklist

- [ ] Change the default admin email and password
- [ ] `APP_DEBUG=false` in production
- [ ] HTTPS enabled
- [ ] Working SMTP so admin 2FA codes are delivered
- [ ] `.env` is not publicly accessible and not committed
- [ ] Regular database backups stored off-server
- [ ] Web root set to `public/` only

---

## Troubleshooting

| Problem | Fix |
|---|---|
| Changes to `.env` or config are ignored | `php artisan optimize:clear` |
| Uploaded images return 404 | `php artisan storage:link` |
| Emails or queued jobs are not processed | Start a worker with `php artisan queue:work` and check `MAIL_*` |
| Frontend styles or scripts missing | Run `npm run build` (or `npm run dev` during development) |
| Admin 2FA email never arrives | Verify SMTP credentials and `MAIL_FROM_ADDRESS` |
| AI chat iframe stays empty | Use the "Open in new window" button; some sites block proxying |

---

## Useful Artisan Commands

```bash
php artisan about                 # overview of the app
php artisan migrate --seed        # run migrations and seeders
php artisan storage:link          # link public/storage
php artisan optimize:clear        # clear all caches
```

---

## License

RJ CMS Lite is source-available. You may view and modify the code for non-commercial purposes. Commercial use and running it as a live website require prior written permission and a paid license from the author. See [LICENSE.md](LICENSE.md) for details.

---

## Credits

- Author: **Ruhid Javadov** ([@mr-ruhid](https://github.com/mr-ruhid))
- Repository: <https://github.com/mr-ruhid/dtf-website>

The frontend theme (`rjshop-theme`) is a derivative work based on the RJ-Shop theme, rebuilt on top of the RJ CMS Lite system.

---

## Support the Project

If this project has been useful to you, consider supporting its continued development and maintenance.

<div align="center">

<a href="https://kofe.al/@ruhidjavadoff">
  <img src="https://kofe.al/assets/images/kofeal-logo.svg" height="36" alt="Support on Kofe.al" style="background-color:#ffffff; padding:6px; border-radius:6px;">
</a>
&nbsp;&nbsp;
<a href="https://cayvoy.com/donate/ruhid4715">
  <img src="https://img.shields.io/badge/Donate-Çayvoy-D97706?style=for-the-badge" alt="Donate via Çayvoy" height="36">
</a>
&nbsp;&nbsp;
<a href="https://www.paypal.com/paypalme/ruhidjavadoff">
  <img src="https://img.shields.io/badge/Donate-PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="Donate via PayPal" height="36">
</a>

</div>

<br>

| Method | Details |
|---|---|
| Kofe.al | [@ruhidjavadoff](https://kofe.al/@ruhidjavadoff) |
| Çayvoy | [ruhid4715](https://cayvoy.com/donate/ruhid4715) |
| PayPal | [paypal.me/ruhidjavadoff](https://www.paypal.com/paypalme/ruhidjavadoff) · `ruhidjavadoff@gmail.com` |
| Crypto (USDT, BNB Smart Chain / BEP20) | `0x9a4AD41762D6B07B8C266b312Cf0dBe31FAd890c` |

> **Crypto:** send only USDT on the **BNB Smart Chain (BEP20)** network. Funds sent on other networks may be lost.

---

## Author

**Ruhid Javadov**

- GitHub: [@mr-ruhid](https://github.com/mr-ruhid)
- Website: [ruhidjavadov.site](https://ruhidjavadov.site)
- Project: [dtf-website](https://github.com/mr-ruhid/dtf-website)
