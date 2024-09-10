build:
	docker compose up --no-start;

start:
	docker compose up --remove-orphans -d;

up: build start

stop:
	docker compose stop;

down:
	docker compose down;

restart:
	docker compose restart;

list:
	docker compose ps;

log-tail:
	docker compose logs --tail=50 -f;

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	docker compose exec -e APP_ENV=test app bin/phpunit --testdox $(c)