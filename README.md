# DoetKu - Financial Tracker

[![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

DoetKu is a simple web application for managing personal income and expenses. The app allows users to add, edit, delete transactions, and view summaries of their finances. It also includes features like user authentication and registration.

![DoetKu](https://github.com/alfast456/DoetKu/blob/main/public/img/dashboard.png?raw=true)

## Technologies Used
- **Laravel 10**: A popular PHP framework for web development.
- **PHP 8**: The programming language used for server-side logic.
- **MySQL**: Relational database for storing transaction data and user information.
- **Bootstrap**: Frontend framework used for responsive and mobile-first web design.
- **SASS**: A CSS preprocessor for maintaining stylesheets efficiently.

## Features
- **Add, Edit, and Delete Transactions**: Manage your income and expense records easily.
- **Summary of Transactions**: View a summary of your financial records over time.
- **Responsive Design**: Fully responsive layout that works across all devices.
- **User Authentication**: Secure user login and registration.
- **User Registration**: Users can create accounts to track their financial data.
- **PDF Export**: Generate and download transaction reports as PDFs.
- **And more**: Additional features to help manage your finances.

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository**
    ```bash
    git clone https://github.com/alfast456/DoetKu.git
    ```

2. **Install the dependencies**
    Navigate to the project directory and run:
    ```bash
    composer install
    ```

3. **Create a new database**
    - Create a new database in MySQL.
    - Import the database schema from the `database` directory.

4. **Set up environment file**
    - Copy the `.env.example` file to `.env` and update the database configuration to match your database credentials.
    ```bash
    cp .env.example .env
    ```

5. **Generate a new application key**
    ```bash
    php artisan key:generate
    ```

6. **Run database migrations**
    If the migrations have not been run yet, use:
    ```bash
    php artisan migrate
    ```

7. **Run the application**
    Start the Laravel development server:
    ```bash
    php artisan serve
    ```

8. **Access the application**
    Open your browser and navigate to:
    ```bash
    http://localhost:8000
    ```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
