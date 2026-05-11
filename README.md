# Real_Estate_Website
# Dream Properties - Real Estate Website

Dream Properties is a modern real estate web application built using PHP, MySQL, HTML, CSS, and JavaScript.  
The platform allows users to browse property listings while providing an admin dashboard for managing properties and customer enquiries.

---

# Features

## User Side

- View property listings
- Property details page
- Responsive modern UI
- Contact / enquiry system
- About page
- Navigation system

---

## Admin Panel

- Secure admin authentication
- Admin dashboard
- Add property
- Edit property
- Delete property
- View customer enquiries
- Session-based authentication
- Secure password hashing
- Image upload system

---

# Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- XAMPP

---

# Project Structure

```text
DreamProperties/
│
├── admin/
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── add-property.php
│   ├── edit-property.php
│   ├── delete-property.php
│   └── enquiries.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── config/
│   └── db.php
│
├── uploads/
│
├── index.php
├── about.php
├── contact.php
├── properties.php
└── README.md
```

---

# Installation Guide

## 1. Clone Repository

```bash
git clone https://github.com/your-username/dream-properties.git
```

---

## 2. Move Project

Move project folder into XAMPP htdocs directory.

Example:

```text
C:\xampp\htdocs\dream-properties
```

---

## 3. Start XAMPP

Start:

- Apache
- MySQL

---

## 4. Create Database

Open:

```text
http://localhost/phpmyadmin
```

Create database:

```sql
CREATE DATABASE dream_properties;
```

---

## 5. Import Tables

Create required tables.

### Admin Table

```sql
CREATE TABLE admin (

    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(100) NOT NULL,

    password VARCHAR(255) NOT NULL

);
```

---

### Properties Table

```sql
CREATE TABLE properties (

    id INT AUTO_INCREMENT PRIMARY KEY,

    title VARCHAR(255) NOT NULL,

    price DECIMAL(15,2) NOT NULL,

    location VARCHAR(255) NOT NULL,

    type VARCHAR(100) NOT NULL,

    description TEXT NOT NULL,

    image VARCHAR(255) NOT NULL

);
```

---

### Enquiries Table

```sql
CREATE TABLE enquiries (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    phone VARCHAR(20) NOT NULL,

    message TEXT NOT NULL

);
```

---

# Database Configuration

Edit:

```text
config/db.php
```

Example:

```php
<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "dream_properties"
);

if ($conn->connect_error) {
    die("Database Connection Failed");
}
```

---

# Create Admin Account

Temporary file:

```php
<?php

echo password_hash("admin123", PASSWORD_DEFAULT);

?>
```

Copy generated hash and insert:

```sql
INSERT INTO admin(username, password)
VALUES(
    'admin',
    'PASTE_HASH_HERE'
);
```

Delete temporary hash file after use.

---

# Run Project

Open browser:

```text
http://localhost/dream-properties
```

Admin panel:

```text
http://localhost/dream-properties/admin/login.php
```

---

# Admin Credentials

```text
Username: admin
Password: admin123
```

---

# Security Features

- Prepared statements
- SQL injection protection
- Session authentication
- Password hashing
- File validation
- Secure image upload
- Session destruction on logout

---

# Future Improvements

- Property search
- Property filtering
- Pagination
- Email notifications
- Admin analytics
- Multiple admin roles
- JWT authentication
- REST API
- Property favorites system

---

# Screenshots

Add screenshots here.

Example:

```md
![Home Page](screenshots/home.png)
```

---

# License

This project is for educational and portfolio purposes.

---

# Author

Developed by PC OFFICIAL