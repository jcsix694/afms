# AFMS

A finance management system.

## Prerequistes  
- PHP v7.3.33 
- Composer v2.1.3

## Getting started 
1. Install prerequisites  
2. Pull down Repository
2. Run `$ composer install`  
3. Copy `/.env.example` and rename to `.env`  
4. Update DB details in `.env` to match your local setup 
5. Run `alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'` to allow to boot up using `sail`
6. Run `sail up` to start the project

## Running Migrations
1. Run `sail up -d` to start the project
2. Run `sail artisan migrate:status` to check the migrations to run
2. Run `sail artisan migrate` to run the migrations

## Other commands 
- Run `sail down` to stop the project
- Run `sail ps` to check containers that are running


