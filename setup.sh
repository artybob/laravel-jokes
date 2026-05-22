#!/bin/bash

echo "Starting Docker containers..."
docker compose up -d

echo "Waiting for MySQL to be ready..."
sleep 10

echo "Setting up .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

echo "Configuring database connection..."
docker compose exec php sh -c "sed -i 's/DB_HOST=mysql/DB_HOST=mysql/' .env"
docker compose exec php sh -c "sed -i 's/DB_DATABASE=laravel/DB_DATABASE=laravel/' .env"
docker compose exec php sh -c "sed -i 's/DB_USERNAME=laravel/DB_USERNAME=laravel/' .env"
docker compose exec php sh -c "sed -i 's/DB_PASSWORD=secret/DB_PASSWORD=secret/' .env"

echo "Generating app key..."
docker compose exec php php artisan key:generate

echo "Clearing cache..."
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear

echo "Running migrations..."
docker compose exec php php artisan migrate

echo "Testing jokes command..."
docker compose exec php php artisan jokes:fetch

echo "Setup complete!"
echo ""
echo "To test the API:"
echo "curl http://localhost:8080/api/jokes"
echo ""
echo "To start scheduler:"
echo "docker compose exec php php artisan schedule:work"
