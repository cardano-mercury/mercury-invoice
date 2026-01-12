export COMPOSE_PROJECT_NAME=cardanomercury
export COMPOSE_FILE=docker/docker-compose.yml

.SILENT: up
up:
	$(MAKE) down
	docker compose up -d
	$(MAKE) composer-install
	./docker/wait-for-mysql.sh
	$(MAKE) db-migrate
	$(MAKE) frontend-build

.SILENT: down
down:
	docker compose down --remove-orphans

.SILENT: build
build:
	docker compose build
	$(MAKE) up
	$(MAKE) frontend-build

.SILENT: rebuild
rebuild:
	docker compose build --pull --no-cache
	$(MAKE) up

#
# Helper functions
#

.SILENT: frontend-build
frontend-build:
	docker exec cardanomercury-web bash -c "npm install && npm run build"

.SILENT: frontend-watch
frontend-watch:
	docker exec cardanomercury-web bash -c "npm install && npm run dev"

.SILENT: frontend-upgrade
frontend-upgrade:
	docker exec cardanomercury-web bash -c "npm update"

.SILENT: composer-install
composer-install:
	docker exec cardanomercury-web bash -c "composer install"

.SILENT: db-migrate
db-migrate:
	docker exec cardanomercury-web bash -c "php artisan migrate"

.SILENT: db-refresh
db-refresh:
	docker exec cardanomercury-web bash -c "php artisan migrate:fresh --seed"

.SILENT: api-docs
api-docs:
	docker exec cardanomercury-web bash -c "php artisan scribe:generate --force"

.SILENT: tinker
tinker:
	docker exec cardanomercury-web bash -c "php artisan tinker"

.SILENT: status
status:
	docker compose ps

.SILENT: logs
logs:
	docker compose logs -f --tail=100

.SILENT: logs-web
logs-web:
	docker compose logs -f --tail=100 cardanomercury-web

.SILENT: logs-horizon
logs-horizon:
	docker compose logs -f --tail=100 cardanomercury-horizon

.SILENT: logs-cron
logs-cron:
	docker compose logs -f --tail=100 cardanomercury-cron

.SILENT: shell
shell:
	docker exec -it cardanomercury-web bash

.SILENT: stats
stats:
	docker stats cardanomercury-web cardanomercury-mysql cardanomercury-redis cardanomercury-horizon cardanomercury-cron

.SILENT: artisan
artisan:
	docker exec cardanomercury-web bash -c "php artisan $(COMMAND)"
