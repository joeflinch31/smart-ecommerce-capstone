# PROJECT PROPOSAL: Smart E-Commerce Web Application

## 1. Project Overview
This project is a fully functional e-commerce web application that allows users to browse products, manage a shopping cart, and place orders. The system includes user authentication, product catalog, and order management. The design is responsive and works on desktop, tablet, and mobile devices.

## 2. Technologies Selected

| Layer | Technology | Purpose |
|-------|------------|---------|
| Frontend | HTML5, CSS3, JavaScript | Structure, styling, interactivity |
| Backend | PHP 8.2 | Server-side logic, form processing |
| Database | MySQL (MariaDB) | Data storage (users, products, orders) |
| Server | XAMPP (Apache) | Local development environment |
| Design | Figma | Wireframes and GUI mockups |
| Version Control | Git & GitHub | Code backup and tracking |

## 3. Features to Implement

### User Features
- User registration and login
- Browse product catalog
- Search and filter products
- Add/remove items from shopping cart
- Place orders
- View order history

### Admin Features
- Add, edit, delete products
- View and manage orders
- Manage user accounts

## 4. Project Workflow Plan

| Week | Tasks | Status |
|------|-------|--------|
| Week 1 | Environment setup (XAMPP, PHP, MySQL, database connection) | ✅ COMPLETE |
| Week 2 | Design phase (Figma wireframes, HTML/CSS frontend) | ✅ COMPLETE |
| Week 3 | Backend development (PHP forms, user registration) | ⏳ PENDING |
| Week 4 | Authentication system (login, sessions, password hashing) | ⏳ PENDING |
| Week 5 | CRUD operations (product management, shopping cart) | ⏳ PENDING |
| Week 6 | Testing, deployment, and documentation | ⏳ PENDING |

## 5. Folder Structure Planning
smart-ecommerce-capstone/
│
├── week1/
│ ├── index.php # Hello World test
│ ├── test_db.php # Database connection test
│ └── week1db.sql # Database export
│
├── week2/
│ ├── css/
│ │ └── style.css # Main stylesheet
│ ├── images/ # Product images
│ ├── index.html # Homepage (product catalog)
│ ├── login.html # Login page
│ ├── register.html # Registration page
│ ├── cart.html # Shopping cart page
│ └── proposal.md # This file
│
├── screenshots/ # Weekly evidence screenshots
└── README.md # Project documentation

text

## 6. Database Schema (Planned)

```sql
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    price DECIMAL(10,2),
    description TEXT,
    image_url VARCHAR(255)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
7. Evidence Submitted for Week 2
Evidence	File Name
Figma wireframes (Homepage)	gui-homepage.png
Figma wireframes (Login)	gui-login.png
Figma wireframes (Register)	gui-register.png
Figma wireframes (Cart)	gui-cart.png
Browser screenshot - Homepage	fig2.1_homepage.png
Browser screenshot - Login	fig2.2_login.png
Browser screenshot - Register	fig2.3_register.png
Browser screenshot - Cart	fig2.4_cart.png
Folder structure	folder-structure.png
8. Conclusion
The project has successfully completed the environment setup (Week 1) and frontend design phase (Week 2). The next phase (Week 3) will implement backend functionality using PHP and MySQL to create a fully dynamic e-commerce system with user registration and login capabilities.

Student Name: Joseph Kinuthia

Course: BIT3208 - Advanced Web Design and Development

Project: Smart E-Commerce Capstone Project

Date: June 3, 2026