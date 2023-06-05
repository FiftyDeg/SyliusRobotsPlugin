#!/bin/bash

export HOST_UID=$(id -u)
export HOST_GID=$(id -g)

# Remove dev volumes before starting, otherwise docker will reuse mysql dev database
docker-compose --env-file ./.env.dev down -v \
&& docker-compose -f docker-compose.yml -f docker-compose.test.yml --env-file ./.env.test up \
    --remove-orphans \
    --force-recreate \
    --build \
    --abort-on-container-exit \
    --exit-code-from php
