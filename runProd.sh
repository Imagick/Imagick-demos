#!/usr/bin/env bash


docker-compose build
# docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d


docker-compose -f docker-compose.yml -f docker-compose.prod.yml up  --build --force-recreate installer

docker-compose -f docker-compose.yml -f docker-compose.prod.yml up  --build --force-recreate js_sass_prod_builder


docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build --force-recreate installer imagick_php_backend redis web_server workers
