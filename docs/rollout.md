# Production rollout checklist

Recommended order: **demo** → **bar** → **comer** (playaalta) → **copas** → **latertulia** → **tertulia** → **horecalo**.

## Before each deploy

1. **Database backup** on the target host (`mysqldump` or provider snapshot).
2. **Rotate secrets** if `.env` was ever committed or shared (DB password, PayPal keys, SMTP).
3. **Maintenance window** (optional): `php artisan down` before symlink, `php artisan up` after (see Deployer hooks in `deploy.php`).

## After deploy

1. Watch `storage/logs/laravel.log` and PHP-FPM/nginx error logs for 24h.
2. Smoke test: open table → add product → checkout → print (if printers online).
3. Wait ~48h before rolling to the next site unless issues appear.

## Post-rollout hardening

- Central log shipping (Papertrail, Grafana Loki, etc.).
- Run `composer audit` weekly; enable Dependabot (`.github/dependabot.yml`).
- Store per-site `.env` only on the server (never in git).
