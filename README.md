# techhunt2020_tha
Take home assignment for Govtech TechHunt2020. This project is done using `Laravel 7.16.1`

# 1. Initial Setup

You only need to do the following steps the first time you have cloned the repository.

## 1.1. Pre-Requisites

1. Install docker and docker compose
2. Install VirtualBox

### Installing docker

### Windows 10 64-bit: Pro, Enterprise, or Education (Build 15063 or later).
Docker is supported in these versions of Windows.

1. Install [Docker Desktop](https://docs.docker.com/docker-for-windows/install/)

#### Windows without docker support
Other Windows versions will require **Docker Toolbox** and **VirtualBox**.

1. [Install Docker Toolbox](https://docs.docker.com/toolbox/toolbox_install_windows/)
2. Run Docker Toolbox - It is a VM that runs in the background

#### Linux(Ubuntu 18.04)
To be written

## Instructions To Run App
1. Make a copy of .env file 
```bash
cp .env.example .env
```

2. Open .env file, fill in the `DB_PASSWORD` variable to be anything you want. Below is an example of what you can name it as.
```
DB_PASSWORD=testpassword
```

3. Run the following command in this folder's root directory. Wait for a while for the environment to be setup.
```bash
docker-compose build; docker-compose up;
```

4. Install composer files
```bash
docker exec techhunt2020-app composer install
```

5. Get the mysql docker container's ip address
```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' techhunt2020-db
```

6. Change .env file, `DB_HOST` parameter to ip address obtained in step 5. An example is shown below.
```
DB_HOST=172.0.18.100
```

7. Create Employees Table in Database using the command below.
```bash
docker exec techhunt2020-app php artisan migrate --path=//database/migrations/standalone_mig
```

## 1.2. Required steps every run

The subsequent time you start running the app, you must use the following steps below to get the app  to work.

1. Make docker-compose start the containers. 
```bash
docker-compose up;
```

2. Get the mysql docker container's ip address
```
docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' techhunt2020-db
```

3. Change .env file, `DB_HOST` parameter to ip address obtained in step 1.

## 1.3. Closing app
 - Either close the docker-compose bash that is running OR 
 - run the following command `docker-compose down;`

# 2. Application 
## 2.1 Generating Data
1. Generate 50 Random Employee Data
```
docker exec techhunt2020-app php artisan db:seed --class=EmployeeSeeder
```

## 2.2. Running tests

1. Run all tests
```bash
docker exec techhunt2020-app php artisan test
```