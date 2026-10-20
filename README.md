# Business Giseness

Website and admin panel for **Business Giseness**, a bilingual (English & Kannada) founder-story podcast.

Built with Laravel 12, Livewire, Tailwind CSS (Vite) and MySQL.

## Local setup

```bash
composer install
npm install
cp .env.example .env        # then set DB_* for your local MySQL
php artisan key:generate
php artisan migrate
php artisan db:seed         # admin user + default site settings
php artisan storage:link
npm run build               # or `npm run dev` while working on styles
php artisan serve
```

Run the tests with `php artisan test` (uses in-memory SQLite).

## Admin panel

- Login: `/admin/login` → dashboard at `/admin/dashboard`
- Manage episodes, guests, blog posts, newsletter subscribers
- **Content**: hero section, site details, social links
- **Settings**: default SEO (title, description, robots, OG image, GA4, Search Console), admin password, production domain (used in `robots.txt`)

Public registration is disabled. Create admins from the command line:

```bash
php artisan admin:create-user
php artisan admin:create-user --name="Jane Doe" --email="jane@example.com"
```

`db:seed` creates `admin@businessgiseness.com` (override with `ADMIN_EMAIL`) using `ADMIN_PASSWORD` from `.env`, or prints a random password once. It never changes the password of an existing admin.

## Public site

| Route | Page |
|---|---|
| `/` | Home (latest published episodes & posts) |
| `/about-business-giseness-podcast` | About |
| `/kannada-business-podcast` | Landing page for "Kannada business podcast" searches |
| `/episodes`, `/episodes/{slug}` | Episodes (`?language=kn` / `?language=en` filters) |
| `/guests`, `/guests/{slug}` | Guests |
| `/blog`, `/blog/{slug}` | Blog (drafts are visible only to logged-in admins) |
| `/privacy-policy`, `/terms-of-use`, `/disclaimer` | Legal |
| `/sitemap.xml`, `/robots.txt`, `/llms.txt` | Generated dynamically (`llms.txt` is a Markdown briefing for AI assistants) |

## SEO / AEO / GEO

`app/Support/Seo.php` is the single source of truth for how the show describes itself: name (incl. Kannada ಬಿಸಿನೆಸ್ ಗಿಸಿನೆಸ್), positioning summary, host, social profiles, FAQs and the sitewide JSON-LD graph (Organization, WebSite, PodcastSeries, host Person). Pages link to that graph by `@id`. The FAQ (`partials/faq.blade.php`) renders visibly and as `FAQPage` schema from the same data, and `/llms.txt` reuses it too — edit facts in one place.

Set each episode's **language** (Kannada / English / both) in the admin — it drives the language filter, the badge on episode pages and `inLanguage` in schema.

## Code layout

- `app/Models` — `Episode`, `Guest` (many-to-many with episodes via `episode_guest`, with `is_host` / `sort_order`), `BlogPost`, `NewsletterSubscription`, `SiteSetting` (cached key/value store, read with `SiteSetting::get()`), `User` (`is_admin` flag)
- `app/Http/Controllers` — public controllers; `Admin/` holds admin CRUD
- `app/Livewire` — admin login and the footer newsletter form
- `resources/views/layouts/app.blade.php` — public layout (SEO meta, footer); pages override meta via `@section('title')`, `@section('meta-description')`, `@section('og_image')`, etc.
- `resources/views/admin/layout.blade.php` — admin layout

## Deployment (cPanel)

The root `index.php` and `.htaccess` forward requests into `public/` for hosts where the document root can't point at `public/`. After pulling: `composer install --no-dev`, `php artisan migrate --force`, `npm run build` (or upload `public/build`), then `php artisan optimize:clear`.
