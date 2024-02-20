# SPMS Project [BACKEND]

## Introduction
Welcome to the SPMS (Sample Project Management System) project! This README provides instructions for setting up and running the Laravel REST API backend. The frontend for this project was developed using the Vue 3.js framework.

## WIKI
https://github.com/garciagaston/Subscription-Platform-Management-System-BACKEND/wiki

## Installation

1. Clone the repository to your local machine:
   ```bash
   git clone https://github.com/garciagaston/Subscription-Platform-Management-System-BACKEND.git
2. Navigate to the project directory:
   ```bash
    cd Subscription-Platform-Management-System-BACKEND
3. Install Composer dependencies:
    ```bash
    composer install
4. Rename .env.example to .env:
    ```bash
    mv .env.example .env
Update the .env file with your database and Redis information.

5. Run database migrations:
    ```bash
    php artisan migrate
6. Run Laravel PINT:
    ```bash
    ./vendor/bin/pint
7. Run PHPLINT:
    ```bash
    ./vendor/bin/phplint
8. Run PHPSTAN:
    ```bash
    ./vendor/bin/phpstan analyse

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
    php artisan serve
    ```

You can now access the application in your web browser at http://localhost:8000.

## Notes
Ensure that you have PHP, Composer, and other necessary dependencies installed on your system before proceeding with the installation.
Make sure your web server is properly configured to serve a Laravel application if you're not using the built-in development server.


## Laravel API URL
https://spms-back-c49dc9b00505.herokuapp.com/

## Laravel API SWAGGER documentation
https://spms-back-c49dc9b00505.herokuapp.com/api/documentation

## Laravel Telescope
https://spms-back-c49dc9b00505.herokuapp.com/telescope


## HEROKU

### Run migrations
    ```bash
    heroku run php artisan migrate --path=database/migrations --app spms-back
    ```

## TEST USERS
    ```bash
email: admin@fake.com
pass: password
    ```

## Entity Relationship Diagram (ERD)
https://lucid.app/lucidchart/12fa0d09-706b-4166-a181-6c2f402bbe83/edit?viewport_loc=-1303%2C-820%2C2537%2C1493%2C0_0&invitationId=inv_9abb45da-d6e9-4e8e-b58e-c10419a4439f
