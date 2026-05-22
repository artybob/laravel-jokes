#!/bin/bash

echo "Starting Docker containers..."
docker compose up -d

echo "Waiting for MySQL to be ready..."
sleep 10

echo "Configuring .env..."
docker compose exec php sh -c "sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql/' .env"
docker compose exec php sh -c "sed -i 's/DB_DATABASE=laravel/DB_DATABASE=laravel/' .env"
docker compose exec php sh -c "sed -i 's/DB_USERNAME=root/DB_USERNAME=laravel/' .env"
docker compose exec php sh -c "sed -i 's/DB_PASSWORD=/DB_PASSWORD=secret/' .env"

echo "Generating app key..."
docker compose exec php php artisan key:generate

echo "Clearing cache..."
docker compose exec php php artisan config:clear

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
