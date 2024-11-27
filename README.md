# Project Name

## Introduction

This project is built using Laminas MVC framework. It includes various modules and configurations to get you started with a robust web application.

## Prerequisites

- PHP 8.3 or higher
- Composer
- Docker (optional, for containerized development)

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/your-repo.git
   cd your-repo
   ```

2. Install dependencies using Composer:

   ```bash
   composer install
   ```

3. Set up the database:

   - Ensure you have SQLite installed.
   - The database file is located at `data/database.sqlite`.

4. Configure environment variables:
   - Copy `.env.example` to `.env` and update the necessary environment variables.

## Running the Application

### Using PHP Built-in Server

You can run the application using PHP's built-in server:

```bash
$ php -S 0.0.0.0:8080 -t public
```
