#!/bin/bash

echo "Clearing application caches..."

php artisan cache:clear
php artisan config:cache  
php artisan route:cache
php artisan view:cache
php artisan clear-compiled

# Try to clear OPcache
echo "Clearing OPcache..."
if php artisan opcache:clear; then
    echo "✓ OPcache cleared via Artisan command"
else
    echo "⚠ Artisan opcache:clear failed, trying alternative method..."
    # Alternative: Use curl to hit the opcache-clear.php script
    if command -v curl >/dev/null 2>&1; then
        SECRET=$(php -r "echo hash('sha256', 'opcache_clear_' . date('Y-m-d'));")
        RESPONSE=$(curl -s "http://localhost/opcache-clear.php?secret=$SECRET")
        echo "OPcache clear response: $RESPONSE"
    else
        echo "⚠ curl not available, OPcache may not be cleared"
    fi
fi

php artisan modelCache:clear
php artisan event:cache

echo "Cache clearing completed!"
