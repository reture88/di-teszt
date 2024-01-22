
include .env

DC_EXITS := $(shell docker compose > /dev/null 2>&1 ; echo $$?)
ifeq ($(DC_EXITS),0)
	DOCKER_COMPOSE = docker compose
else
	DOCKER_COMPOSE = docker-compose
endif

# DOCKER
.PHONY: build
build: ## Build docker development environment
	$(DOCKER_COMPOSE) build

.PHONY: start
start: ## Start docker development environment
	docker network prune --force
	$(DOCKER_COMPOSE) up --detach --remove-orphans --force-recreate

.PHONY: stop
stop: ## Stop docker development environment
	$(DOCKER_COMPOSE) stop

.PHONY: set-rights
set-rights: ## Adding necessary rights for the laravel folders
	chmod 0777 -R ./bootstrap/cache ./storage/logs ./storage/framework


# COMPOSER
.PHONY: composer-install
composer-install: ## Install required php packages
	docker exec ${APP_NAME}_php_fpm composer install

.PHONY: composer-update
composer-update: ## Update require php packages
	docker exec ${APP_NAME}_php_fpm composer update

.PHONY: php-cs-fixer-check
php-cs-fixer-check: ## Checks the php files for warnings and errors
	docker exec ${APP_NAME}_php_fpm composer php-cs-fixer-check

.PHONY: php-cs-fixer-fix
php-cs-fixer-fix: ## Fixs the php files conaining warnings and errors
	docker exec -it ${APP_NAME}_php_fpm composer php-cs-fixer-fix

.PHONY: phpstan-analyse
phpstan-analyse: ## Runs PHPStan static code analizer
	docker exec ${APP_NAME}_php_fpm composer phpstan-analyse

.PHONY: phpunit
phpunit: ## Runs PHPUnit tests
	docker exec -it ${APP_NAME}_php_fpm composer phpunit


# APPLICATION
.PHONY: app-name
app-name: ## The App's name
	echo ${APP_NAME}

.PHONY: migrate
migrate: ## Database migrations
	docker exec ${APP_NAME}_php_fpm php ./artisan migrate

.PHONY: migrate-rollback
migrate-rollback: ## Database migrations rollback
	docker exec ${APP_NAME}_php_fpm php ./artisan migrate:rollback

.PHONY: migrate-pretend
migrate-pretend: ## Pretends the database migration
	docker exec ${APP_NAME}_php_fpm php ./artisan migrate --pretend

.PHONY: run-laravel-worker-1
run-laravel-worker-1: ## Run artisan worker
	docker exec -it ${APP_NAME}_laravel-worker-1 php artisan queue:work --tries=1 --queue=save_last_login,load_last_tweet,load_retweets --sleep=3


# SSH
.PHONY: ssh-nginx
ssh-nginx: ## SSH into nginx container
	docker exec -it ${APP_NAME}_nginx /bin/bash

.PHONY: ssh-php
ssh-php: ## SSH into php container
	docker exec -it ${APP_NAME}_php_fpm /bin/bash

.PHONY: ssh-mysql
ssh-mysql: ## SSH into mysql container
	docker exec -it ${APP_NAME}_project_mysql_81 /bin/bash
