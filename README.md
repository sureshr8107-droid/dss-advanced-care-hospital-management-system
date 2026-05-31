# DSS Advanced Care – Hospital Appointment Booking System
## Setup Instructions for XAMPP

---

### 📋 Prerequisites
- XAMPP installed (Apache + MySQL + PHP 7.4+)
- Web browser (Chrome/Firefox recommended)

---

### 🚀 Setup Steps

#### Step 1: Copy Files
Copy the entire `dss_advanced_care` folder to:
```
C:\xampp\htdocs\dss_advanced_care\
```
(On Linux/Mac: `/opt/lampp/htdocs/dss_advanced_care/`)

#### Step 2: Start XAMPP
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

#### Step 3: Create Database
1. Open browser → go to `http://localhost/phpmyadmin`
2. Click **"New"** to create a database → Name it `dss_hospital` → Click **Create**
3. Click on `dss_hospital` in the left panel
4. Click **SQL** tab
5. Copy-paste contents of `db/setup.sql` and click **Go**

#### Step 4: Access the Website
Open browser → go to:
```
http://localhost/dss_advanced_care/
```

---

### 👥 Test Accounts

#### Patient Login
- Register a new account at `/register.php`
- Or use the registration page to create your account

#### Doctor Login (`/doctor_login.php`)
| Doctor | Email | Password |
|--------|-------|----------|
| Mathivanan M | mathivanan@dsscare.com | password |
| Suresh R | suresh@dsscare.com | password |
| Zainudeen | zainudeen@dsscare.com | password |
| Maaran V | maaran@dsscare.com | password |
| Rashika Shree | rashika@dsscare.com | password |
| Karthick M | karthick@dsscare.com | password |
| Arjun | arjun@dsscare.com | password |
| Trisha V | trisha@dsscare.com | password |
| Stalin K | stalin@dsscare.com | password |
| Thulasi D | thulasi@dsscare.com | password |
| Ganesh G | ganesh@dsscare.com | password |

---

### 📁 File Structure
```
dss_advanced_care/
├── config.php              ← DB config + session helpers
├── index.php               ← Homepage
├── register.php            ← Patient registration
├── login.php               ← Patient login
├── logout.php              ← Patient logout
├── select_disease.php      ← Disease/condition selector
├── doctors.php             ← Doctors listing with filters
├── book_appointment.php    ← Appointment booking form
├── confirmation.php        ← Booking confirmation
├── my_appointments.php     ← Patient appointment history
├── doctor_login.php        ← Doctor portal login
├── doctor_dashboard.php    ← Doctor appointment dashboard
├── doctor_logout.php       ← Doctor logout
├── assets/
│   ├── css/style.css       ← Main stylesheet
│   └── js/main.js          ← JavaScript
└── db/
    └── setup.sql           ← Database setup script
```

---

### 🗄️ Database Tables
| Table | Description |
|-------|-------------|
| `users` | Patient accounts |
| `doctors` | Doctor profiles |
| `slots` | Available time slots |
| `appointments` | Booked appointments |

---

### ✨ Features
- ✅ Patient Registration & Login (PHP sessions + password hashing)
- ✅ 11 Disease categories with icon-based UI
- ✅ Doctor listing with filtering by specialization
- ✅ SVG doctor profile illustrations
- ✅ 7-day slot availability calendar
- ✅ Full patient details form (age, height, weight, gender, food pref)
- ✅ Dummy payment system (Pay at Hospital / Online Card/UPI)
- ✅ Animated booking confirmation with reference number
- ✅ Patient appointment history
- ✅ Doctor dashboard with patient details expandable view
- ✅ Responsive design (mobile-friendly)
- ✅ Blue medical theme with Playfair Display typography

---

### ⚠️ Notes
- This is a demo system. The online payment is **simulated** (no real payment gateway)
- Doctor passwords in the database use PHP's `password_hash()` — default demo password is `password`
- Slots are generated for the next 7 days from the time you run `setup.sql`

---

*Built with HTML, CSS, PHP & MySQL for XAMPP environment*
*DSS Advanced Care Hospital — Chennai, Tamil Nadu*
