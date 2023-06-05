<a href="../README.md" target="_blank">Back</a>

## Development

### Start docker
1. Execute `cd ./.docker && ./bin/start_dev.sh`
2. Configure `/etc/hosts` adding the line `127.0.0.1    syliusplugin.local`


### Fixtures

Fixtures are configured in `src/Resources/config/fixtures/fiftydeg_sylius_robots_channels_suite.yaml`.  
Each time you start docker compose, fixtures are automatically executed (see `docker-compose.yaml`).  

<a href="./testing.md" target="_blank">Next: Testing</a>
