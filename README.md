# Statify

A Laravel based self-hosted websiste statistics app

## Installation

Define the `STATIFY_SCRIPT_NAME="yourscript"` in the `.env` file.

## Deploy on Forge

### Installation

#### New Site
- Project Type : General PHP / Laravel
- PHP Version : PHP 8.2
- Web Directory : /public
- Create Databse

```bash
cd /home/forge/counted.yourdomain.tld
git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

if [ -f artisan ]; then
    $FORGE_PHP artisan migrate --force
fi
```

### Environment

`DATABASE_NAME="database_name"`

`COUNTED_SCRIPT_NAME="custom_script_name"`

`CACHE_DRIVER=array`

### Scheduler

Command :

`php8.2 /home/forge/counted.yourdomain.tld/artisan schedule:run`

Frequency: Every Minutes

### Deamons

`php8.2 artisan horizon`

## Features
[ ] Import data from Google Analytics
[x] Multiple websites monitoring by teams
[ ] Not blocked by adblockers
[x] Self hosted
[ ] CSV Exports
[ ] Monitor site down
[ ] Fast

## Todo
[ ] Websites CRUD
[x] Send API
[x] JS stat script
[ ] Allow CORS websites array
[ ] Events CRUD
[ ] Events Aggregation
[ ] Schedule run delete events older than 24h every hours

agregated
- from (local, ga, etc)
- website_id
- day


## Installation

Environnement :
CACHE_DRIVER=array