# Counted 📏

> Work in progress

Counted is a powerful and user-friendly statistics application designed to streamline data collection and analysis. With Counted, users can effortlessly gather and track various metrics, enabling them to make informed decisions based on accurate and up-to-date statistical insights. The intuitive interface and comprehensive feature set make it easy to input, organize, and visualize data, empowering users to uncover meaningful patterns and trends. Whether it's for business analytics, research projects, or personal data tracking, Counted simplifies the process of harnessing the power of statistics, allowing users to unlock valuable insights and drive data-informed decision-making.

## Installation

Define the `COUNTED_SCRIPT_NAME="yourscript"` in the `.env` file.

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
- [ ] Imports
	- [ ] Google Analytics
	- [ ] Umami
	- [ ] Plausible
	- [ ] useFathom
- [x] Multiple websites monitoring by teams
- [ ] Not blocked by Ad Blockers
- [ ] RGPD Compliant
- [x] Self hosted
- [ ] CSV Exports
- [ ] Monitor uptime
- [ ] Console errors
- [ ] Fast
- [-] Database support
	- [x] MariaDB
	- [x] MySQL
	- [x] PostgreSQL
	- [ ] SQLite

## WIP
- [ ] Websites CRUD
- [x] Send API
- [x] JS stat script
- [x] Allow CORS websites array
- [ ] Events CRUD
- [ ] Events Aggregation
- [ ] Schedule run delete events older than 24h every hours
- [ ] Avg time comparaison to previous period
- [ ] Engagement rate comparaison to previous period

agregated
- from (local, ga, etc)
- website_id
- day