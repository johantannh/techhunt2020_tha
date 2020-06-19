# techhunt2020_tha
Take home assignment for Govtech TechHunt2020

## Initial Setup

You only need to do the following steps the first time you have cloned the repository.

> Pre-Requisite: Make sure you have docker installed

1. Run the following command in this folder's root directory. Wait for a while for the environment to be setup.
```bash
docker-compose build; docker-compose up;
```

2. Make copy of .env file 
```bash
cp .env.example .env
```

3. Install composer files
```bash
docker exec techhunt2020-app composer install
```

4. Create Employees Table in Database using the command below.
```bash
docker exec techhunt2020-app php artisan migrate --path=//database/migrations/standalone_mig
```

## Required steps every run
1. Get the mysql docker container's ip address
```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' techhunt2020-db
```

2. Change .env file, `DB_HOST` parameter to ip address obtained in step 1.


## Running tests

1. Run all tests
```bash
docker exec techhunt2020-app php artisan test
```