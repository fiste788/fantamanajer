@echo off
docker exec -i php vendor/bin/phpcs %*
@echo on
