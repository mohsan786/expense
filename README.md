# The Ledger — PHP & MySQL Deployment Guide

A web app for a two-partner leather shoe business, built with **PHP + MySQL**, featuring real-time cloud sync, PIN security, partner ratio profit split, employee piece-rate payroll, vendor credit tracking, and offline data backup/restore.

---

## 🚀 How to Run Locally

### Using PHP Built-in Web Server & MySQL (XAMPP / WAMP / MAMP)
1. Make sure **MySQL Server** (e.g. XAMPP / MariaDB / Local MySQL) is running on `localhost:3306`.
2. Start the PHP server in this folder:
   ```bash
   php -S localhost:8000
   ```
3. Open `http://localhost:8000` in your web browser.
4. Enter the default passcode: **`1234`**.

---

## 🌐 How to Deploy Live to Web Hosting (cPanel / Hostinger / Namecheap / Bluehost)

1. **Create a MySQL Database**:
   - Log into your hosting cPanel or control panel.
   - Go to **MySQL Databases** and create a new database (e.g., `myuser_ledger`).
   - Create a MySQL user and password, and grant **ALL PRIVILEGES** to that user on the database.

2. **Configure Database Credentials**:
   - Open `config.php` and update the constants:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'myuser_ledger');
     define('DB_USER', 'myuser_dbuser');
     define('DB_PASS', 'your_secure_password');
     ```

3. **Upload Files**:
   - Upload all files (`index.php`, `api.php`, `db.php`, `config.php`, `.htaccess`) to your server's `public_html` directory or a subdirectory (e.g. `public_html/ledger`).

4. **Access Your Live App**:
   - Visit `https://your-domain.com/ledger`.
   - Enter passcode **`1234`** to log in.
   - Go to the **Settings** tab to change your passcode to something secure!

---

## 🔐 Security Features
- **Passcode Authentication**: Built-in session security requiring a PIN/Passcode to view financial numbers.
- **SQL Injection Prevention**: Built with PHP PDO parameter binding.
- **Directory Protection**: `.htaccess` rules prevent directory listing and unauthorized access to hidden files.
