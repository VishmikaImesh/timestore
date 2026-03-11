<p align="center">
  <h1 align="center">🛒 TimeStore</h1>
  <p align="center">
    A custom PHP e-commerce platform built with MVC architecture, a front controller, and a custom routing system.
  </p>
</p>

<p align="center">

![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![Architecture](https://img.shields.io/badge/Architecture-MVC-green)
![Routing](https://img.shields.io/badge/Routing-Custom-orange)
![Payments](https://img.shields.io/badge/Payments-PayHere-purple)
![Database](https://img.shields.io/badge/Database-MySQL-red)
![Status](https://img.shields.io/badge/Status-Active-brightgreen)

</p>

---

# 🌐 Live Demo

Demo URL  https://timestore.imeshvishmika.me


---

# 📖 Project Overview

**TimeStore** is a full-stack **e-commerce web application** built using **PHP** with a custom **MVC architecture**, **Front Controller pattern**, and a **custom routing system**.

The platform allows users to browse products, place orders, and manage their purchase history while providing administrators with tools to manage products, users, orders, and revenue analytics.

The goal of this project is to demonstrate **backend system design, architecture principles, routing systems, middleware security, and real-world application workflows**.

---

# ⭐ Features

## 👤 User Features

- Browse product catalog
- Search and filter products
- Add items to cart
- Checkout with **PayHere sandbox payment gateway**
- View order history
- View order details
- Messaging system with administrators

---

## 🛠 Admin Features

- Add products
- Update products
- Delete products
- Search and filter products
- Manage users
- View sales history
- View revenue analytics
- Manage orders
- Communicate with users via messaging

---

# 🧰 Technology Stack

| Layer | Technology |
|------|------|
| Backend | PHP |
| Architecture | MVC |
| Router | Custom Router |
| Security | CSRF + Role Based Auth |
| Database | MySQL |
| Reverse Proxy | Nginx |
| Web Server | Apache |
| Runtime | PHP-FPM |
| Payment Gateway | PayHere Sandbox |

---

# 🏗 System Architecture

```mermaid
flowchart TD

A[Client Browser]:::client --> B[Nginx Reverse Proxy]:::infra
B --> C[Apache Web Server]:::infra
C --> D[PHP-FPM]:::infra
D --> E[index.php Front Controller]:::app

E --> F[Router]:::app
F --> G[Middleware Layer]:::security
G --> H[Controllers]:::app
H --> I[Services]:::app
I --> J[Repositories / Models]:::app
J --> K[(MySQL Database)]:::db

classDef client fill:#4CAF50,color:#fff
classDef infra fill:#FF9800,color:#fff
classDef app fill:#2196F3,color:#fff
classDef security fill:#F44336,color:#fff
classDef db fill:#9C27B0,color:#fff
```
---

# 🔄 Request Lifecycle

```mermaid
sequenceDiagram

participant User
participant Nginx
participant Apache
participant Router
participant Middleware
participant Controller
participant Service
participant Database

User->>Nginx: HTTP Request
Nginx->>Apache: Forward request
Apache->>Router: index.php

Router->>Middleware: Authorization check
Middleware->>Router: Access granted

Router->>Controller: Dispatch controller
Controller->>Service: Business logic
Service->>Database: Query data
Database-->>Service: Results

Service-->>Controller: Response
Controller-->>User: JSON / HTML response
```
---

# 🗄 Database Design

```mermaid
erDiagram

USERS {
int id
string name
string email
string password
string role
}

PRODUCTS {
int id
string name
string description
float price
int stock
}

ORDERS {
int id
int user_id
string status
float total
}

ORDER_ITEMS {
int id
int order_id
int product_id
int quantity
}

MESSAGES {
int id
int sender_id
int receiver_id
string message
datetime created_at
}

USERS ||--o{ ORDERS : places
ORDERS ||--o{ ORDER_ITEMS : contains
PRODUCTS ||--o{ ORDER_ITEMS : includes
USERS ||--o{ MESSAGES : sends
USERS ||--o{ MESSAGES : receives
```

---

# 🔐 Security

TimeStore includes several security mechanisms.

Authentication

Session-based authentication.

Role-Based Authorization

Routes can define required roles.

Example:

"allows" => ["admin"]
CSRF Protection
hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
Middleware Layer

Security checks are performed before controller execution.

---

# 📁 Project Structure

```text
timestore/
├── public/
│   ├── index.php
│   └── .htaccess
├── app/
│   ├── controllers/
│   │   ├── ProductController.php
│   │   ├── OrderController.php
│   │   └── UserController.php
│   ├── services/
│   │   ├── ProductService.php
│   │   └── OrderService.php
│   ├── models/
│   │   ├── ProductModel.php
│   │   ├── OrderModel.php
│   │   └── UserModel.php
│   ├── middleware/
│   │   ├── AuthMiddleware.php
│   │   └── CsrfMiddleware.php
│   ├── router/
│   │   └── Router.php
│   └── views/
├── config/
├── database/
└── docs/
    ├── images/
    └── banner.png
```
