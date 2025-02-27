# SPMS Project [BACKEND]

## Introduction
Welcome to the SPMS (Subscription Platform Management System) API backend, built with Laravel 12. This REST API powers the SPMS platform, managing user subscriptions to content packages and channels. The frontend for this project is developed using Vue 3.js.

## Features
- User Management: Users can register, log in, and manage their subscriptions.
- Subscription System: Users can subscribe to packages, which include multiple channels.
- Admin Dashboard: Admins have full CRUD access to manage users, subscriptions, packages, and channels.
- API Documentation: Auto-generated Swagger API documentation.
- Developer Tools: Laravel Telescope for debugging and logging.

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
    php artisan migrate --seed
    ```
    (Includes seeding for initial test data)

6. Run Code Quality Tools:
    Format code using Laravel Pint:
    ```bash
    ./vendor/bin/pint
    ./vendor/bin/pint && php ./vendor/bin/phpcs -s
    ```

## Generate API Documentation
    Generate the Swagger documentation:
    ```bash
    php artisan l5-swagger:generate
    ```
    Access it at: http://localhost:8000/api/documentation.

## Running Unit Tests
To run the unit tests, execute the following command:
    ```bash
    php artisan test --bail --retry --watch
    ```

## Running the Application
### Development Server

    To start the Laravel development server:
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
