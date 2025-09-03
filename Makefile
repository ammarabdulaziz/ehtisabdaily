cache-clear:
	rm -f bootstrap/cache/config.php bootstrap/cache/packages.php bootstrap/cache/routes-*.php bootstrap/cache/events.php
	rm -rf bootstrap/cache/filament
	php artisan optimize:clear

queue-work:
	php artisan queue:work

queue-work-once:
	php artisan queue:work --once