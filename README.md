# Authentication System

A modern and secure user authentication system built with PHP and MySQL, featuring a beautiful dashboard interface and comprehensive user management features.

## Features

- **User Authentication**
  - Secure user registration with unique username and email
  - Password hashing using PHP's built-in functions
  - Email verification system
  - "Remember Me" functionality using secure cookies
  - Session management

- **Modern Dashboard**
  - Responsive design with Bootstrap 4
  - Interactive sidebar navigation
  - User profile section with avatar
  - Statistics cards with hover effects
  - Recent activity feed
  - Quick action buttons
  - Beautiful gradient backgrounds
  - Font Awesome icons integration

- **Security Features**
  - Password hashing using `password_hash()`
  - Email verification for new accounts
  - Secure session management
  - Protection against SQL injection
  - XSS prevention
  - CSRF protection

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer (for PHPMailer)
- Web server (Apache/Nginx) or PHP's built-in server
- SMTP server for email verification

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/kipash-prog/php-auth_system
   cd auth_system
   ```

2. **Set up the database:**
   - Create a MySQL database named `auth_system`
   - Import the SQL dump:
     ```bash
     mysql -u root -p auth_system < auth_system.sql
     ```

3. **Configure the database connection:**
   - Open `db.php` and update the database credentials:
     ```php
     $host = 'localhost';
     $db   = 'auth_system';
     $user = 'root'; // Change this if your MySQL username is different
     $pass = '';     // Change this if your MySQL password is not empty
     ```

4. **Install dependencies:**
   ```bash
   composer install
   ```

5. **Configure email settings:**
   - Open `register.php` and `login.php`
   - Update the SMTP settings with your email credentials:
     ```php
     $mail->Host = 'smtp.gmail.com';
     $mail->Username = 'your_email@gmail.com';
     $mail->Password = 'your_app_password'; // Use an App Password for Gmail
     ```

6. **Start the server:**
   ```bash
   php -S localhost:8000
   ```

7. **Access the application:**
   - Open your browser and go to `http://localhost:8000`

## Dashboard Features

The dashboard provides a modern and user-friendly interface with the following features:

- **Sidebar Navigation**
  - User profile with avatar
  - Quick access menu
  - Logout button

- **Main Dashboard**
  - Welcome banner
  - Statistics cards
  - Recent activity feed
  - Quick action buttons

- **Responsive Design**
  - Works on all screen sizes
  - Mobile-friendly interface
  - Smooth animations and transitions

## Usage

1. **Registration:**
   - Visit `/register.php`
   - Fill in your details
   - Verify your email

2. **Login:**
   - Visit `/login.php`
   - Enter credentials
   - Use "Remember Me" if desired

3. **Dashboard:**
   - View your profile
   - Check recent activity
   - Access quick actions
   - Navigate through sidebar menu

4. **Logout:**
   - Click the logout button in the sidebar
   - Session will be destroyed

## Security Best Practices

- All passwords are hashed using `password_hash()`
- Email verification required for new accounts
- Session management with secure cookies
- Protection against common web vulnerabilities
- Input validation and sanitization
- Secure password reset functionality

## Contributing

Feel free to contribute to this project by:
1. Forking the repository
2. Creating a new branch
3. Making your changes
4. Submitting a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please:
1. Check the documentation
2. Look for existing issues
3. Create a new issue if needed

---

Made with ❤️ by kidus shimelis

## Interactive Demo

To see the system in action, follow these steps:

1. **Register a new account:**
   - Go to `http://localhost:8000/register.php`.
   - Fill in the registration form with a unique username and email.
   - Submit the form.

2. **Verify your email:**
   - Check your email for a verification link.
   - Click the link to verify your account.

3. **Log in:**
   - Go to `http://localhost:8000/login.php`.
   - Enter your username or email and password.
   - Click "Login".

4. **Explore the dashboard:**
   - After logging in, you will be redirected to the dashboard.
   - You can log out by clicking the "Logout" button.

---

Feel free to explore the code and make any improvements or suggestions! 
