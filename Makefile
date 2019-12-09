export COMPOSE=docker-compose -f docker/docker-compose.yml

build:
	$(COMPOSE) build
	$(COMPOSE) run --rm php composer install

test:
	$(COMPOSE) run --rm php vendor/bin/phpunit tests/

start:
	$(COMPOSE) up -d mysql php

stop:
	$(COMPOSE) kill
