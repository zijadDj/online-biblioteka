# Laravel Online Library Project

# Installation

## 1. Clone the Repository

```bash
git clone https://github.com/zijadDj/online-biblioteka.git
cd online-biblioteka
```

## 2. Install PHP Dependencies
```bash
composer install
```

## 3. Copy .env File

```bash
cp .env.example .env
```

## 4. Sail Setup
###  Requirements

- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running
- WSL 2 enabled (if on Windows)
- No native MySQL or Redis running on the same ports (Sail uses Docker ports)

Start the application:
```bash
./vendor/bin/sail up -d
```

## 5. Generate App Key

```bash
./vendor/bin/sail artisan key:generate.
```

## 6.Configure Database in .env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 7. Run Migrations
If you want to run migrations **outside** of the container:
```bash
./vendor/bin/sail artisan migrate

```

If you prefer to run migrations **inside** the container, first enter the container:
```bash
docker exec -it online-biblioteka-laravel.test-1 bash
```
Then run the migrations:
```bash
php artisan migrate
```
Note:
**Once inside the container, you can run all Laravel Artisan commands normally, just as you would on your local machine.**

## 8. Mailtrap Setup
- Create a free account at https://mailtrap.io.
- Create a new Inbox in the Mailtrap dashboard.
- Copy the SMTP credentials (host, port, username, password).

Configure your .env file with Mailtrap settings:
```bash
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="${APP_NAME}"
```



## 9. Contributing

- Please fork the repository and create a new branch for your feature or bugfix.
- All changes must be submitted through a pull request.
- The `main` branch is protected — direct commits are not allowed.
- At least one approval is required before a pull request can be merged.
- Write clear commit messages and follow Laravel coding standards.
