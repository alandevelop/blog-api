## About
- Standard CRUD operations for Post and Comments entities
- Links for pagination in JSON resources
- Separate request validation classes for keeping controllers clean
- Feature test for for Post and Comments entities
- Docker environment 

## Installation
- run `docker-compose up`
- add `127.0.0.1 blog-api` in `/etc/hosts`
- copy `.env.example` to `.env` file
- run `. ./alias.bash` in the terminal to add aliases
- run `composer install`
- run `artisan key:generate`
- run `artisan db:seed`

## Testing
In case of using postman or other rest client please provide Accept: `application/json` header for all queries
