# Student Management System

A simple web-based system to manage student records and track attendance.

## Default Credentials and Formats

### Admin Account
- Username: admin
- Password: password

### Student Account (Example Format)
- Username: 2023/ict/100
- Password: ict@100
- Note: Other students can login using their credentials in this format.

### Lecturer Account (Example Format)
- Username: lec_perera
- Password: perera@123
- Note: Other lecturers can login using their credentials in this format.

## File Structure
- database.sql: Database tables setup
- db.php: Database connection settings
- login.php and logout.php: User login and logout
- submit_otp.php: OTP verification system
- admin_add_student.php: Page to add new students
- mark_attendance.php: Page to mark daily attendance
- profile.php: User profile view

## How to Setup
1. Move the project folder into xampp/htdocs/
2. Open phpMyAdmin at http://localhost/phpmyadmin/
3. Create a new database and import the database.sql file
4. Update your database connection details in db.php
5. Open your browser and go to http://localhost/Student Management System/




