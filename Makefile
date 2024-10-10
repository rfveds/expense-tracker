# Makefile

# Setup the project
setup: build up generate-key composer-install npm-install migrate fixtures npm-dev

# Build the Docker containers
build:
	docker-compose -f docker/docker-compose.yml build

# Start the Docker containers
up:
	docker-compose -f docker/docker-compose.yml up -d

# Stop and remove the Docker containers
down:
	docker-compose -f docker/docker-compose.yml down

# Restart the Docker containers
restart: down up

# Update the Docker containers
update: down build up

# Install composer dependencies
composer-install:
	docker-compose -f docker/docker-compose.yml exec app composer install

# Dump autoload files
dump-autoload:
	docker-compose -f docker/docker-compose.yml exec app composer dump-autoload

# Generate migrations
make-migration:
	docker-compose -f docker/docker-compose.yml exec app php expense-tracker diff

# Run migrations
migrate:
	docker-compose -f docker/docker-compose.yml exec app php expense-tracker migrations:migrate --no-interaction

# Load fixtures
fixtures:
	docker-compose -f docker/docker-compose.yml exec app php expense-tracker app:generate-data-fixtures --no-interaction

# Install npm dependencies
npm-install:
	docker-compose -f docker/docker-compose.yml exec app npm install

# Build assets for development
npm-dev:
	docker-compose -f docker/docker-compose.yml exec app npm run dev

#  build assets during development & watch for changes
npm-watch:
	docker-compose -f docker/docker-compose.yml exec app npm run watch

# Build assets for production
npm-build:
	docker-compose -f docker/docker-compose.yml exec app npm run build

# Generate a new application key
generate-key:
	docker-compose -f docker/docker-compose.yml exec app php expense-tracker app:generate-key --no-interaction