# Makefile

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

# Install composer dependencies
composer-install:
	docker-compose -f docker/docker-compose.yml exec app composer install

# Dump autoload files
dump-autoload:
	docker-compose -f docker/docker-compose.yml exec app composer dump-autoload

# Run migrations
migrate:
	docker-compose -f docker/docker-compose.yml exec app php expense-tracker migrations:migrate

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