# RJ Shop

A full-featured e-commerce administration panel built with Laravel. RJ Shop provides everything needed to manage a made-to-order and print-on-demand storefront: product catalog with tiered pricing, orders with design file handling, delivery logistics, customer support, content management, and system maintenance, all from a single admin interface.

The project is lightweight by design. The admin UI runs on Blade, Tailwind CSS and Alpine.js delivered via CDN, so no Node.js toolchain or asset build step is required.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Project Status](#project-status)
- [Roadmap](#roadmap)
- [Documentation](#documentation)
- [Links](#links)
- [Support the Project](#support-the-project)

---

## Overview

| Area | Summary |
| --- | --- |
| Controllers | 22 |
| Models | 34 |
| Migrations | 18 |
| Seeders | 5 |
| Views | ~40 |
| Routes | 200+ |

All admin routes are defined in `routes/admin.php` and protected by a dedicated `AdminMiddleware`.

---

## Features

### Authentication and Security

- Email and password login with optional "Remember me"
- Two-factor authentication (2FA) with one-time codes delivered by email
- Automatic 6-hour lockout after 5 failed login attempts
- Login log recording every authentication attempt
- Manual IP blocking
- Middleware-level route protection

### Dashboard

- Summary cards for revenue, orders, products and support activity
- Recent orders, recent tickets and top-selling products
- Quick action shortcuts

### Catalog

**Products**
- Filtering by model, category, status and keyword search
- Quantity-based tier pricing (wholesale style)
- Attributes (Size, Color and custom attributes)
- Product-specific color price overrides
- Print zone assignment
- Multiple images per product
- Featured toggle

**Models**
- Top-level product groups (for example DTF Transfers, UV Stickers)
- Full CRUD with SEO fields and imagery
- Featured toggle

**Categories**
- Belong to a model, with parent/child hierarchy
- Tree view with inline modal editing

**Attributes**
- Locked system attributes and user-defined custom attributes
- Color picker with hex values for color attributes
- Default price adjustments per value
- Tree view with a values modal

**Print Zones**
- Placement definitions such as Full Front and Left Chest
- Width and height limits in inches
- Price add-ons for apparel products

### Logistics

**Branches**
- Multiple branches with a configurable default
- Address, phone and email per branch

**Delivery Zones**
- Region groups by state, city or ZIP code
- Color labels and grid view

**Delivery Rates**
- Rates defined per branch and zone
- Price and estimated day range
- Free delivery thresholds by order value or quantity
- Cash on delivery (COD) support

### Orders

- Order list with filters
- Detail view with line items, design files and status history
- Seven-stage status workflow with manual status changes
- Payment and tracking information
- Uploaded design files automatically removed after one month
- Built-in API documentation page with Customer and Admin sections, supporting mobile app integration

### Support Tickets

- Seven problem categories
- Priority levels: Low, Medium, High, Urgent
- Status flow: Open, In Progress, Waiting, Resolved, Closed
- Screenshot and file attachments
- Two-way messaging between customer and admin
- Ticket assignment to administrators
- Internal notes

### Content Management

**Pages**
- Locked system pages: Home, About, Contact, Blog, FAQ, Terms, Privacy, Shipping, Return
- Custom pages creation
- Full HTML editing with TinyMCE 6
- SEO fields and header/footer visibility controls

**Blog**
- Full CRUD with TinyMCE editor
- Automatic slug generation
- Featured image and SEO fields

**Gallery**
- Image and video items, with video storage on Cloudinary
- Lightbox preview and sort order

**Sliders**
- Slider and banner types
- Location-based placement (for example `home_hero`, `home_mid`)
- Item CRUD with text position, text color, button label and link

**FAQ**
- Inline modal CRUD with sort order and visibility toggle

### Settings

Twelve settings sections are available:

| Section | Purpose |
| --- | --- |
| General | Site name, logo, favicon |
| Contact and Social | Contact details and social media links |
| SEO | Meta tags, analytics, robots |
| Homepage | Hero, statistics, announcement bar |
| SMTP | Mail configuration with test message |
| System Info | PHP, Laravel and extension status, documentation links |
| Maintenance | Scheduled maintenance, countdown, bypass IPs |
| Security | Login logs and blocked IPs |
| Profile | Password and 2FA management |
| Cache | Cache clearing tools |
| Backup | Database, files or full backups with restore |
| Update | ZIP upload with automatic installation |

### Profile

- Update name, email and phone
- Change password
- Enable or disable 2FA
- View login activity

---

## Technology Stack

| Layer | Technology |
| --- | --- |
| Backend | Laravel (PHP) |
| Templating | Blade |
| Styling | Tailwind CSS (CDN) |
| Interactivity | Alpine.js (CDN) |
| Icons | Font Awesome 6 |
| Rich text editor | TinyMCE 6 |
| Media storage | Cloudinary |
| Database | SQLite or MySQL |
| Caching | Laravel cache, used by the Setting model |
| Backup and update | ZIP-based archive handling |

---

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/Admin/    # 22 admin controllers
│   └── Middleware/
│       └── AdminMiddleware.php
├── Mail/
│   └── TwoFactorCodeMail.php
├── Models/                   # 34 Eloquent models
└── Services/
    └── CloudinaryService.php

database/
├── migrations/               # Schema for users, catalog, delivery, orders, support, pages
└── seeders/
    ├── AdminSeeder.php
    ├── AttributeSeeder.php
    ├── DatabaseSeeder.php
    ├── PageSeeder.php
    └── SliderSeeder.php

resources/views/
├── admin/                    # Layout, dashboard and all module views
│   ├── auth/
│   ├── settings/
│   └── ...
└── emails/
    └── two-factor.blade.php

routes/
├── admin.php                 # All admin routes
└── web.php

bootstrap/
└── app.php                   # Middleware registration
```

---

## Getting Started

### Requirements

- PHP 8.2 or higher
- Composer
- SQLite or MySQL
- Required PHP extensions (the System Info page in Settings reports the status of each)

### Installation

```bash
# Clone the repository
git clone https://github.com/mr-ruhid/devspork.git
cd devspork

# Install dependencies
composer install

# Configure the environment
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database, mail (SMTP) and Cloudinary credentials, then run:

```bash
# Create the schema and seed default data
php artisan migrate --seed

# Start the development server
php artisan serve
```

For production deployments, cache configuration and routes for better performance:

```bash
php artisan optimize
```

---

## Project Status

**Completed**

- Authentication and two-factor authentication
- Dashboard
- Products, Models, Categories, Attributes and Print Zones
- Branches, Delivery Zones and Delivery Rates
- Orders (API and admin panel)
- Support Tickets
- Pages and Blog
- Gallery, Sliders and FAQ
- Settings (12 sections)
- Backup and Update system

---

## Roadmap

The next phase focuses on the customer-facing storefront:

- Theme directory structure
- Home page
- Product listing and product detail pages
- Cart and checkout
- Order flow
- Customer order tracking
- Production implementation of the Customer and Admin API endpoints

---

## Documentation

- [RJ CMS Lite](https://ruhidjavadoff.blogspot.com/2021/03/rj-cms-lite.html) — core CMS system documentation
- [RJ Theme](https://ruhidjavadoff.blogspot.com/2026/10/rj-theme.html) — theme system and customization
- [RJ AI Agent](https://ruhidjavadoff.blogspot.com/2026/12/rj-ai-agent-agsaggal-ai.html) — Agsaggal AI application support agent
- [RJ CMS Systems](https://ruhidjavadoff.blogspot.com/2026/07/rj-cms-sistemlri.html) — overview of all RJ CMS systems
- [RJ SHOP AI 1.x](https://ruhidjavadoff.blogspot.com/2025/10/rj-shop-ai-1x-rj-shop-lite.html) — RJ SHOP Lite application documentation

---

## Links

- Repository — [github.com/mr-ruhid/devspork](https://github.com/mr-ruhid/devspork)
- Website — [ruhidjavadov.site](https://www.ruhidjavadov.site)
- Author — [@mr-ruhid](https://github.com/mr-ruhid)

---

## Support the Project

If this project has been useful to you, consider supporting its continued development and maintenance.

<div align="center">

<a href="https://kofe.al/@ruhidjavadoff">
  <img src="https://kofe.al/assets/images/kofeal-logo.svg" height="36" alt="Support on Kofe.al" style="background-color:#ffffff; padding:6px; border-radius:6px;">
</a>
&nbsp;&nbsp;
<a href="https://www.paypal.com/paypalme/ruhidjavadoff">
  <img src="https://img.shields.io/badge/Donate-PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="Donate via PayPal" height="36">
</a>

</div>

<br>
