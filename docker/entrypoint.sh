#!/bin/bash
set -e

CORE="/app/Files/core"
ENV_FILE="$CORE/.env"
PORT="${PORT:-10000}"

cd "$CORE"

# Create .env from example if missing
if [ ! -f "$ENV_FILE" ]; then
  if [ -f "$CORE/.env.example" ]; then
    cp "$CORE/.env.example" "$ENV_FILE"
  else
    touch "$ENV_FILE"
  fi
fi

# Map common Render / hosting env vars into .env (overwrite keys if provided)
set_env() {
  local key="$1"
  local val="$2"
  if [ -z "$val" ]; then
    return 0
  fi
  if grep -q "^${key}=" "$ENV_FILE" 2>/dev/null; then
    sed -i "s|^${key}=.*|${key}=${val}|" "$ENV_FILE"
  else
    echo "${key}=${val}" >> "$ENV_FILE"
  fi
}

set_env "APP_NAME" "${APP_NAME:-QuoteMatch}"
set_env "APP_ENV" "${APP_ENV:-production}"
set_env "APP_DEBUG" "${APP_DEBUG:-false}"
set_env "APP_URL" "${APP_URL:-http://localhost:$PORT}"
set_env "LOG_CHANNEL" "stderr"
set_env "SESSION_DRIVER" "${SESSION_DRIVER:-file}"
set_env "CACHE_STORE" "${CACHE_STORE:-file}"
set_env "QUEUE_CONNECTION" "${QUEUE_CONNECTION:-sync}"

# Database (MySQL recommended â€” Render free DB is Postgres, so use external MySQL)
set_env "DB_CONNECTION" "${DB_CONNECTION:-mysql}"
set_env "DB_HOST" "$DB_HOST"
set_env "DB_PORT" "${DB_PORT:-3306}"
set_env "DB_DATABASE" "$DB_DATABASE"
set_env "DB_USERNAME" "$DB_USERNAME"
set_env "DB_PASSWORD" "$DB_PASSWORD"

# License (Codecanyon / ViserLab)
set_env "PURCHASECODE" "$PURCHASECODE"

# Generate APP_KEY if empty
if ! grep -q "^APP_KEY=base64:" "$ENV_FILE" 2>/dev/null; then
  php artisan key:generate --force || true
fi

# Permissions
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Clear caches (ignore failures on first boot)
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo "Starting QuoteMatch on 0.0.0.0:${PORT} (document root: Files/)"
cd /app/Files
exec php -S "0.0.0.0:${PORT}" router.php
