build-environment:
	@echo "Building and up the app containers"
	docker-compose kill
	docker-compose build
	docker-compose up --detach db
	docker-compose run --rm php sh -c '\
        php -d memory_limit=256M \
        & composer install \
	    && bin/console doctrine:database:create --if-not-exists \
	    && bin/console doctrine:migrations:migrate -n \
	    && bin/console consumer:sync-books'
	docker-compose rm db
	make swagger-api-doc
	cp .git_hooks/pre-commit .git/hooks/pre-commit

up-environment:
	docker-compose up -d

stop-environment:
	docker-compose stop

remove-environment: stop-environment
	@echo "Removing the app containers"
	docker-compose up --detach db
	docker-compose run --rm php sh -c 'bin/console doctrine:database:drop --force'
	docker-compose kill
	docker-compose rm -v -f

tests-unit:
	@echo "Running unit tests.."
	docker-compose run --rm php sh -c '\
		php -d memory_limit=256M \
		./vendor/bin/phpunit -c phpunit.unit.xml --testsuite unit ${TEST_GROUP} '

swagger-api-doc:
	docker-compose run --rm php sh -c "rm -rf public/swagger && mkdir public/swagger && cp -r vendor/swagger-api/swagger-ui/dist apidoc public/swagger/"

code-fixer-fix:
	docker-compose run --rm php sh -c 'vendor/bin/php-cs-fixer fix src'