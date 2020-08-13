docker-compose exec php bin/console make:migration

2
3
4
cp api/.env.dist api/.env
docker-compose build
docker-compose up -d
docker-compose exec php bin/console doctrine:schema:update --force
docker-compose exec php bin/console doctrine:fixtures:load