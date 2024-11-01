export COMPOSE_PROJECT_NAME=cardanomercury
export COMPOSE_FILE=docker/docker-compose.yml

.PHONY: up
up:
	$(MAKE) down
	docker compose up -d
	$(MAKE) composer-install
	./docker/wait-for-mysql.sh
	$(MAKE) db-migrate
	$(MAKE) frontend-build

.PHONY: down
down:
	docker compose down --remove-orphans

.PHONY: build
build:
	docker compose build
	$(MAKE) up

#
# Helper functions
#

.PHONY: frontend-build
frontend-build:
	docker exec -it cardanomercury-web bash -c "npm install && npm run build"

.PHONY: frontend-watch
frontend-watch:
	docker exec -it cardanomercury-web bash -c "npm install && npm run dev"

.PHONY: frontend-upgrade
frontend-upgrade:
	docker exec -it cardanomercury-web bash -c "npm update"

.PHONY: composer-install
composer-install:
	docker exec -it cardanomercury-web bash -c "composer install"

.PHONY: db-migrate
db-migrate:
	docker exec -it cardanomercury-web bash -c "php artisan migrate"

.PHONY: db-refresh
db-refresh:
	docker exec -it cardanomercury-web bash -c "php artisan migrate:fresh --seed"

.PHONY: api-docs
api-docs:
	docker exec -it cardanomercury-web bash -c "php artisan scribe:generate --force"

.PHONY: tinker
tinker:
	docker exec -it cardanomercury-web bash -c "php artisan tinker"

.PHONY: status
status:
	docker compose ps

.PHONY: logs
logs:
	docker compose logs -f --tail=100

.PHONY: logs-web
logs-web:
	docker compose logs -f --tail=100 cardanomercury-web

.PHONY: logs-horizon
logs-horizon:
	docker compose logs -f --tail=100 cardanomercury-horizon

.PHONY: logs-cron
logs-cron:
	docker compose logs -f --tail=100 cardanomercury-cron

.PHONY: shell
shell:
	docker exec -it cardanomercury-web bash

.PHONY: stats
stats:
	docker stats cardanomercury-web cardanomercury-mysql cardanomercury-redis cardanomercury-horizon cardanomercury-cron

.PHONY: artisan
artisan:
	docker exec -it cardanomercury-web bash -c "php artisan $(COMMAND)"
