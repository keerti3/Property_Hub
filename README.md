# 🏡 Property Management Website

An intuitive and responsive web application for efficient property listing, search, and management. This PHP-based system caters to **buyers**, **sellers**, and **administrators** with role-specific dashboards, secure authentication, and smooth user interaction through CSS transitions and animations.

---

## 📌 Project Summary

This Property Management Platform enables:

- 🏠 Sellers to list, update, and manage properties  
- 🔍 Buyers to search, filter, and wishlist properties  
- 📊 Admins to monitor analytics and manage user accounts  

Built using **PHP**, **HTML/CSS**, and **JavaScript**, the platform ensures a lightweight, mobile-friendly experience with smooth animations and secure login flows.

---

## 📄 Functional Overview

### 🔐 User Registration
- Secure role-based registration (Buyer, Seller, Admin)
- Field validation and encrypted passwords
- User role determines dashboard access
- Optionally supports email verification (future scope)

### 🧭 Role-Based Dashboards

| Role        | Key Features                                                             |
|-------------|--------------------------------------------------------------------------|
| **Buyer**   | Property search with filters, save listings to wishlist, contact sellers |
| **Seller**  | Add/edit/delete listings, upload images, manage property details         |
| **Admin**   | User & property oversight, analytics monitoring, report generation       |

### 🔎 Buyer Features
- Advanced search (price, location, size, amenities)
- Wishlist functionality for favorites
- Interactive property cards with hover effects

### 📊 Admin Analytics
- Basic charts with Chart.js
- Track active users, listing trends, and regional activity
- User account management from the dashboard

---

## 💻 Homepage Features

- Clean UI with role-based login and registration
- Secure login redirect to user-specific dashboard
- Responsive design and animated transitions

---

## 🎨 CSS Transitions & Effects

- **Fade-In/Out** – Page transitions between routes  
- **Slide Animations** – Dashboard and modal transitions  
- **Hover Effects** – Interactive cards and buttons  
- **Search Field Animation** – Focus-expand UX  
- **Chart Animation** – Smooth analytics visuals

---

## 📱 User Experience

- 📲 Mobile-first responsive layout  
- 🧭 Simple and clear role-based navigation  
- 🧩 Dynamic UI components powered by CSS3  
- 🧪 Tested for usability and visual consistency  

---

## 🚀 How to Run Locally

### 🛠️ Prerequisites

- PHP 7.x or later  
- A local server (XAMPP, MAMP, or WAMP)  
- A browser (Chrome, Firefox, Safari)

### 🔧 Setup Steps

1. **Clone or Download the Project**

```bash
git clone https://github.com/your-username/property-management-php.git
```
2. **Move Folder to Server Root**
XAMPP/MAMP: Move folder into /htdocs/  
WAMP: Move into /www/

3. Start Apache Server
Open XAMPP/MAMP, start Apache
Visit in browser:
```
http://localhost/property-management-php
```
4. (Optional) Setup MySQL
Import the included .sql file into phpMyAdmin  
Update DB config in db.php

## 🛠️ Tech Stack
Frontend: HTML5, CSS3, JavaScript  
Backend: PHP  
Styling & UX: CSS transitions, animations, hover effects  
Database: MySQL (optional, configurable via db.php)  
Analytics: Chart.js

## 🔮 Future Enhancements
✉️ Email verification during sign-up  
💬 Real-time chat between buyers and sellers  
📍 Google Maps API for property location  
📱 Convert to Progressive Web App (PWA)



