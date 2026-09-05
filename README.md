# Corporate Employee Portal

A web-based **internal employee management portal** developed using PHP, MySQL, HTML, CSS, and JavaScript.

The system is designed to provide a centralized platform for managing employee information, departments, positions, certificates, and related documents. It also provides advanced search, filtering, and reporting capabilities.

## Features

* 👥 Employee management
* 🔎 Advanced employee search
* 🏢 Department management
* 💼 Position-based filtering
* 📜 Certificate management
* 📄 Employee document management
* 🆔 Personnel number search
* 👤 Personal information management
* 📊 Employee information and reporting
* 📥 Excel export
* 📄 PDF report generation
* 🔐 User authentication
* 🛡️ Role-based access control
* 🗄️ MySQL database integration

## Employee Search

The portal provides multiple filtering options for finding employees.

Users can search by:

* First name
* Last name
* Personnel number
* Gender
* Department
* Position
* Age range
* Certificate

Certificate-based searches can identify employees who have specific certifications, such as **ISO 13485**.

## Employee Information

The system stores and manages information such as:

* Personnel number
* First name
* Last name
* Gender
* Marital status
* Date of birth
* Phone number
* Email address
* Department
* Position
* Profile photo

## Certificate Management

Employees can be associated with their professional certificates.

Certificate records can include:

* Certificate name
* Certificate acquisition date
* Certificate expiration date
* Certificate document

The relationship between employees and certificates is managed through a dedicated employee-certificate structure.

## Document Management

The portal supports storing and managing employee-related documents.

Documents can be associated with individual employees and accessed through the internal system.

## Reporting and Export

Search results can be used to generate reports and export employee information.

Supported formats include:

* **Excel**
* **PDF**

This allows filtered employee data to be used for administrative and reporting purposes.

## Authentication and Authorization

The system includes user authentication and role-based access control.

Users are associated with roles and departments, allowing access to be managed according to their responsibilities.

The system includes structures for:

* Users
* Roles
* Permissions
* Departments

## Database Structure

The project uses **MySQL/MariaDB** as its relational database.

Main tables include:

```text id="p0g5v1"
users
roles
departments
employees
certificates
employee_certificates
documents
```

### Main Relationships

```text id="7e2v5k"
Departments
     │
     └── Employees
             │
             ├── Employee Certificates
             │       │
             │       └── Certificates
             │
             └── Documents
```

## Technologies

* **PHP**
* **MySQL / MariaDB**
* **HTML5**
* **CSS3**
* **JavaScript**
* **PDO**
* **XAMPP**
* **phpMyAdmin**

## Project Structure

```text id="2f6p8n"
KurumsalPortal/
│
├── config/
│   └── database.php
│
├── pages/
│   ├── dashboard.php
│   ├── search.php
│   ├── employee-detail.php
│   └── ...
│
├── uploads/
│
├── index.php
├── login.php
├── README.md
└── ...
```

## Installation

### 1. Install XAMPP

Install and start:

* Apache
* MySQL

### 2. Clone the Repository

Place the project inside the XAMPP `htdocs` directory:

```text id="e2j0qs"
C:\xampp\htdocs\KurumsalPortal
```

### 3. Create the Database

Open **phpMyAdmin** and create a database named:

```text id="n6zv9h"
kurumsal_portal
```

Import the provided SQL database file.

### 4. Configure the Database

Update the database connection settings in:

```text id="t2q6dd"
config/database.php
```

Configure the database host, username, password, and database name according to your local environment.

### 5. Run the Application

Start Apache and MySQL through XAMPP.

Then open the application in your browser:

```text
http://localhost/KurumsalPortal/
```

## Security

The application uses:

* Session-based authentication
* Password hashing
* PDO prepared statements
* Role-based authorization
* Database relationships and foreign keys

Sensitive configuration information such as database credentials should not be committed to the repository.

## Project Purpose

The purpose of this project is to develop a centralized internal platform for managing and searching employee information.

The project provides practical experience with:

* Backend web development
* Relational database design
* CRUD operations
* SQL queries and joins
* Authentication
* Authorization
* Search and filtering systems
* File management
* Excel and PDF reporting
* PHP and MySQL integration

## Project Status

**Completed**

## Future Improvements

* Improved dashboard analytics
* More advanced permission management
* Employee activity history
* Improved document management
* Advanced reporting and filtering
* Responsive mobile interface
* Notification system
* Improved security and audit logging

## Author

**Sude Sena Aydın**
