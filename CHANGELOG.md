# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.2] - 2026-10-30

### Fixed
- **Admin Steps Widget:** Resolved an issue where uploaded images appeared broken in the admin panel by correctly appending the `storage/` path in the Alpine.js preview.
- **System Update View:** Updated the fallback version string from `1.1.1` to `1.1.2` to prevent visual glitches during update checks.
- **Auto-Updater:** Fixed the version bump in `UpdateController` to ensure the latest release is fetched correctly.

## [1.1.1] - 2026-10-30

### Added
- **Dynamic SEO:** Automatically generated `/sitemap.xml` and dynamic `/robots.txt` routing.
- **SEO Settings:** Integrated Sitemap links, Robots.txt editor, Meta tags, Open Graph, and Analytics IDs into the Admin Panel.
- **Auto-Updater:** Added `UpdateController` to securely pull new releases directly from GitHub API without manual ZIP uploads.
- **AI Chat Proxy:** Implemented a server-side proxy for the Admin AI Chat to bypass iframe restrictions (`X-Frame-Options` / CSP).
- **Favicon Fallback:** Added automatic fallback to `public/icon/favicon.png` if no custom favicon is set in the database.
- **Installation Guide:** Added `INSTALL.md` with detailed steps for local and shared hosting setups.

### Changed
- **Admin AI Chat UI:** Redesigned the chat button into a modern side-tab with iframe and popup modes.
- **Routing:** Standardized `bootstrap/app.php` to adhere to Laravel 12 routing standards.
- **Admin Sidebar:** Restored and stabilized the main logo.

### Security
- Added root `.htaccess` to block sensitive files (`.env`, `.git`, etc.) and redirect traffic to the `public` folder.
- Added SSH and storage symlink instructions to resolve broken image issues on shared hosting.

## [1.0.0] - 2026-10-25

### Added
- Initial public release.
- Widget-based homepage with 19 predefined sections.
- Product catalog, models, categories, and attributes.
- RJFrame Design Studio (Fabric.js).
- Order management, cart, checkout, and delivery zones.
- Support tickets, 2FA authentication, and storage analyzer.
