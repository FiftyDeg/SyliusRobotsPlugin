#!/bin/bash
export $(grep -v '^#' .env.dev | xargs)

docker exec -u root -it "fiftydeg_sylius_robots_plugin_php" bash
