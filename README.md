# DENR Travel Order System

## Overview

The **PENRO Travel Order System** is a web-based application designed to create, manage, and monitor Travel Orders for the Provincial Environment and Natural Resources Office (PENRO) Leyte. The system improves operational efficiency by digitalizing the process and auto routing the document and also supporting multi-step approval. Allows users to print said travel orders, also allows them to upload supporting documents attachments to the Travel Order via drive.

---

## Features

* Role-Based Access Control
* Google OAuth Authentication
* Travel Order Management (Create, Update, Delete, Store, Print, Download)
* Hierarchical Organization Structure
* File Management with Google Drive integration
* Report Generation and Printing
* Automated Email Reminders
* Audit Trail for user activity logging

---

## Tech Stack

* **Backend:** CodeIgniter 4 (PHP)
* **Frontend:** HTML, CSS, Bootstrap, AdminLTE
* **Database:** MySQL
* **Authentication:** Session-based and Google OAuth
* **Cloud Storage:** Google Drive API

---

## Requirements

* PHP ^8.2
* Composer ^2.8
* MySQL Database
* Google Cloud Console (OAuth Client + Service Account)
* Gmail App Password (for SMTP email service)

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Zaphkiel14/travel-order-system-penro-leyte.git
cd travel-order-system-penro-leyte
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

Copy the `env` file and rename it to `.env`, then update the following:

#### Application Settings

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
```

> Set `CI_ENVIRONMENT` to `production` when deploying.

#### Database Configuration

```
database.default.hostname = localhost
database.default.database = your_database_name
database.default.username = root
database.default.password =
```

### 4. Run Migrations

```bash
php spark migrate
```

---

## Email Configuration

Add the following to your `.env` file:

```
email.protocol = smtp
email.SMTPHost = smtp.gmail.com
email.SMTPUser = youremail@example.com
email.SMTPPass = your_app_password
email.SMTPPort = 587
email.SMTPCrypto = tls
email.fromEmail = youremail@example.com
email.fromName = "DENR Travel Order System"
email.mailType = html
email.charset = UTF-8
email.wordWrap = true
```

### Automated Emails

To enable scheduled email notifications, configure a scheduler:

* **Linux:** Cron Job
* **Windows:** Task Scheduler

Command to run:

```bash
php spark tasks:run
```

This triggers the pending travel order reminder command.

---

## Google Drive Integration

### 1. Store Credentials

Place your Google credentials in:

```
writable/keys/
    client_secret.json
    service_account.json
```

### 2. Configure `.env`

```
drive.folderId = 
GOOGLE_CLIENT_SECRET_PATH = writable/keys/client_secret.json
GOOGLE_SERVICE_ACCOUNT_PATH = writable/keys/service_account.json
```

> `drive.folderId` is the ID from your Google Drive folder URL.

## Running the Application

```bash
php spark serve
```

---

## Seeders

### Default Setup

```bash
php spark db:seed UsersSeeder
php spark db:seed DatabaseSeeder
```

---

## Project Structure

```
/app        Application logic (Controllers, Models, Views)
/public     Public assets
/writable   Logs, cache, uploads, and secure files
/tests      Automated tests
```

---

## Core Modules

### Travel Order Management

Handles Travel Order creation, tracking, approval, printing, downloading attachments.

### Audit Trail

Records user activities for accountability.

### Reporting

Generates printable reports and data exports.

---

## Security Practices

* Password hashing
* Role-based access control
* Input validation and sanitization
* CSRF protection (CodeIgniter built-in)
* Secure handling of API credentials via `.env`
* Secure end-points with custom error handler



---

## License

This project is intended for institutional use.

---

## Developers

* Vincent Eleazar G. Uykieng (Zaphkiel14)

---

## Support

For issues or inquiries, open an issue in the repository or contact the developers.
