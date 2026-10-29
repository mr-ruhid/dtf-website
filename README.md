<div align="center">

<img src="https://raw.githubusercontent.com/mr-ruhid/rare-logos-vault/main/logo/cms/rj-shop/rj_shop_lite_colorful.png" alt="RJ Shop Lite Logo" height="120" />



**A lightweight Laravel CMS for print businesses**
DTF transfers · custom signage · product catalogs · orders · deliveries · support tickets · drag-and-drop homepage widgets

![Version](https://img.shields.io/badge/version-1.1.2-blue?style=flat-square)
![Laravel](https://img.shields.io/badge/Laravel-12.69-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=black)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?style=flat-square&logo=vite&logoColor=white)
![License](https://img.shields.io/badge/License-Proprietary-lightgrey?style=flat-square)

[Installation Guide](INSTALL.md) · [Changelog](CHANGELOG.md) · [Repository](https://github.com/mr-ruhid/dtf-website) · [Author](https://github.com/mr-ruhid)

</div>

---

## Overview

RJ CMS Lite is a Laravel-based content and commerce system built for print shops. It combines a customer-facing storefront (catalog, online design studio, cart, checkout, order tracking) with a full admin panel (orders, deliveries, support, content, widgets, storage tools).

The homepage is assembled from **widgets**: predefined sections that an admin can reorder by drag-and-drop, enable or disable, and edit through per-widget settings.

---

## Documentation

Comprehensive guides have been separated for clarity:

- **[Installation Guide (INSTALL.md)](INSTALL.md)** — Step-by-step setup for local and shared hosting environments, environment variables, and troubleshooting.
- **[Changelog (CHANGELOG.md)](CHANGELOG.md)** — Full history of features, updates, and bug fixes for all versions.

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

## Default Admin Credentials

```text
URL:      /admin/login
Email:    admin@rjshop.com
Password: admin123
```

> Change these immediately after first login. The account is created by `AdminSeeder`.

---

## Project Structure

```text
app/
├── Http/Controllers/Admin/    Admin panel controllers (27)
├── Http/Controllers/          Frontend controllers (13)
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
├── emails/
└── sitemap.blade.php          Sitemap XML template

routes/
├── web.php                    Frontend routes
└── admin.php                  Admin routes (prefix: /admin)

README.md                      Project overview
INSTALL.md                     Installation guide (local and shared hosting)
CHANGELOG.md                   Version history
LICENSE.md                     License terms
```

---

## Widget System

Homepage sections are stored in the `widgets` table. Each row holds `key`, `name`, `sort_order`, `is_active` and a JSON `settings` column. Admins reorder widgets by drag-and-drop; the order is saved to `sort_order`.

Manage widgets at `/admin/widgets`.

### Admin AI Chat Widget

Embeds a third-party AI chat inside the admin panel without an API key.

- **Modes:** iframe (slide panel) or popup (new window)
- **Presets:** Hugging Face Spaces, DeepAI, Kimi
- **Proxy:** `GET /admin/ai-proxy?url=...` fetches the target site server-side, strips `X-Frame-Options` / CSP headers, and replaces frame-breakout code with no-ops
- **Fallback:** if the iframe stays empty, a message with an "Open in new window" button is shown

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

## SEO & Sitemap

Dynamic SEO tools managed from `/admin/settings/seo`.

- **Sitemap:** Automatically generated at `/sitemap.xml`, covering pages, products, models, and blog posts.
- **Robots.txt:** Dynamically served at `/robots.txt` from the admin settings.
- **Meta Tags:** Global meta title, description, keywords, and Open Graph image.
- **Analytics:** Google Analytics, Tag Manager, and Facebook Pixel integration.
- **Favicon:** Dynamic fallback to `public/icon/favicon.png` if no custom favicon is uploaded.

---

## Payments

Payments run through a pluggable gateway registry (`App\Payment\Registry\PaymentGatewayRegistry`), registered by `PaymentServiceProvider`. A manual gateway is included; additional gateways can be added by registering them in the registry. Manage payment methods in the admin panel under `/admin/payments`.

---

## License

RJ CMS Lite is source-available. You may view and modify the code for non-commercial purposes. Commercial use and running it as a live website require prior written permission and a paid license from the author. See [LICENSE.md](LICENSE.md) for details.

---

## Support the Project

If this project has been useful to you, consider supporting its continued development and maintenance.

<div align="center">

<a href="https://kofe.al/@ruhidjavadoff">
  <img src="https://github.com/mr-ruhid/rare-logos-vault/blob/main/logo/monetization/kofe.al/kofe.al_logo.svg" height="36" alt="Support on Kofe.al" style="background-color:#ffffff; padding:6px; border-radius:6px;">
</a>
&nbsp;&nbsp;
<a href="https://cayvoy.com/donate/ruhid4715">
  <img src="https://github.com/mr-ruhid/rare-logos-vault/blob/main/logo/monetization/cayvoy/cayvoy_logo.svg" alt="Donate via Çayvoy" height="36">
</a>
&nbsp;&nbsp;
<a href="https://www.paypal.com/paypalme/ruhidjavadoff">
  <img src="https://img.shields.io/badge/Donate-PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white" alt="Donate via PayPal" height="36">
</a>

</div>

| Method | Details |
|---|---|
| Kofe.al | [@ruhidjavadoff](https://kofe.al/@ruhidjavadoff) |
| Çayvoy | [ruhid4715](https://cayvoy.com/donate/ruhid4715) |
| PayPal | [paypal.me/ruhidjavadoff](https://www.paypal.com/paypalme/ruhidjavadoff) · ruhidjavadoff@gmail.com |
| Crypto (USDT, BNB Smart Chain / BEP20) | `0x9a4AD41762D6B07B8C266b312Cf0dBe31FAd890c` |

> **Crypto:** send only USDT on the BNB Smart Chain (BEP20) network. Funds sent on other networks may be lost.

---

## Author

**Ruhid Javadov** — MR-Ruhid

- GitHub: [@mr-ruhid](https://github.com/mr-ruhid)
- Website: [ruhidjavadov.site](https://ruhidjavadov.site)
- Project: [dtf-website](https://github.com/mr-ruhid/dtf-website)
