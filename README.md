# 🗳️ Secure Modern E-Voting System

[![Banner](https://images.unsplash.com/photo-1540910419892-4a36d2c3266c?auto=format&fit=crop&q=80&w=1200&h=400)]()

<div align="center">
  <a href="https://evoting.page.gd">
    <img src="https://img.shields.io/badge/Live%20Demo-Visit%20Site-blue?style=for-the-badge&logo=googlechrome&logoColor=white" alt="Live Demo">
  </a>
</div>

## 🌟 Overview
Experience a new standard in digital democracy. This **E-Voting System** is a robust, secure, and visually stunning web application designed to handle modern election requirements with ease. Built with **PHP, MySQL, and Vanilla CSS/JS**, it features a unique **Glassmorphsim UI** that provides a premium, high-tech user experience.

---
<center><img src="banner.png"></center>
## 🚀 Key Features

### 👤 User Portal
- **Modern Landing Page**: Dynamic, engaging intro to the platform.
- **Secure Authentication**: Encrypted registration and secure login system.
- **Personalized Dashboard**: Track your voting stats and explore active elections.
- **Glassmorphic Voting Interface**: A beautiful, intuitive way to select candidates and cast votes.
- **Real-time Results**: Live vote tallying with visual progress bars.

### 🛡️ Admin Power Panel
- **Real-time Analytics**: Monitor voter turnout and election statuses.
- **Election Management**: Full control to Create, Update, and Delete election cycles.
- **Candidate Management**: Easily manage candidates and their affiliations.
- **Voter Verification**: Approve or block voter accounts for a secure environment.
- **Detailed Reporting**: Comprehensive results tracking with distinct winner identification.

---

## 🎨 Design Aesthetics
- **Unique Glassmorphic Theme**: Sophisticated use of background blurs and transparency.
- **Responsive Layout**: Optimized for Desktop, Tablet, and Mobile.
- **Dynamic Backgrounds**: Uses high-resolution online URLs for a fresh, modern look every time.
- **Interactive Animations**: Micro-animations and scroll effects for a polished feel.

---

## 🛠️ Technology Stack
- **Frontend**: HTML5, Vanilla CSS3 (Custom Design System), JavaScript (ES6+).
- **Backend**: PHP (Object-oriented style with PDO).
- **Database**: MySQL (Optimized Schema).
- **Icons**: Font Awesome 6.

---

## 🔧 Installation & Setup

1. **Environment Setup**: Ensure you have **XAMPP** (or any PHP/MySQL server) installed and running.
2. **Database Initialization**:
   - Access `http://localhost/evoting/setup.php` in your browser.
   - This script will automatically create the `evoting_db` and all necessary tables.
3. **Account Setup**:
   - Register a new account at `http://localhost/evoting/register.php`.
   - To become an Admin, run `http://localhost/evoting/create_admin.php` or manually change the `role` to `admin` in your MySQL database.
4. **Usage**: Log in and start managing or participating in digital elections!

---

## 📸 Project Structure
```bash
/evoting
├── admin/               # Admin Panel Modules
│   ├── includes/        # Admin-specific templates
│   └── (modules).php    # Dashboard, Elections, Voters, etc.
├── assets/
│   ├── css/ style.css   # Main Theme & Layout
│   └── js/ main.js      # Animations & Logic
├── includes/            # Core Templates (Header, Footer, Config)
├── index.php            # Landing Page
├── dashboard.php        # User Hub
└── (logic).php         # Voting, Auth, Results scripts
```

---

## 👨‍💻 Author
**Harshad Teli**  
*Enabling Digital Democracy through Modern Technology.*
