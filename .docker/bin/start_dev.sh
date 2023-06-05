#!/bin/bash

export HOST_UID=$(id -u)
export HOST_GID=$(id -g)

# Remove test volumes before starting, otherwise docker will reuse mysql dev database
docker-compose --env-file ./.env.test down -v \
&& docker-compose --env-file ./.env.dev up --force-recreate --remove-orphans --build
