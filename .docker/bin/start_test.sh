#!/bin/bash

export HOST_UID=$(id -u)
export HOST_GID=$(id -g)

# Remove dev volumes before starting, otherwise docker will reuse mysql dev database
docker-compose --env-file ./.env.dev down -v \
&& docker-compose --env-file ./.env.test up --force-recreate --remove-orphans --build
