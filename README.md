# User Module for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/durrbar/user-module.svg?style=flat-square)](https://packagist.org/packages/durrbar/user-module)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/durrbar/user-module/run-tests-L8.yml?branch=main&label=Tests)](https://github.com/durrbar/user-module/actions?query=workflow%3ATests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/durrbar/user-module.svg?style=flat-square)](https://packagist.org/packages/durrbar/user-module)

The **User Module** is a powerful and flexible Laravel package designed to streamline user management. It provides essential functionality for managing users, roles, and permissions in Laravel applications.

---

## Features

- User authentication (login, registration, password reset)
- Role-based access control (RBAC)
- Permission-based authorization
- API-ready user management endpoints
- Easily extendable and customizable

---

## Installation

### Requirements

- PHP >= 8.2
- Laravel >= 11.0

### Step 1: Install via Composer

```bash
composer require durrbar/user-module
```

### Step 2: Publish Config and Migrations

Publish the configuration and migration files using the following command:

```bash
php artisan durrbar:user-install
```

### Step 3: Run Migrations

Run the database migrations to set up the necessary tables:

```bash
php artisan migrate
```

### Step 4: Configure the Package

Edit the published configuration file located at `config/user-module.php` to customize settings such as default roles and permissions.

---

## Usage

### User Authentication

The package includes ready-to-use authentication routes for login, registration, and password reset. You can use the following endpoints:

- **POST /login** - Authenticate users
- **POST /register** - Register new users
- **POST /password-reset** - Request a password reset

---

## API Documentation

The package includes the following API endpoints:

| Endpoint                   | Method | Description                  |
|----------------------------|--------|------------------------------|
| `/api/users`               | GET    | List all users               |
| `/api/users/{id}`          | GET    | Retrieve a specific user     |
| `/api/users`               | POST   | Create a new user            |
| `/api/users/{id}`          | PUT    | Update a user's information  |
| `/api/users/{id}`          | DELETE | Delete a user                |

---

## Testing

To run tests, use the following command:

```bash
php artisan test
```

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

---

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new feature branch.
3. Commit your changes.
4. Submit a pull request.

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

---

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

---

## Support

For issues or feature requests, please open an issue on the [GitHub repository](https://github.com/durrbar/user-module).

---

## Credits

- Author: [Durrbar](https://github.com/durrbar)
- Packagist: [durrbar/user-module](https://packagist.org/packages/durrbar/user-module)

