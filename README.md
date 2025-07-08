# Laravel Online Library Project

# Installation

## 1. Clone the Repository

```bash
git clone https://github.com/zijadDj/online-biblioteka.git
cd online-biblioteka
```

## 2.Install PHP Dependencies
```bash
composer install
```

## 3.Copy .env File & Generate App Key

```bash
cp .env.example .env
php artisan key:generate
```

## 4.Configure Database in .env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 5. Run Migrations
```bash
php artisan migrate
```

## 6. Install Redis
```bash
https://redis.io/docs/latest/operate/oss_and_stack/install/archive/install-redis/
```


## 7. Mailtrap Setup
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

## 8. Contributing

- Please fork the repository and create a new branch for your feature or bugfix.
- All changes must be submitted through a pull request.
- The `main` branch is protected — direct commits are not allowed.
- At least one approval is required before a pull request can be merged.
- Write clear commit messages and follow Laravel coding standards.
