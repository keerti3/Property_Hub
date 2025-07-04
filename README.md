# 🏡 Property Management Website

A full-stack web platform for seamless property registration, management, and discovery, built with a focus on **buyers**, **sellers**, and **administrators**. This project delivers intuitive dashboards, advanced search features, real-time analytics, and a responsive UI—offering an all-in-one solution for the modern real estate market.

## 📌 Project Summary

We aim to build a versatile, secure, and user-friendly **Property Management Platform** using modern web technologies. The system offers:

- 🏠 Property listing and management tools for sellers  
- 🔍 Advanced search and wishlist features for buyers  
- 📊 Admin dashboards for system monitoring and analytics  

The platform includes **secure login**, **role-based dashboards**, and **responsive UI** with **CSS animations**, making the experience smooth across devices.

---

## 📄 Functional Overview

### 🔐 User Registration
- Secure sign-up for Buyers, Sellers, and Admins
- Password encryption using bcrypt
- Role selection during registration
- Optional email verification

### 🧭 Role-Based Dashboards

| Role        | Features                                                                 |
|-------------|--------------------------------------------------------------------------|
| **Buyer**   | Search with filters, save to wishlist, contact sellers                   |
| **Seller**  | Add/update/delete listings, upload images, view tax estimate             |
| **Admin**   | Manage users, track analytics, generate reports, oversee platform health |

### 🔎 Search & Wishlist
- Filters: Price, Location, Amenities
- Save favorites to personal wishlists
- Real-time feedback and smooth UI interactions

### 📊 Admin Analytics
- Charts and metrics using Chart.js / D3.js
- Track active users, most viewed properties, regional interest
- User account and role management

---

## 💻 Homepage Features

- Clean and responsive landing page
- Register/login options by role
- Smooth navigation to dashboards post-login
- Animated transitions and hover effects

---

## 🎨 CSS Transitions & Effects

- **Fade-In/Fade-Out:** Smooth page transitions
- **Slide Transitions:** Switch dashboards with animations
- **Hover Effects:** Buttons and property cards animate on hover
- **Animated Search Field:** Expands and highlights on focus
- **Chart Animations:** Visual analytics with smooth rendering

---
## Use Case Diagram
![image](https://github.com/user-attachments/assets/a2c96434-3438-405c-a11f-3a5c7cebfb13)

## 📱 User Experience

- 🔄 Responsive design for mobile and desktop  
- 🚀 Fast loading and intuitive UI  
- 🧭 Simple navigation based on user role  
- 🎯 Accessible and clearly labeled actions

---
## 🚀 Getting Started

Follow these steps to set up and run the Property Management Website locally.

---

### 📦 Prerequisites

Make sure the following are installed on your system:

- [Node.js](https://nodejs.org/) (v14 or higher recommended)  
- [MongoDB](https://www.mongodb.com/) or [MySQL](https://www.mysql.com/)  
- Git (optional but recommended)

---

### 🔧 Installation Steps

1. **Clone the repository**

```bash
git clone https://github.com/your-username/property-management-platform.git
cd property-management-platform
```
2. Install backend dependencies
```
npm install
```
3. Set up environment variables
Create a .env file in the root directory and add the following (adjust based on your DB/auth system):
```
PORT=5000
DB_URI=mongodb://localhost:27017/property-db
JWT_SECRET=your_jwt_secret
```
4. Run the development server
```
npm start
```
## 🧪 Testing Strategy

### ✅ Test Cases

1. **Navigation Testing** – Ensure dashboard and page routing across devices  
2. **Usability Testing** – Gather user feedback on functionality and layout  
3. **Refinement Loop** – Iterate improvements based on testing and feedback  

---

## 🛠️ Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap  
- **Backend**: Node.js, Express.js (or preferred stack)  
- **Database**: MongoDB / MySQL (based on implementation)  
- **Charting**: Chart.js / D3.js  
- **Authentication**: bcrypt, role-based auth system

---

## 🔄 Future Enhancements

- Real-time chat between buyers and sellers  
- AI-based property recommendations  
- Integration with external map APIs  
- Progressive Web App (PWA) support  

---


