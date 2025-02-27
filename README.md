# SPMS Project [BACKEND]

## Introduction
Welcome to the SPMS (Subscription Platform Management System) API REST backend developed in Laravel 12! This README provides instructions for setting up and running the Laravel REST API backend. The frontend for this project was developed using the Vue 3.js framework.

## Installation

1. Clone the repository to your local machine:
   ```bash
   git clone https://github.com/garciagaston/Subscription-Platform-Management-System-BACKEND.git
   ```
2. Navigate to the project directory:
   ```bash
    cd Subscription-Platform-Management-System-BACKEND
    ```
3. Install Composer dependencies:
    ```bash
    composer install
    ```
4. Rename .env.example to .env:
    ```bash
    mv .env.example .env
    ```
Update the .env file with your database and Redis information.

5. Run database migrations:
    ```bash
    php artisan migrate
    ```
6. Run Laravel PINT:
    ```bash
    ./vendor/bin/pint
    ```
7. Run PHPSTAN:
    ```bash
    ./vendor/bin/phpstan analyse
    ```

## Generate Swagger API Documentation:
    ```bash
    php artisan l5-swagger:generate
    ```
Access the generated Swagger documentation at http://localhost:8000/api/documentation.

## Running Unit Tests
To run the unit tests, execute the following command:
    ```bash
    php artisan test
    ```

## Running the Application
To start the Laravel development server, run:
    ```bash
        npm install && npm run build
        composer run dev
    ```

You can now access the application in your web browser at http://localhost:8000.

## Laravel API URL
http://localhost:8000/

## Laravel API SWAGGER documentation
http://localhost:8000/api/documentation

## Laravel Telescope
http://localhost:8000/telescope

## FAKE TEST USERS
    ```bash
email: admin@fake.com
pass: password
    ```
