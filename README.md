# SPMS Project

This is the README file for the SPMS (Subscription Platform Management System) project. Below are instructions for setting up and running the project.

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
    php artisan l5-swagger:generate
Access the generated Swagger documentation at http://localhost:8081/api/documentation.

## Running Unit Tests
To run the unit tests, execute the following command:
    ```bash
    php artisan test

## Running the Application
To start the Laravel development server, run:

    php artisan serve
You can now access the application in your web browser at http://localhost:8081.

## Notes
Ensure that you have PHP, Composer, and other necessary dependencies installed on your system before proceeding with the installation.
Make sure your web server is properly configured to serve a Laravel application if you're not using the built-in development server.
