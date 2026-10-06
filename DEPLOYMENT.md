# Production Deployment

This project must run with PHP 8.2+, MySQL/MariaDB, and the Laravel `public/` directory as the web root.

## Release checklist

1. Create a production database and a least-privilege database user.
2. Copy `.env.example` to `.env` on the server and replace every placeholder. Do not deploy `.env.testing`.
3. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-domain.example`, `APP_KEY` to a newly generated production key, and `APP_TIMEZONE=Asia/Jakarta`.
4. Configure production mail credentials. The local `MAIL_MAILER=log` value is not a delivery configuration.
5. Configure `DB_CONNECTION=mysql` or `mariadb`; never use `ardians_store_testing` in production.
6. Back up the database before applying schema changes.
7. Inspect `php artisan migrate:status` before running migrations. Never use `migrate:fresh` on production.
8. The business tables currently come from `database/schema/ardians_store_schema.sql`, while repository migrations contain the Laravel base tables and later incremental changes. For a new database, import the schema dump using a controlled release procedure, then reconcile the `migrations` table before applying pending incremental migrations. Do not run `php artisan migrate` blindly against an imported schema whose migration history has not been checked.
9. Install PHP dependencies with `composer install --no-dev --optimize-autoloader`.
10. Build frontend assets in a build environment with `npm ci` and `npm run build`, then deploy `public/build`. Do not run `npm run dev` in production.
11. Run `php artisan storage:link` when the host supports symlinks. Verify `public/storage` points to `storage/app/public`. Keep payment proofs in `storage/app/private` and never expose that directory through the web root.
12. Ensure only `storage/` and `bootstrap/cache/` are writable by the web server. Do not use `chmod 777` for the whole project.
13. Run `php artisan optimize:clear` after changing `.env`, then run `php artisan optimize` only after production values are verified.
14. Configure the scheduler for `php artisan schedule:run` every minute. If database queues are enabled, run a supervised queue worker; otherwise use `QUEUE_CONNECTION=sync` on hosting without a worker.
15. Restart workers after a release and verify the application health endpoint `/up`.

## Shared hosting without SSH

Build `public/build` locally with the same Node/Vite versions, run Composer in a deployment environment, and upload the application excluding `node_modules/`, `.env.testing`, `test-results/`, `playwright-report/`, and development caches. Put the domain document root at `public/`. If symlinks are unavailable, ask the host for its Laravel storage-link procedure; do not make `storage/app/private` public.

## Smoke test after release

- Open the homepage and product catalog.
- Open a product image and upload a product image from admin.
- Upload a store logo and confirm it is served from `/storage/...`.
- Complete guest checkout with COD and transfer.
- Upload and view payment proof as admin.
- Open an order with `order_number` plus `tracking_token` from a separate browser.
- Verify wrong tracking credentials show a generic error.
- Verify admin login rate limiting, dashboard, inventory, purchases, expenses, reports, and settings.
- Check `storage/logs` for errors without exposing the log directory publicly.
- Run automated tests against the isolated testing database, never the production database.
