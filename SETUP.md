# Sun Son Solar: CodeIgniter 4 port (registration, login, staff accounts)

Tested on paper only: it was written without PHP/MySQL available, so run the steps below
and report any error message you see. PHP 8.2.4 (your XAMPP) meets CI 4.7's PHP 8.2+ requirement.

## 1. Copy these files into your `Regis-SunSonSolar-codeigniter` project
Merge the folders (`app/`, `public/`, `writable/`, plus `.env.example` and `.gitignore`).
Overwrite when asked: `app/Config/Filters.php`, `app/Config/Routes.php`,
`app/Controllers/BaseController.php`, `app/Controllers/Home.php`, `public/css/registration.css`.

## 2. Delete the old files (they conflict or are obsolete)
- `app/Controllers/registration.php`   (replaced by `Register.php`)
- `app/Views/registration.php`         (replaced by `register.php`)
- `app/Views/welcome_message.php`      (optional)
- `public/js/registration.js`          (replaced by `auth.js`)
- repo root: `css/`, `js/`, `index.html`, `db.sql`   (the migrations replace `db.sql`)

## 3. Database
1. Start **MySQL** in the XAMPP Control Panel.
2. Open http://localhost/phpmyadmin and create an empty database named `team3_db`
   (collation `utf8mb4_general_ci`). If you already ran the old `db.sql`, drop that database
   first: the table design changed.
3. Copy `.env.example` to `.env` and adjust the database user/password if yours differ.

## 4. Create the tables and the first admins
Open a terminal in the project folder (the one containing `spark`):

    C:\xampp\php\php.exe spark migrate
    C:\xampp\php\php.exe spark db:seed AdminSeeder

The seeder prints a temporary password for Kat (`KittyKat16`) and Sol (`admin`). Give them
out privately; each person must change theirs at first login.

## 5. Run it

    C:\xampp\php\php.exe spark serve

Open http://localhost:8080 . Try: /register, /login, then log in as an admin and open
User management to create an HR/dispatcher/technician account.

## What it does
- Customers self-register (role fixed to "customer" on the server; employee sign-up is closed).
- HR/Admin create employee accounts; HR cannot create admin or HR accounts.
- Passwords are hashed; temporary passwords force a change at first login.
- 5 wrong passwords lock the account for 15 minutes. Role and active status are re-checked on every request.
- CSRF protection and security headers are on globally.
- Audit log records logins, lockouts, password changes, account creation and deactivation.

## Still to confirm / build
- Sol's email, username, full name ("Sol Solis" vs "Sol Sun Solis"); Kat's and Sol's gender; office address.
- Apollo is both head technician and dispatcher; this design gives each user ONE role. Decide which, or add multi-role.
- `privacy.php` is a draft placeholder and needs company/legal approval. Put the logo at `public/images/logo.png`.
- Next: homepage, bookings, technician check-in (table already exists), notifications, reports.
