# techhunt2020_tha
Take home assignment for Govtech TechHunt2020

## Initial Setup
1. Make sure you have docker installed
2. Run the following command in this folder's root directory. Wait for a while for the environment to be setup.
```
docker-compose build; docker-compose up;
```
3. Make copy of .env file 
```
cp .env.example .env
```

## Required steps every run
1. Get the mysql docker container's ip address
```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' techhunt2020-db
```

2. Change .env file, `DB_HOST` parameter to ip address obtained in step 1.


## Creating DB tables

Note: This only needs to be run once

1. Create Employees Table in Database using the command below.
```bash
docker exec techhunt2020-app php artisan migrate --path=//database/migrations/standalone_mig
```

## Running tests

1. Run all tests
```bash
docker exec techhunt2020-app php artisan test
```