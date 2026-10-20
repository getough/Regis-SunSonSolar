# Sun Son Solar Company — Completed Frontend Demo

**Team Name:** Team S

## Project Overview

Sun Son Solar is a solar energy company based in Pasig City, founded by Katherine “Kat” Sinagaraw and Santino “Sonny” Sinagaraw.

This project continues the supplied PHP, HTML, CSS, and JavaScript application. It retains the original homepage, images, product cards, slideshow, login layout, and registration layout while adding pages and scripts for the requested demonstration workflows.

The project is intended as a demonstration application and is **not a production-ready multi-user system**.

## How to Run the Project

### Option 1: Run Directly from VS Code (Windows + XAMPP PHP)

1. Extract the ZIP file.
2. Open the `Sun_Son_Solar_VSCode_Ready` folder in VS Code using **File → Open Folder**. Make sure this is the folder containing `index.php`.
3. Make sure XAMPP is installed at `C:\xampp`. The project uses the PHP executable from that installation.
4. Press **F5** and choose **Run Sun Son Solar Website**, or press **Ctrl + Shift + B** and select the **Run Sun Son Solar** task.
5. The task starts PHP's local development server and opens the homepage in your browser. It selects an available port between 8000 and 8020.
6. After editing PHP, HTML, CSS, or JavaScript files, save your changes with **Ctrl + S** and refresh the browser using **Ctrl + F5**.
7. When finished, use **Terminal → Run Task → Stop Sun Son Solar** to stop the server.

You can also run the project by double-clicking `RUN_WEBSITE.bat` in the project folder.

If VS Code displays multiple tasks, select **Run Sun Son Solar**. Do not use **Run Code** or **Live Server** to execute PHP files. PHP needs a PHP-capable server.

This method does not require starting Apache or MySQL in XAMPP.

### Option 2: Run Using XAMPP Apache

1. Copy the `Sun_Son_Solar_VSCode_Ready` folder into `C:\xampp\htdocs\`.
2. Start Apache in the XAMPP Control Panel. MySQL is not required for this frontend demo.
3. Open the following URL in your browser:

   `http://localhost/Sun_Son_Solar_VSCode_Ready/`

The root `index.php` file opens the homepage.

Avoid placing the project inside an additional nested folder when extracting the ZIP, as this may prevent the homepage from opening at the expected URL.

## Important Technical Information

The demo requires JavaScript to be enabled in the browser. The sample SHA-256 login flow requires `localhost` as a secure context.

The application uses:

- **localStorage** for sample data that persists in the same browser profile.
- **sessionStorage** for the currently active demonstration account.
- **PHP, HTML, CSS, and JavaScript** for the website and demonstration workflows.

The active demo flows do not use a database schema, SQL queries, a hosted API, a payment provider, or backend authentication.

Data is stored locally in the browser and does not synchronize across different computers or browser profiles. This is a demonstration application, not a production-ready multi-user system.

**Do not enter genuine personal information or real passwords into this prototype.**

## Sample Login Accounts

The following accounts are provided for demonstration purposes only.

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `DemoAdmin1!` |
| HR | `hr` | `DemoHR123!` |
| Dispatcher | `dispatcher` | `DemoDispatch1!` |
| Technician | `technician` | `DemoTech123!` |
| Customer | `customer` | `DemoCustomer1!` |
| Accounting | `accounting` | `DemoAccounting1!` |
| Customer Service | `support` | `DemoSupport1!` |
| Marketing | `marketing` | `DemoMarketing1!` |
| Sales | `sales` | `DemoSales123!` |
| IT | `itstaff` | `DemoIT123!` |

These are local demonstration accounts, not actual staff credentials.

Customers can register through `register.php`. HR and Admin can create employee accounts through the dashboard. Employee self-registration is disabled.

## Functional Demonstration Modules

### Public Website

- Original homepage and images retained.
- Product and service catalog.
- Company and solar energy benefits section.
- FAQ section.
- Contact area.
- Testimonial placeholder without fabricated testimonials.
- Existing slideshow retained with deferred loading.

### Customer Module

- Customer registration and login.
- Profile and password management.
- New booking requests.
- Booking cancellation when permitted.
- Service history.
- Notifications.
- Sample payments and receipts.
- Support tickets.

### Admin Module

- Admin dashboard.
- User management.
- Employee account creation and deactivation.
- Booking management and technician assignment.
- Product and service management.
- Pricing and availability management.
- Payment records and support management.
- Announcements and system settings.
- Booking charts and CSV exports.
- Activity logs and demonstration-data reset.

### HR Module

- Employee account creation and management.
- Department announcements.

### Dispatcher Module

- Booking confirmation.
- Assignment of active technicians.
- Schedule management.

### Technician Module

- Assigned job viewing.
- GPS-based site check-in and check-out.
- Final service reports and issue notes.
- Notifications and announcements.

### Other Employee Roles

The application provides role-specific views and permitted editing for Accounting, IT, Marketing, Sales, and Customer Service.

## How to Test the Complete Job Workflow

1. **Customer:** Log in using the sample customer account and submit a booking.
2. **Dispatcher:** Log out, sign in as the dispatcher, open **Manage Bookings**, select **Manage**, change the booking status to **Assigned**, and select a technician.
3. **Technician:** Log out, sign in as the technician, open **My Jobs**, and click **Check In**. Grant browser location permission when requested. When the work is finished, click **Check Out**, then **File Report**, and submit the service report.
4. **Customer:** Log back in as the customer. Verify that the booking is marked **Completed** and appears in Service History. Check whether status-change notifications are displayed.
5. **Admin or Accounting:** Log in as Admin or Accounting and record a sample payment. The customer can then view and print a demonstration receipt.

### GPS and Location Requirements

GPS check-in requires `http://localhost` or HTTPS, browser location permission, and a compatible device and browser.

If location access is denied or unavailable, the application reports that check-in did not occur. It does not fabricate GPS coordinates.

## Database and Authentication Notes

The original `db.php` file is retained and left unchanged for future database development.

The `process_login.php` and `process_register.php` files are safe redirects in this demo. The active registration and login workflows use `demo-auth.js` instead of the previous SQL-processing scripts.

When database integration is implemented, authentication, user permissions, and all database writes must be handled by a secured server-side backend.

The current demonstration workflows use browser storage rather than a shared database, so information is limited to the browser profile in which it was created.

## Original Files Retained

The following original files were retained:

- `index.php`
- `register.php`
- `login.php`
- `style.css`
- `register_style.css`
- `login_style.css`
- `script.js`
- All original images

The original login and registration pages received additional script references, while the homepage received appended content sections and deferred loading for the existing slideshow.

## Additional Files

The additional demonstration functionality is implemented in the following files:

- `demo-core.js`
- `demo-auth.js`
- `dashboard.php`
- `dashboard.js`
- `dashboard_style.css`
- `public.js`
- `public-extra.css`

## Limitations

This project is intended for demonstration and testing. It does not provide production-grade server-side authentication, persistent shared database storage, a hosted API, or integrated payment processing.

A secure server-side backend and database integration are required before the application can be used as a real company system with multiple users.
