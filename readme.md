
# Projekt SI

```bash
# Make sure that docker engine is running, then run project containers
docker-compose up -d
# In php container:
cd app
composer install
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
# App should by available at http://localhost:8000
```