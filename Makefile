build-environment:
	@echo "Building and up the app containers"
	docker-compose kill
	docker-compose build
	docker-compose up --detach db
	docker-compose run --rm php sh -c '\
        php -d memory_limit=256M \
        & composer install \
	    && bin/console doctrine:database:create --if-not-exists'
	docker-compose rm db

up-environment:
	docker-compose up -d