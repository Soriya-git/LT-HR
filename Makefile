# ---- Config ----
DC := docker compose
APP := laravel.test          # your service/container name
PHP := php
ART := $(DC) exec $(APP) $(PHP) artisan

# ---- Generic passthrough ----
.PHONY: artisan composer
artisan:
	@$(ART) $(arg)

composer:
	@$(DC) exec $(APP) composer $(arg)

# ---- Common Laravel tasks ----
.PHONY: migrate fresh seed rollback reset
migrate:
	@$(ART) migrate

fresh:
	@$(ART) migrate:fresh

seed:
	@$(ART) db:seed

rollback:
	@$(ART) migrate:rollback

reset:
	@$(ART) migrate:reset

# ---- Cache / Config ----
.PHONY: cache-clear config-clear route-clear view-clear optimize
cache-clear:
	@$(ART) cache:clear

config-clear:
	@$(ART) config:clear

route-clear:
	@$(ART) route:clear

view-clear:
	@$(ART) view:clear

optimize:
	@$(ART) optimize:clear

# ---- Queue / Scheduler ----
.PHONY: queue-work queue-restart schedule-run
queue-work:
	@$(ART) queue:work

queue-restart:
	@$(ART) queue:restart

schedule-run:
	@$(ART) schedule:run

# ---- Testing / Debugging ----
.PHONY: test tinker logs bash
test:
	@$(DC) exec $(APP) vendor/bin/phpunit

tinker:
	@$(ART) tinker

logs:
	@$(DC) logs -f $(APP)

bash:
	@$(DC) exec $(APP) bash

# ---- Utility ----
.PHONY: up down restart build
up:
	@$(DC) up -d

down:
	@$(DC) down

restart:
	@$(DC) restart $(APP)

build:
	@$(DC) build $(APP)
