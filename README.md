# Musici

Modern AJAX-based music streaming platform built with PHP, MySQL, JavaScript, jQuery, HTML and CSS.

## Features

* AJAX-powered user experience (without full page reloads)
* Admin dashboard and management panel
* OTP login via mobile verification code
* Username and password authentication
* Role-based access control (Admin, Manager, Customer)
* Music categorization system
* Dynamic site settings panel
* Responsive user interface
* MySQL database integration

## Tech Stack

* PHP
* MySQL
* JavaScript
* jQuery
* AJAX
* HTML5
* CSS3

## Screenshots

### Homepage

![Homepage](assets/screenshots/home.png)

### Admin Dashboard

![Admin Dashboard](assets/screenshots/admin-panel.png)

### Login System

![Login](assets/screenshots/login.png)

### Settings Panel

![Settings](assets/screenshots/settings.png)

## Installation

1. Clone the repository

```bash
git clone https://github.com/Ali-Rahimpoor/musici.git
```

2. Install dependencies

```bash
composer install
```

3. Create environment file

Copy:

`.env.example`

to:

`.env`

Then configure your database credentials.

4. Import database

Import:

`musici.sql`

using phpMyAdmin or MySQL CLI.

5. Run project

Use XAMPP, Laragon or any PHP local server.

## Project Structure

```txt
musici/
├── admin/
├── assets/
├── css/
├── js/
├── vendor/
├── musici.sql
├── config.php
├── index.php
└── .env.example
```
