# 🍽️ Restaurant Management System

A complete **Restaurant Management System** that provides a powerful **Admin Dashboard** for managing restaurant operations and a **User Website** that allows customers to order food online, pay electronically, and track their orders in real time.

The system is built using **Laravel** and follows **clean architecture principles and design patterns** to ensure scalability, performance, and maintainability.

---

## 🚀 Project Overview

The system consists of two main parts:

### 1️⃣ Admin Dashboard
Used to manage:
- Users & permissions
- Orders & order statuses
- Categories & meals
- Reports & statistics
- Cashier system

### 2️⃣ User Website
Used by customers to:
- Browse the food menu
- Place orders online
- Pay online
- Track order status
- Login easily using social accounts

---

## 👥 Roles & Permissions (RBAC)

The system uses **Role-Based Access Control (RBAC)** with a clear separation of responsibilities:

### 🔑 Owner
- Create and manage roles & permissions
- Add Admin / Data Entry / Cashier accounts
- Full system control

### 🛠️ Admin
- Receive and manage online orders
- Update order statuses (Pending / Preparing / Completed / Cancelled)
- Create and manage admin users
- View daily reports
- Access full system statistics

### 📝 Data Entry
- CRUD operations for Categories
- CRUD operations for Meals
- Monitor website orders
- Generate daily reports
- View personal statistics

### 💰 Cashier
- Access cashier screen
- Create in-restaurant orders
- Track completed orders
- Search through handled orders
- Print order invoices

---

## 🌐 Website Features (Customer Side)

- View food menu
- Place online orders
- Online payment support
- Real-time order tracking
- Social login using:
  - Google
  - GitHub
- User receive mail for confirm order created
- Simple and user-friendly experience
---

## 💳 Online Payment Integration

Integrated multiple payment gateways using clean architecture principles:

- **Stripe**
- **Paymob**

Applied design patterns:
- Factory Pattern
- Strategy Pattern  

This allows easy integration of additional payment gateways without modifying existing logic.

---

## 🔔 Notification System

- When a new order is created:
  - An instant notification is sent to the Admin
- Notification channels:
  - Database Notifications
  - Real-time Notifications using **Pusher**
- Implemented using **Broadcasting**

---

## ⚙️ Background Processing & Automation

To improve performance and system reliability:

- **Events & Listeners**
  - Order creation triggers an Event
  - Listeners handle notifications and background logic
- **Queues & Jobs**
  - Heavy tasks are processed asynchronously
- **Task Scheduling**
  - Automatically deletes unpaid orders after a specific time period

---

## 🧪 API Testing

- All RESTful APIs are tested using **PHPUnit**
- Feature and unit tests are implemented to ensure:
  - Authorization & permissions validation
  - Correct API responses
  - Business logic accuracy
- Helps guarantee system stability and prevent regressions

---

## 🧠 Architecture & Design Patterns

The project follows a clean and scalable architecture:

- Repository Pattern
- Service Layer
- Factory Pattern
- Strategy Pattern

This results in:
- Clean and readable code
- Easy maintenance
- High testability
- Scalable structure

---

## 🔌 API-Driven Dashboard

- The dashboard is fully powered by **RESTful APIs**
- Frontend communication via:
  - AJAX
  - jQuery
- Clear separation between backend and frontend logic

---

## 🛠️ Technologies Used

- PHP
- Laravel Framework
- RESTful API
- MySQL
- Stripe & Paymob
- Pusher
- AJAX & jQuery
- PHPUnit Testing
- Queues & Jobs
- Events & Listeners
- Task Scheduler

---

## 📌 Key Features Summary

- Advanced role & permission system
- Online ordering & cashier system
- Secure online payments
- Real-time notifications
- Mail Uisng MailGun
- Reports & statistics
- Background job processing
- Fully tested RESTful APIs
- Clean and scalable architecture

---

## 📬 Contact

For any questions or suggestions:

**Mahmoud Abdelrahim**  
Backend Developer (Laravel)
Email : mahmoudabdelrahim189@gmail.com
Phone : 01201955377
