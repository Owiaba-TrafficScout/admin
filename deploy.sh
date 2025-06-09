#!/bin/bash

echo "🚀 Starting Laravel Deployment..."

# Step 1: Regenerate Composer autoload
echo "🔄 Regenerating Composer autoload..."
composer dump-autoload

# Step 2: Clear all caches
echo "🧹 Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Step 3: Rebuild optimized caches
echo "⚙️ Rebuilding optimized caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 4: (Optional) Run migrations

echo "🧬 Running migrations..."
php artisan migrate:fresh --seed --force

echo "✅ Laravel Deployment Complete!"
