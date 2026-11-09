LABORATORY SAMPLE TRACKING SYSTEM - MINI PROJECT WITH CLIENT DASHBOARD

CLIENT FEATURES
- Register and log in.
- A new client account is linked to a client record using its email address.
- Client dashboard shows only samples assigned to that client's record.
- Client can open a sample to see its test results.
- Client cannot open the administrator pages.

ADMIN FEATURES
- Administrator dashboard shows totals.
- Manage clients, samples, and test results.
- When adding an existing client, enter the client's email address. The client should register using the same email to connect the account to that client record.

INSTALLATION - FRESH DATABASE
1. Extract this folder into C:\xampp\htdocs\lab_sample_mini_project\.
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin.
4. Import database.sql. This creates the database and all tables.
5. Check config.php for your MySQL username and password.
6. Open http://localhost/lab_sample_mini_project/register.php to create a client account.
7. Log in at http://localhost/lab_sample_mini_project/login.php.

UPGRADE AN EXISTING DATABASE
1. Back up your database first.
2. In phpMyAdmin, select lab_mini_project, open the SQL tab, and run upgrade_existing_database.sql ONCE.
3. If you have existing client rows, you may add their email addresses in the clients table. Client users should register with the same email address to be linked.
4. Create an admin account by registering normally, then run this SQL in phpMyAdmin, replacing the email:
   UPDATE users SET role = 'admin' WHERE email = 'admin@example.com';
5. Log out and log in again so the admin role is loaded into the session.

IMPORTANT
- The default registration role is client. New users cannot make themselves administrators.
- Only administrator accounts can access clients.php, samples.php, and results.php.
- The client dashboard status is a simple indication: 'Sample Received / Awaiting Testing' if no test result exists, or 'Testing / Results Recorded' if at least one result exists. It is not a formal lab approval or release status.
- Passwords use PHP password_hash/password_verify.
- This is a learning mini-project. Add HTTPS and additional security controls before using real sensitive data.
