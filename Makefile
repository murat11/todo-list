export COMPOSE=docker-compose -f docker/docker-compose.yml

build:
	$(COMPOSE) build
	$(COMPOSE) run --rm php composer install

unit-test:
	$(COMPOSE) run --rm php vendor/bin/phpunit tests/Unit/

integration-test:
	$(COMPOSE) up -d mysql-test php
	$(COMPOSE) exec php vendor/bin/phpunit tests/Integration/
	$(COMPOSE) kill

start:
	$(COMPOSE) up -d mysql-test php

stop:
	$(COMPOSE) kill
