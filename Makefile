export COMPOSE=docker-compose -f docker/docker-compose.yml

build:
	$(COMPOSE) build
	$(COMPOSE) run --rm php composer install
	$(COMPOSE) run --rm nodejs npm install

unit-test:
	$(COMPOSE) run --rm php vendor/bin/phpunit tests/Unit/

start:
	$(COMPOSE) up -d nginx

stop:
	$(COMPOSE) kill
