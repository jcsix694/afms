# AFMS

A finance management system.

## Prerequistes  
- PHP v7.3.33 
- Composer v2.1.3

## Getting started 
1. Install prerequisites  
2. Pull down Repository
3. Run `$ composer install`  
4. Run `npm install`
5. Copy `/.env.example` and rename to `.env`  
6. Update DB details in `.env` to match your local setup 
7. Update OPPWA_API_URL, OPPWA_API_ENTITY_ID and OPPWA_API_ACCESS_TOKEN with the credentials in the `.env`
8. Run `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'` to allow to boot up using `sail`
9. Run `sail up -d` to start the project
10. Run migrations (See Running Migrations)

## Running Migrations
1. Run `sail artisan migrate:status` to check the migrations to run
2. Run `sail artisan migrate` to run the migrations

## Other commands 
- Run `sail down` to stop the project
- Run `sail ps` to check containers that are running


