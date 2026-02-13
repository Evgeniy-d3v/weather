php:
	docker compose exec php bash
reloadQueue:
	docker compose exec php php artisan optimize:clear && \
		docker compose up -d --force-recreate \
			scheduler \
        	queue-handle_telegram_webhook \
        	queue-send-telegram \
        	queue-get_city_coordinate \
        	queue-daily_forecast \
        	queue-hourly_forecast \
        	queue-send_forecast_report\
        	queue-send_current_weather\


pint:
	@echo "Running Pint (app/)..."
	docker compose exec -T php vendor/bin/pint --config pint.json app/ tests/ --dirty --repair

