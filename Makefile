php:
	docker compose exec php bash
reloadQueue:
	docker compose exec php php artisan optimize:clear && docker restart weather-queue-handle-telegram-webhook weather-queue-send-telegram weather-queue-get_city_coordinate

