# RJ's Event Styling - Localhost Setup Guide
## How to Run the Booking System Locally

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

| Software | Minimum Version | Download Link |
|----------|----------------|---------------|
| PHP | 8.1+ | [php.net](https://www.php.net/downloads) |
| Composer | 2.0+ | [getcomposer.org](https://getcomposer.org/download/) |
| Node.js | 18+ | [nodejs.org](https://nodejs.org/) |
| MySQL/MariaDB | 8.0+ / 10.4+ | [mysql.com](https://dev.mysql.com/downloads/) |
| Git | Latest | [git-scm.com](https://git-scm.com/downloads) |

### 💡 Recommended: Use XAMPP or Laragon

For easier setup on Windows:
- **XAMPP:** [Download](https://www.apachefriends.org/) - Includes PHP, MySQL, Apache
- **Laragon:** [Download](https://laragon.org/download/) - Lightweight, includes everything

---

## 🚀 Step-by-Step Installation

### Step 1: Download/Clone the Project

**Option A: Clone with Git**
```bash
git clone [repository-url] booking-system
cd booking-system
```

**Option B: Download ZIP**
1. Download the project ZIP file
2. Extract to your desired location
3. Open terminal/command prompt in that folder

---

### Step 2: Install PHP Dependencies

```bash
composer install
```

If you encounter memory issues:
```bash
php -d memory_limit=-1 composer install
```

---

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

---

### Step 4: Environment Configuration

**Copy the example environment file:**

**Windows (Command Prompt):**
```cmd
copy .env.example .env
```

**Windows (PowerShell) / Mac / Linux:**
```bash
cp .env.example .env
```

---

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

---

### Step 6: Configure Database

1. **Create a new database** in MySQL:
   ```sql
   CREATE DATABASE booking_system;
   ```

2. **Edit the `.env` file** with your database details:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=booking_system
   DB_USERNAME=root
   DB_PASSWORD=your_password_here
   ```

   > 💡 **XAMPP users:** Password is usually empty (leave `DB_PASSWORD=`)
   > 💡 **Laragon users:** Password is usually empty (leave `DB_PASSWORD=`)

---

### Step 7: Run Database Migrations

```bash
php artisan migrate
```

When prompted "Do you want to run migrations?", type `yes`.

---

### Step 8: (Optional) Seed Demo Data

To add sample data for testing:
```bash
php artisan db:seed
```

---

### Step 9: Build Frontend Assets

**For Production Build:**
```bash
npm run build
```

**For Development (with Hot Reload):**
```bash
npm run dev
```

> ⚠️ Keep this terminal open if using `npm run dev`

---

### Step 10: Start the Laravel Server

Open a **new terminal** and run:
```bash
php artisan serve
```

You'll see:
```
Starting Laravel development server: http://127.0.0.1:8000
```

---

### Step 11: Access the Application

Open your browser and go to:
```
http://localhost:8000
```
OR
```
http://127.0.0.1:8000
```

🎉 **Congratulations! The system is now running locally!**

---

## 👤 Creating Your First Admin Account

### Option 1: Through Tinker (Recommended)

```bash
php artisan tinker
```

Then type:
```php
use App\Models\User;

User::create([
    'first_name' => 'Admin',
    'last_name' => 'User',
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'account_status' => 'approved'
]);
```

Press `Ctrl+C` to exit Tinker.

### Option 2: Through Database Seeder

If a seeder exists, run:
```bash
php artisan db:seed --class=AdminSeeder
```

---

## 🔧 Common Commands Reference

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start the development server |
| `npm run dev` | Start Vite dev server (hot reload) |
| `npm run build` | Build assets for production |
| `php artisan migrate` | Run database migrations |
| `php artisan migrate:fresh` | Reset & re-run all migrations |
| `php artisan cache:clear` | Clear application cache |
| `php artisan config:clear` | Clear config cache |
| `php artisan route:clear` | Clear route cache |
| `php artisan optimize:clear` | Clear all caches |

---

## 🐛 Troubleshooting

### Issue: "Could not find driver" Error
**Solution:**
1. Enable PDO extensions in `php.ini`:
   ```ini
   extension=pdo_mysql
   extension=mysqli
   ```
2. Restart your web server

### Issue: "SQLSTATE[HY000] [2002]" Connection Refused
**Solution:**
1. Ensure MySQL service is running
2. Check `.env` database credentials
3. Verify database exists

### Issue: Vite Manifest Not Found
**Solution:**
```bash
npm run build
# OR keep 'npm run dev' running in another terminal
```

### Issue: Permission Denied (Linux/Mac)
**Solution:**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

### Issue: Class Not Found Error
**Solution:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue: Styles Not Loading
**Solution:**
```bash
npm run build
php artisan view:clear
```

---

## 📧 Email Configuration (Optional)

To enable email functionality locally:

### Using Mailtrap (Recommended for Testing)
1. Create account at [mailtrap.io](https://mailtrap.io)
2. Get your credentials
3. Update `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_username
   MAIL_PASSWORD=your_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

### Using Gmail (For Production)
See `GMAIL_APP_PASSWORD_GUIDE.md` for detailed instructions.

---

## 📁 Project Structure Overview

```
booking-system/
├── app/                    # Application code
│   ├── Http/Controllers/   # Route handlers
│   ├── Models/             # Database models
│   └── Mail/               # Email classes
├── config/                 # Configuration files
├── database/
│   ├── migrations/         # Database structure
│   └── seeders/            # Sample data
├── public/                 # Public assets
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   └── auth.php            # Authentication routes
├── storage/                # Logs, cache, uploads
├── .env                    # Environment config
└── composer.json           # PHP dependencies
```

---

## 🔄 Stopping the Server

To stop the development server:
1. Go to the terminal running `php artisan serve`
2. Press `Ctrl + C`

To stop Vite:
1. Go to the terminal running `npm run dev`
2. Press `Ctrl + C`

---

## 📞 Need Help?

If you encounter issues not covered here:
1. Check the `storage/logs/laravel.log` file for errors
2. Search the error message online
3. Refer to [Laravel Documentation](https://laravel.com/docs)

---

*For full user manual, see: USER_MANUAL.md*
