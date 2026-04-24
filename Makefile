.PHONY: help install start stop restart test migrate seed backup clean

help:
	@echo "Available commands:"
	@echo "  make install   - Install all dependencies"
	@echo "  make start     - Start all services"
	@echo "  make stop      - Stop all services"
	@echo "  make test      - Run tests"
	@echo "  make migrate   - Run migrations"
	@echo "  make seed      - Seed database"
	@echo "  make backup    - Backup database"

install:
	cd backend && composer install
	cd frontend && npm install
	cd realtime && npm install

start:
	docker-compose up -d

stop:
	docker-compose down

test:
	cd backend && php artisan test

migrate:
	docker exec security-php php artisan migrate

seed:
	docker exec security-php php artisan db:seed

backup:
	docker exec security-php php artisan backup:run

clean:
	docker-compose down -v
	rm -rf backend/vendor frontend/node_modules realtime/node_modules