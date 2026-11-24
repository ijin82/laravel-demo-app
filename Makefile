COMPOSER_RUN = docker run --rm -u "$$(id -u):$$(id -g)" -e COMPOSER_HOME=/tmp/composer -v "$(CURDIR)":/app -w /app composer:2

.PHONY: up down logs shell fresh test pint

up:
	docker compose up -d --build

down:
	docker compose down

logs:
	docker compose logs -f --tail=100

shell:
	docker compose exec app sh

fresh:
	docker compose exec app php artisan migrate:fresh --seed

test:
	$(COMPOSER_RUN) sh -lc "composer install --no-interaction --quiet && php artisan test"

pint:
	$(COMPOSER_RUN) vendor/bin/pint
