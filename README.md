# My Gym — PHP Gym Management Website

Simple PHP-based gym website and admin panel for membership, products (protein), equipment, and orders.

**Features**
- **Public pages:** home, about, services, equipment, protein, membership, contact
- **User area:** registration, login, profile, cart, checkout, order history
- **Admin panel:** manage equipment, products, memberships, orders, payments, trainers
- **Email support:** PHPMailer included for notifications

**Tech stack**
- PHP (plain PHP files)
- MySQL (SQL files included)
- Composer (autoload present)

**Quick Start**
1. Clone the repository:

   git clone <repo-url>

2. Install dependencies (if you use Composer):

   composer install

3. Create a MySQL database and import one of the provided SQL dumps: `yuvraj.sql` or `krish.sql`.

4. Update database credentials in the connection files:
- [user/masterpage/db_connect.php](user/masterpage/db_connect.php)
- [adminPanel/db_connection.php](adminPanel/db_connection.php)

5. Serve the site with your web server (Apache/IIS) or PHP built-in server:

   php -S localhost:8000

6. Open the site in your browser:
- Frontend: [index.php](index.php)
- Admin: [adminPanel/dashboard.php](adminPanel/dashboard.php)

**Notes**
- PHPMailer is included under `phpmailer/` — configure SMTP settings if you want email features.
- Static assets (CSS/images) are under `css/`, `image/`, and `user/css/`.

**Contributing**
Contributions are welcome. Open an issue or submit a pull request with a clear description of changes.

**License**
This project does not include a license file. Add a license (for example MIT) if you plan to publish it.
