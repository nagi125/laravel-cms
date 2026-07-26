.PHONY: setup up down restart ps logs test lint fmt shell-php shell-frontend psql

setup:
	docker compose build
	docker compose up -d db
	docker compose run --rm php composer install
	@if [ ! -f src/backend/.env ]; then cp src/backend/.env.example src/backend/.env; fi
	docker compose run --rm php php artisan key:generate
	docker compose up -d
	docker compose exec php php artisan migrate --seed

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

ps:
	docker compose ps

logs:
	docker compose logs -f

test:
	docker compose exec php php artisan test

lint:
	docker compose exec php ./vendor/bin/pint --test
	docker compose exec frontend npm run lint

fmt:
	docker compose exec php ./vendor/bin/pint

shell-php:
	docker compose exec php bash

shell-frontend:
	docker compose exec frontend bash

psql:
	docker compose exec db psql -U cms -d cms
