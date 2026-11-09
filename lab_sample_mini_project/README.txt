LABORATORY SAMPLE TRACKING SYSTEM - MINI PROJECT

FILES
- register.php: creates a user account and saves it in the users table.
- login.php: checks the user's email and password. Successful logins are saved in login_logs.
- logout.php: ends the user's session.
- auth.php: protects the application pages from visitors who are not logged in.
- config.php: database connection settings.
- database.sql: creates the database and all required tables.
- index.php: dashboard.
- clients.php: client records.
- samples.php: sample records.
- results.php: test result records.

INSTALLATION
1. Extract this folder into C:\xampp\htdocs\lab_sample_mini_project\.
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin.
4. Import database.sql. If you already imported the old database, import the updated SQL too; CREATE TABLE IF NOT EXISTS adds the new users and login_logs tables.
5. Check config.php. The default XAMPP username is root and password is blank.
6. Open http://localhost/lab_sample_mini_project/register.php and create an account.
7. Log in at http://localhost/lab_sample_mini_project/login.php.

NOTE
- Passwords are stored as secure password hashes.
- Only successful logins are recorded in login_logs.
- The log stores the account, login timestamp, IP address, and browser user-agent.
- This is a beginner mini-project. Add HTTPS and further security controls before using it with real sensitive data.
