# AFMS

A finance management system which is integrated with OPPWA to create a checkout, payments and issue refunds. This Repository is ysing docker to allow use of Laravel & MySql

For the GUI, please use the following Repository: https://github.com/jcsix694/afms-vuejs

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

## Services
- AFMS API: 80
- MySQL: 3366
- Redis: 6379

## Endpoints
- POST api/auth <em>(Authorizes a loging and returns a Bearer Token for the user)</em>
- POST api/users <em>(Creates a customer)</em>
- GET api/users/me <em>(Returns data a user using an active Bearer Token)</em>
- POST api/checkouts <em>(Creates a checkout within OPPWA for a user with an active Bearer Token)</em>
- GET api/checkouts <em>(Returns all the checkouts with any payments and refunds for a user with an active Bearer Token)</em>
- GET api/checkouts/{id} <em>(Returns a single checkout by checkout id with any payments and refunds for a user with an active Bearer Token)</em>
- POST api/payments/refund <em>(Creates a refund for a payment for a user with an active Bearer Token)</em>
