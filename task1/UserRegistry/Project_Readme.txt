Please run the containers separately and in this order

docker-compose build
docker-compose up db
docker-compose up app

Project will be accessible on http://localhost:8080
Route for users listing on http://localhost:8080/users

For testing phpunit
docker-compose exec app bash
vendor/bin/phpunit