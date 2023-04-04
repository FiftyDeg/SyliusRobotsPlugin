#!/bin/bash
export $(grep -v '^#' .env | xargs)

docker exec -it "fiftydeg_sylius_robots_plugin_php" bash
