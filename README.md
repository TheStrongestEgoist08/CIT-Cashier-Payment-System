# Project Title: 
    Cashier Payment System

# Authors: 
    Bigal, Carl Michael C.
    Camarines, Glendo
    Sanchez, Ejan Lorens

# Program: 
    BSIT 4-1

# Project Description: 
    This project is a web-based Cashier Payment System designed to manage and process student payments efficiently. It provides a user-friendly interface for handling transactions, tracking payment records, and managing outstanding balances. The system ensures accurate financial monitoring, reduces manual errors, and improves the overall payment workflow for administrators and cashiers.

# Features:
    User Authentication                     : Secure login system for administrators and cashiers with role-based access control.
    Student Payment Management              : Allows cashiers to view student accounts, outstanding balances, and payable items.
    Payment History Tracking                : Keeps detailed records of all transactions for auditing and review purposes.
    Report Generation                       : Generates financial reports such as daily collections, payment summaries, and transaction logs.
    Search and Filtering                    : Quickly find student records, transactions, and payable items using filters.
    Notifications                           : Alerts trainers when a trade offer is received, accepted, or declined.
    Security Features                       : Protects sensitive financial data through authentication, validation, and access restrictions.

# Technologies Used:
    Backend     : PHP
    Frontend    : HTML, CSS, JavaScript, Blade (for templating), Alphine.js
    Database    : MySQL for storing data, student and user information
    Framework   : Laravel 12
    Tools       : Visual Studio Code, Laragon (development environment)

# Installation:

# Step 1:
    Extract The zip file

# Step 2:
    Find and Open the extracted folder on your IDE

# Step 3:
    Set up .env file database password based on the password of your root account

# Step 4:
    Migrate the database by opening a new terminal in your ide
    Type the command "php artisan migrate"

    or 

    Use the SQL statements to create the database 

# Step 5:
    Populate the database using database seeder
    Type the command "php artisan db:seed"

# Step 6
    Type the command "php artisan serve" on your terminal to run the projserver. 
    After that open new terminal in your IDE and type the command "npm run dev" to see the design of the website

    Then ctrl + click the link provided [http://127.0.0.1:8000]
