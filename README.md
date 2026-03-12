# Cloud Deployment Guide

This document describes the deployment architecture and step-by-step process for deploying the **Rj's Event Styling Booking System** to the cloud.

## Architecture Overview

* **Framework:** Laravel 11 (PHP 8.2+)
* **Frontend:** Blade Templates + Tailwind CSS + Alpine.js + FullCalendar
* **Build Tool:** Vite
* **Deployment Platform:** Railway (automatic builds via Nixpacks)
* **Database:** MySQL (provisioned on Railway)
* **File Storage:** Amazon S3 (for payment proof uploads)
* **Authentication:** Laravel Breeze (role-based: Admin / User)

---

## System Architecture

```
User (Browser)
       |
       v
Railway (Laravel Application)
  |              |
  v              v
MySQL          AWS S3
(Database)     (File Storage - Payment Proofs)
```

---

# 1. Setting Up AWS S3 for File Storage

Since Railway uses ephemeral storage (files are wiped on every deploy), Amazon S3 is used to persist uploaded files such as payment proof screenshots.

## Step 1: Create an S3 Bucket

1. Go to [AWS S3 Console](https://s3.console.aws.amazon.com/)
2. Click **Create bucket**
3. Enter a bucket name (e.g., `booking-system-upload-things`)
4. Select the region **Asia Pacific (Singapore) - ap-southeast-1**
5. Uncheck **Block all public access** (since uploaded payment proofs need to be viewable)
6. Acknowledge the warning and click **Create bucket**

## Step 2: Configure Bucket Policy

Go to your bucket > **Permissions** > **Bucket Policy** and add:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicReadGetObject",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::booking-system-upload-things/*"
        }
    ]
}
```

Replace `booking-system-upload-things` with your actual bucket name.

## Step 3: Create IAM User for S3 Access

1. Go to [AWS IAM Console](https://console.aws.amazon.com/iam/)
2. Click **Users** > **Create user**
3. Enter a username (e.g., `booking-system-s3-user`)
4. Click **Next**, then **Attach policies directly**
5. Click **Create policy** and use the following JSON:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "s3:PutObject",
                "s3:GetObject",
                "s3:DeleteObject"
            ],
            "Resource": "arn:aws:s3:::booking-system-upload-things/*"
        }
    ]
}
```

6. Name the policy (e.g., `BookingSystemS3Access`) and create it
7. Attach this policy to the user
8. Go to the user > **Security credentials** > **Create access key**
9. Select **Application running outside AWS**
10. Save the **Access Key ID** and **Secret Access Key** securely

> **Important:** Never commit these keys to your repository. Store them only in Railway environment variables.

## Step 4: Configure CORS (Optional)

If you need direct browser uploads to S3, go to your bucket > **Permissions** > **CORS** and add:

```json
[
    {
        "AllowedHeaders": ["*"],
        "AllowedMethods": ["GET", "PUT", "POST"],
        "AllowedOrigins": [
            "https://it12booking-system-production.up.railway.app"
        ],
        "ExposeHeaders": []
    }
]
```

---

# 2. Deploying the Application to Railway

## Step 1: Push Project to GitHub

```bash
git add .
git commit -m "Initial commit"
git push origin main
```

Make sure your `.gitignore` includes:

```
.env
/vendor
/node_modules
/public/build
```

> **Important:** Never commit the `.env` file to GitHub. It contains secrets like your APP_KEY and AWS credentials.

## Step 2: Create a Railway Project

1. Go to [https://railway.app](https://railway.app)
2. Click **New Project**
3. Select **Deploy from GitHub repo**
4. Connect your GitHub account and select your repository
5. Railway will auto-detect that this is a Laravel (PHP) project

## Step 3: Provision a MySQL Database

1. In your Railway project dashboard, click **New** > **Database** > **MySQL**
2. Railway will provision a MySQL instance and provide connection credentials
3. Click on the MySQL service to view the connection variables:
    - `MYSQL_HOST`
    - `MYSQL_PORT`
    - `MYSQL_DATABASE`
    - `MYSQL_USER`
    - `MYSQL_PASSWORD`

## Step 4: Set Environment Variables

In your Railway project, click on your **web service** > **Variables** and add the following:

### Application Settings

```
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:YourAppKeyHere
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://your-app.up.railway.app
```

> Generate an APP_KEY locally by running `php artisan key:generate --show` and paste the output.

### Database Settings

```
DB_CONNECTION=mysql
DB_HOST=${MYSQL_HOST}
DB_PORT=${MYSQL_PORT}
DB_DATABASE=${MYSQL_DATABASE}
DB_USERNAME=${MYSQL_USER}
DB_PASSWORD=${MYSQL_PASSWORD}
```

> You can use Railway's variable references (`${MYSQL_HOST}`, etc.) to automatically link to your MySQL service.

### AWS S3 Settings

```
AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_BUCKET=booking-system-upload-things
AWS_USE_PATH_STYLE_ENDPOINT=false
FILESYSTEM_DISK=s3
```

### Other Settings

```
BCRYPT_ROUNDS=12
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
CACHE_STORE=file
LOG_CHANNEL=stack
LOG_LEVEL=error
```

## Step 5: Configure the Build

The project includes a `railway.toml` file that configures the build:

```toml
[build]
builder = "nixpacks"

[deploy]
startCommand = "php artisan migrate --force && php artisan storage:link 2>/dev/null; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
```

This tells Railway to:
- Use **Nixpacks** as the build system
- Run database migrations automatically on each deploy
- Create the storage symlink
- Start the Laravel development server on the correct port

## Step 6: Deploy

Railway deploys automatically when you push to the `main` branch. During deployment, Nixpacks will:

1. Detect the PHP/Laravel project
2. Install PHP 8.2+ and required extensions
3. Run `composer install` (installs PHP dependencies)
4. Install Node.js and run `npm install` + `npm run build` (compiles Tailwind CSS and JavaScript via Vite)
5. Execute the start command defined in `railway.toml`

You can monitor the deployment logs in the Railway dashboard under the **Deployments** tab.

## Step 7: Verify Deployment

1. Visit your Railway-provided URL (e.g., `https://it12booking-system-production.up.railway.app`)
2. Verify the landing page loads with all event images (Wedding, Birthday, etc.)
3. Register a user account and test the login flow
4. Create a test booking and submit a payment proof screenshot to confirm S3 uploads are working
5. Log in as admin to verify payment proof images display correctly

---

# 3. How File Storage Works

## Static Assets (Event Images, Logos)

Static images like event cards and logos are stored in the `public/img/` directory and served directly by the web server. These are committed to the repository and persist across deploys.

```
public/img/
  Wedding.jpg
  Birthday.jpg
  Christening.jpg
  Pageant.jpg
  Debut.jpg
  corporate.jpg
  rj_logo.jpg
  ...
```

## Dynamic Uploads (Payment Proofs)

User-uploaded files (payment proof screenshots) are stored in **Amazon S3** to ensure they persist across Railway deployments.

- **Upload path:** `payment-proofs/` directory in the S3 bucket
- **Upload handler:** `app/Http/Controllers/PaymentController.php`
- **Display:** Admin payment details view retrieves the image URL using `Storage::disk('s3')->url()`

---

# 4. Project Structure

```
it12_Booking-System/
  app/
    Http/Controllers/       # Application controllers
      AdminController.php       # Admin dashboard, user management, reports
      BookingController.php     # Booking CRUD and status management
      ChatController.php        # Real-time messaging between admin and users
      EventController.php       # Event type management
      InvoiceController.php     # Invoice generation and viewing
      LandingController.php     # Public landing page
      NotificationController.php # In-app notifications
      PaymentController.php     # Payment processing and S3 uploads
      ProfileController.php    # User profile management
    Models/                 # Eloquent models
      Booking.php               # Booking records
      BookingInventory.php      # Pivot: bookings <-> inventory items
      Event.php                 # Event types (Wedding, Birthday, etc.)
      Inventory.php             # Inventory/stock management
      Invoice.php               # Generated invoices
      Message.php               # Chat messages
      Notification.php          # User notifications
      Payment.php               # Payment records
      User.php                  # User accounts (Admin/User roles)
  frontend/
    views/                  # Blade templates (custom path)
    css/app.css             # Tailwind CSS entry point
    js/app.js               # Alpine.js + FullCalendar entry point
  config/                   # Laravel configuration files
  database/migrations/      # Database migration files
  public/
    img/                    # Static event images and logos
    build/                  # Vite-compiled assets (auto-generated)
  routes/
    web.php                 # Web routes (admin and user)
    auth.php                # Authentication routes (Laravel Breeze)
  railway.toml              # Railway deployment configuration
  .env                      # Environment variables (DO NOT COMMIT)
  composer.json             # PHP dependencies
  package.json              # Node.js dependencies
  vite.config.js            # Vite build configuration
```

---

# 5. Local Development Setup

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL

## Step 1: Clone the Repository

```bash
git clone https://github.com/onin070528/it12_Booking-System.git
cd it12_Booking-System
```

## Step 2: Install Dependencies

```bash
composer install
npm install
```

## Step 3: Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your local database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it12_db_rjbooking
DB_USERNAME=root
DB_PASSWORD=
```

For local development, you can use local file storage:

```
FILESYSTEM_DISK=local
```

Or connect to S3 by adding your AWS credentials to `.env`.

## Step 4: Run Migrations

```bash
php artisan migrate
```

## Step 5: Create Storage Symlink

```bash
php artisan storage:link
```

## Step 6: Start Development Server

```bash
composer dev
```

This starts the following services concurrently:
- Laravel development server
- Queue listener
- Log viewer (Laravel Pail)
- Vite dev server (for hot-reloading CSS/JS)

---

# 6. Troubleshooting

| Issue | Solution |
| --- | --- |
| Images not loading after deploy | Ensure filenames match exactly (case-sensitive on Linux). E.g., `Wedding.jpg` not `wedding.jpg` |
| Payment proof screenshots missing | Verify `FILESYSTEM_DISK=s3` and AWS credentials are set in Railway environment variables |
| S3 upload fails | Check that the IAM user has `s3:PutObject` permission on the bucket |
| S3 images not displaying | Ensure the bucket policy allows public `s3:GetObject` access |
| Database connection error | Verify MySQL service is running on Railway and `DB_*` variables are correctly set |
| 500 error on deploy | Set `APP_DEBUG=true` temporarily to see the full error, then set it back to `false` |
| Assets not styled (no CSS) | Ensure `npm run build` runs during deployment (Nixpacks does this automatically) |
| Build fails with "secret not found" | Ensure `railway.toml` has `builder = "nixpacks"` to avoid Railpack secret detection issues |
