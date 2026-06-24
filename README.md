# Hospital-Management-System
Final year project system June 2026 - A digital web-based Healthcare System
#  Afya One Hospital Management System

> A web-based Hospital Management System developed as a Final Year Research Project at the Catholic University of Eastern Africa (CUEA).

---

---

##  About The System

Afya One HMS is a fully digital hospital management platform that streamlines patient care workflows in a Kenyan healthcare context. The system eliminates manual paper-based processes by providing a centralized digital platform for patient registration, appointment booking, prescription management and M-Pesa bill payment.

---

##  System Users

| Role | Portal | File |
|---|---|---|
| Patient | Patient Dashboard | `patient-panel.php` |
| Doctor | Doctor Dashboard | `doctor-panel.php` |
| Administrator | Admin Dashboard | `admin-panel1.php` |

---

##  Key Features

-  Role-based access control — Patient, Doctor and Admin portals
-  Patient registration and secure login
-  Online appointment booking with conflict checking
-  Doctor appointment approval and cancellation workflow
-  Digital prescription with integrated pharmacy database (30 medicines)
-  Simulated M-Pesa STK Push payment flow
-  Itemized PDF bill generation (consultation fee + medicine costs)
-  Admin payment status tracking dashboard
-  Patient notification bell (approved/pending/cancelled)
-  bcrypt password hashing via PHP password_hash()
-  Contact Us form with admin message viewing

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| CSS Framework | Bootstrap 5 |
| Backend | PHP 8.0 |
| Database | MySQL |
| Local Server | XAMPP (Apache) |
| PDF Generation | TCPDF Library |
| Password Security | bcrypt (password_hash / password_verify) |
| Code Editor | Visual Studio Code |

---

##  Database

The database is called `myhmsdb` and contains 8 tables:

| Table | Purpose |
|---|---|
| `patreg` | Patient registration records |
| `doctb` | Doctor records |
| `appointmenttb` | Appointment bookings |
| `prestb` | Prescriptions |
| `pharmacytb` | Medicine inventory (30 medicines) |
| `prescriptionmeds` | Medicines per prescription |
| `contact` | Contact form messages |
| `admintb` | Admin credentials |

---

##  Setup Instructions

### Prerequisites
- XAMPP installed (Apache + MySQL + PHP 8.0)
- Web browser (Chrome, Firefox, Edge)

### Step 1 — Clone or Download
Download the project files and place them in your XAMPP htdocs folder:
```
C:\xampp\htdocs\Hospital-Management-System\
```

### Step 2 — Import Database
1. Open phpMyAdmin:
```
http://localhost/phpmyadmin
```
2. Create a new database called `myhmsdb`
3. Click **Import** tab
4. Select the `myhmsdb.sql` file
5. Click **Go**

### Step 3 — Install TCPDF
1. Download TCPDF from: https://tcpdf.org
2. Place the `TCPDF` folder inside the project root:
```
C:\xampp\htdocs\Hospital-Management-System\TCPDF\
```

### Step 4 — Run The System
1. Start XAMPP — make sure Apache and MySQL are both **green**
2. Open your browser and go to:
```
http://localhost/Hospital-Management-System/
```

---

##  Test Login Credentials

### Admin
| Field | Value |
|---|---|
| Username | `admin` |
| Password | `password` |

### Doctor (example)
| Field | Value |
|---|---|
| Username | `Kieran` |
| Password | `password` |

### Patient (example)
| Field | Value |
|---|---|
| Email | (use any registered patient email) |
| Password | `password` |

> **Note:** New patients who register through the registration form can use any password of their choice — it will be securely hashed automatically.

---

##  Project Structure

```
Hospital-Management-System/
│
├── index.php               # Homepage — Login & Registration
├── patient-panel.php       # Patient Dashboard
├── doctor-panel.php        # Doctor Dashboard
├── admin-panel1.php        # Admin Dashboard
├── prescribe.php           # Prescription Form
├── payment-status.php      # Admin Payment Tracker
├── func.php                # Patient auth & helper functions
├── func1.php               # Doctor auth
├── func2.php               # Patient registration
├── func3.php               # Admin auth
├── newfunc.php             # Display functions
├── contact.php             # Contact form handler
├── logout.php              # Patient logout
├── logout1.php             # Doctor/Admin logout
├── style.css               # Custom styles
├── services.php            # About Us page
├── contact.html            # Contact Us page
├── myhmsdb.sql             # Database export
└── images/                 # System images
```

---

##  M-Pesa Integration

The system implements a **simulated M-Pesa STK Push** payment flow:
1. Patient clicks Pay — modal opens requesting phone number
2. System simulates STK Push with a processing screen
3. Success screen shows dummy M-Pesa reference number
4. Patient downloads itemized PDF receipt

> In a production environment this would connect to the Safaricom Daraja API.

---

## PDF Bill

The PDF bill is generated using TCPDF and includes:
- Patient and appointment details
- Itemized list of prescribed medicines with quantities and prices
- Consultation fee
- Medicine total
- **Grand Total (Consultation + Medicine)**
- Payment status: PAID via M-Pesa
- Receipt date and time

---

##  Academic Submission

This project was submitted in partial fulfillment of the requirements for the award of Bachelor of Science in Computer Science at the Catholic University of Eastern Africa.

---

