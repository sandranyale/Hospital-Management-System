<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Afya One — Smart Healthcare</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal:    #0d9488;
            --teal-dk: #0f766e;
            --teal-lt: #ccfbf1;
            --navy:    #0f172a;
            --white:   #ffffff;
            --muted:   #64748b;
            --border:  rgba(255,255,255,.12);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--navy);
            color: var(--white);
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 18px 60px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(15,23,42,.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: all .3s;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 900; color: var(--white);
            text-decoration: none;
        }
        .nav-brand .brand-dot { color: var(--teal); }
        .nav-brand .brand-icon {
            width: 36px; height: 36px; background: var(--teal);
            border-radius: 8px; display: flex; align-items: center;
            justify-content: center; font-size: 16px;
        }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a {
            color: rgba(255,255,255,.7); font-size: 14px; font-weight: 500;
            text-decoration: none; transition: color .2s; letter-spacing: .3px;
        }
        .nav-links a:hover { color: var(--white); }
        .nav-cta {
            background: var(--teal); color: var(--white) !important;
            padding: 9px 22px; border-radius: 30px; font-weight: 600 !important;
            transition: background .2s !important;
        }
        .nav-cta:hover { background: var(--teal-dk) !important; color: var(--white) !important; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(15,23,42,.95) 0%, rgba(13,148,136,.3) 100%),
                url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1600&q=80') center/cover no-repeat;
            display: flex; align-items: center;
            padding: 120px 60px 80px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; top: -100px; right: -100px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(13,148,136,.2) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute; bottom: -80px; left: 30%;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(13,148,136,.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-content { max-width: 560px; position: relative; z-index: 2; }
        .hero-tag {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(13,148,136,.2); border: 1px solid rgba(13,148,136,.4);
            color: #5eead4; padding: 6px 16px; border-radius: 30px;
            font-size: 13px; font-weight: 600; letter-spacing: .5px;
            margin-bottom: 24px; text-transform: uppercase;
        }
        .hero-tag i { font-size: 10px; }
        .hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(42px, 5vw, 64px);
            font-weight: 900; line-height: 1.1;
            margin-bottom: 20px;
        }
        .hero h1 span { color: var(--teal); }
        .hero p {
            font-size: 18px; color: rgba(255,255,255,.7);
            line-height: 1.7; margin-bottom: 36px; font-weight: 300;
        }
        .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; }
        .btn-primary-hero {
            background: var(--teal); color: var(--white);
            padding: 14px 30px; border-radius: 30px;
            font-size: 15px; font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: all .2s; border: none; cursor: pointer;
        }
        .btn-primary-hero:hover { background: var(--teal-dk); transform: translateY(-2px); color: #fff; }
        .btn-outline-hero {
            background: transparent; color: var(--white);
            padding: 14px 30px; border-radius: 30px;
            font-size: 15px; font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            border: 1.5px solid rgba(255,255,255,.3); transition: all .2s;
        }
        .btn-outline-hero:hover { border-color: var(--teal); color: var(--teal); background: rgba(13,148,136,.1); }

        /* Stats strip */
        .hero-stats {
            display: flex; gap: 40px; margin-top: 52px;
            padding-top: 32px; border-top: 1px solid rgba(255,255,255,.1);
        }
        .stat-item h3 {
            font-family: 'Playfair Display', serif;
            font-size: 28px; font-weight: 900; color: var(--teal);
        }
        .stat-item p { font-size: 13px; color: rgba(255,255,255,.5); margin-top: 2px; }

        /* Hero right — login card */
        .hero-right {
            flex: 1; display: flex; justify-content: flex-end;
            position: relative; z-index: 2;
        }
        .login-card {
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 24px; padding: 36px;
            width: 400px; flex-shrink: 0;
        }
        .login-tabs {
            display: flex; gap: 4px;
            background: rgba(255,255,255,.07);
            border-radius: 14px; padding: 4px;
            margin-bottom: 28px;
        }
        .login-tab {
            flex: 1; padding: 9px; border-radius: 10px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            color: rgba(255,255,255,.5); text-align: center;
            transition: all .2s; border: none; background: none;
        }
        .login-tab.active { background: var(--teal); color: var(--white); }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; animation: fadeIn .3s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }

        .tab-panel h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px; font-weight: 700; margin-bottom: 20px;
        }
        .form-field { margin-bottom: 14px; }
        .form-field input, .form-field select {
            width: 100%; padding: 12px 16px;
            background: rgba(255,255,255,.08);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 12px; color: var(--white);
            font-size: 14px; font-family: 'Outfit', sans-serif;
            outline: none; transition: border-color .2s;
        }
        .form-field input::placeholder { color: rgba(255,255,255,.35); }
        .form-field input:focus { border-color: var(--teal); background: rgba(13,148,136,.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .gender-row { display: flex; gap: 16px; padding: 4px 0; }
        .gender-row label {
            display: flex; align-items: center; gap: 6px;
            font-size: 14px; color: rgba(255,255,255,.7); cursor: pointer;
        }
        .pass-match { font-size: 12px; font-weight: 600; margin-top: 4px; }
        .match-ok  { color: #4ade80; }
        .match-err { color: #f87171; }
        .btn-submit {
            width: 100%; background: var(--teal); color: var(--white);
            border: none; border-radius: 12px; padding: 13px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            font-family: 'Outfit', sans-serif; transition: all .2s;
            margin-top: 4px;
        }
        .btn-submit:hover { background: var(--teal-dk); transform: translateY(-1px); }
        .form-link {
            text-align: center; margin-top: 14px;
            font-size: 13px; color: rgba(255,255,255,.5);
        }
        .form-link a { color: var(--teal); text-decoration: none; font-weight: 600; }

        /* ── SERVICES ── */
        .section { padding: 90px 60px; }
        .section-tag {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--teal); font-size: 13px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px; margin-bottom: 14px;
        }
        .section-tag::before {
            content: ''; width: 24px; height: 2px; background: var(--teal);
        }
        .section h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(30px, 4vw, 44px); font-weight: 900;
            margin-bottom: 14px; line-height: 1.2;
        }
        .section-sub { font-size: 17px; color: rgba(255,255,255,.55); max-width: 520px; line-height: 1.7; }

        .services-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px; margin-top: 50px;
        }
        .service-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px; padding: 30px;
            transition: all .3s; position: relative; overflow: hidden;
        }
        .service-card::before {
            content: ''; position: absolute; top: 0; left: 0;
            width: 100%; height: 3px; background: var(--teal);
            transform: scaleX(0); transform-origin: left; transition: transform .3s;
        }
        .service-card:hover { background: rgba(13,148,136,.08); border-color: rgba(13,148,136,.3); transform: translateY(-4px); }
        .service-card:hover::before { transform: scaleX(1); }
        .service-icon {
            width: 52px; height: 52px; background: rgba(13,148,136,.15);
            border-radius: 14px; display: flex; align-items: center;
            justify-content: center; font-size: 22px; color: var(--teal);
            margin-bottom: 18px;
        }
        .service-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
        .service-card p { font-size: 14px; color: rgba(255,255,255,.5); line-height: 1.6; }

        /* ── HOW IT WORKS ── */
        .how-section {
            padding: 90px 60px;
            background: rgba(255,255,255,.02);
            border-top: 1px solid rgba(255,255,255,.06);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .steps-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px; margin-top: 50px; position: relative;
        }
        .step {
            text-align: center; padding: 20px;
        }
        .step-num {
            width: 56px; height: 56px;
            background: var(--teal); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 900; margin: 0 auto 18px;
        }
        .step h3 { font-size: 17px; font-weight: 700; margin-bottom: 8px; }
        .step p { font-size: 14px; color: rgba(255,255,255,.5); line-height: 1.6; }

        /* ── FOOTER ── */
        footer {
            padding: 50px 60px 30px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .footer-top {
            display: flex; justify-content: space-between; align-items: flex-start;
            flex-wrap: wrap; gap: 30px; margin-bottom: 40px;
        }
        .footer-brand h2 {
            font-family: 'Playfair Display', serif;
            font-size: 24px; font-weight: 900; margin-bottom: 8px;
        }
        .footer-brand h2 span { color: var(--teal); }
        .footer-brand p { font-size: 14px; color: rgba(255,255,255,.4); max-width: 260px; line-height: 1.6; }
        .footer-links h4 { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: rgba(255,255,255,.4); margin-bottom: 14px; }
        .footer-links a { display: block; color: rgba(255,255,255,.6); font-size: 14px; text-decoration: none; margin-bottom: 8px; transition: color .2s; }
        .footer-links a:hover { color: var(--teal); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.06);
            padding-top: 24px; display: flex; justify-content: space-between;
            font-size: 13px; color: rgba(255,255,255,.3); flex-wrap: wrap; gap: 10px;
        }

        @media (max-width: 900px) {
            nav { padding: 16px 24px; }
            .nav-links { display: none; }
            .hero { flex-direction: column; padding: 100px 24px 60px; }
            .hero-right { display: none; }
            .section, .how-section { padding: 60px 24px; }
            footer { padding: 40px 24px 24px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav id="navbar">
    <a class="nav-brand" href="#">
        <div class="brand-icon"><i class="fa-solid fa-hospital" style="color:#fff"></i></div>
        Afya <span class="brand-dot">One</span>
    </a>
    <div class="nav-links">
        <a href="#services">Services</a>
        <a href="#how">How It Works</a>
        <a href="contact.html">Contact</a>
        <a href="#login-section" class="nav-cta">Get Started</a>
    </div>
</nav>

<!-- HERO -->
<section class="hero" id="login-section">
    <div class="hero-content">
        <div class="hero-tag"><i class="fa-solid fa-circle"></i> Kenya's Digital Health Platform</div>
        <h1>Smart Healthcare <span>Starts Here</span></h1>
        <p>Book appointments, access prescriptions, pay bills seamlessly all in one secure platform built for modern Kenyan healthcare.</p>
        <div class="hero-btns">
            <a href="#login-section" class="btn-primary-hero" onclick="setTab('patient')">
                <i class="fa-solid fa-calendar-plus"></i> Book Appointment
            </a>
            <a href="#services" class="btn-outline-hero">
                <i class="fa-solid fa-circle-info"></i> Learn More
            </a>
        </div>
        <div class="hero-stats">
            <div class="stat-item"><h3>500+</h3><p>Patients Served</p></div>
            <div class="stat-item"><h3>20+</h3><p>Specialist Doctors</p></div>
            <div class="stat-item"><h3>24/7</h3><p>Digital Access</p></div>
        </div>
    </div>

    <!-- LOGIN CARD -->
    <div class="hero-right">
        <div class="login-card">
            <div class="login-tabs">
                <button class="login-tab active" onclick="setTab('patient')" id="tab-patient">Patient</button>
                <button class="login-tab" onclick="setTab('doctor')" id="tab-doctor">Doctor</button>
                <button class="login-tab" onclick="setTab('admin')" id="tab-admin">Admin</button>
            </div>

            <!-- PATIENT TAB -->
            <div class="tab-panel active" id="panel-patient">
                <h3>Welcome Back!</h3>
                <!-- Login -->
                <form method="post" action="func.php" id="pat-login-form">
                    <div class="form-field">
                        <input type="email" name="email" placeholder="Email address" required>
                    </div>
                    <div class="form-field">
                        <input type="password" name="password2" placeholder="Password" required>
                    </div>
                    <button type="submit" name="patsub" class="btn-submit">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In
                    </button>
                </form>
                <div class="form-link">
                    Don't have an account?
                    <a href="#" onclick="togglePatientForm()">Register here</a>
                </div>

                <!-- Register (hidden by default) -->
                <form method="post" action="func2.php" id="pat-register-form" style="display:none">
                    <h3 style="margin-bottom:16px">Create Account</h3>
                    <div class="form-row">
                        <div class="form-field">
                            <input type="text" name="fname" placeholder="First Name *" onkeydown="return alphaOnly(event)" required>
                        </div>
                        <div class="form-field">
                            <input type="text" name="lname" placeholder="Last Name *" onkeydown="return alphaOnly(event)" required>
                        </div>
                    </div>
                    <div class="form-field">
                        <input type="email" name="email" placeholder="Email address *" required>
                    </div>
                    <div class="form-field">
                        <input type="tel" name="contact" maxlength="10" placeholder="Phone Number *" required>
                    </div>
                    <div class="form-field">
                        <input type="password" name="password" id="password" placeholder="Password *" onkeyup="check()" required>
                    </div>
                    <div class="form-field">
                        <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password *" onkeyup="check()" required>
                        <div class="pass-match" id="message"></div>
                    </div>
                    <div class="gender-row">
                        <label><input type="radio" name="gender" value="Male" checked> Male</label>
                        <label><input type="radio" name="gender" value="Female"> Female</label>
                    </div>
                    <button type="submit" name="patsub1" class="btn-submit" onclick="return checklen()" style="margin-top:14px">
                        <i class="fa-solid fa-user-plus"></i> Create Account
                    </button>
                    <div class="form-link">
                        Already have an account?
                        <a href="#" onclick="togglePatientForm()">Sign in</a>
                    </div>
                </form>
            </div>

            <!-- DOCTOR TAB -->
            <div class="tab-panel" id="panel-doctor">
                <h3>Doctor Login </h3>
                <form method="post" action="func1.php">
                    <div class="form-field">
                        <input type="text" name="username3" placeholder="Username" required>
                    </div>
                    <div class="form-field">
                        <input type="password" name="password3" placeholder="Password" required>
                    </div>
                    <button type="submit" name="docsub1" class="btn-submit">
                        <i class="fa-solid fa-stethoscope"></i> Sign In as Doctor
                    </button>
                </form>
            </div>

            <!-- ADMIN TAB -->
            <div class="tab-panel" id="panel-admin">
                <h3>Admin Portal </h3>
                <form method="post" action="func3.php">
                    <div class="form-field">
                        <input type="text" name="username1" placeholder="Admin Username" required>
                    </div>
                    <div class="form-field">
                        <input type="password" name="password2" placeholder="Password" required>
                    </div>
                    <button type="submit" name="adsub" class="btn-submit">
                        <i class="fa-solid fa-shield-halved"></i> Sign In as Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="section" id="services">
    <div class="section-tag">What We Offer</div>
    <h2>Comprehensive Healthcare<br>at Your Fingertips</h2>
    <p class="section-sub">From booking to billing, Afya One covers every step of your healthcare journey digitally.</p>

    <div class="services-grid">
        <div class="service-card">
            <div class="service-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <h3>Easy Appointment Booking</h3>
            <p>Book appointments with specialist doctors online in seconds. Choose your preferred date and time.</p>
        </div>
        <div class="service-card">
            <div class="service-icon"><i class="fa-solid fa-user-doctor"></i></div>
            <h3>Specialist Doctors</h3>
            <p>Access a wide network of verified specialists — Cardiologists, Neurologists, Pediatricians and more.</p>
        </div>
        <div class="service-card">
            <div class="service-icon"><i class="fa-solid fa-file-medical"></i></div>
            <h3>Digital Prescriptions</h3>
            <p>Doctors issue prescriptions digitally. View your prescription history anytime from your dashboard.</p>
        </div>
        <div class="service-card">
            <div class="service-icon"><i class="fa-brands fa-android"></i></div>
            <h3>M-Pesa Bill Payment</h3>
            <p>Pay your consultation fees instantly via M-Pesa STK Push. Secure, fast, and fully digital.</p>
        </div>
        <div class="service-card">
            <div class="service-icon"><i class="fa-solid fa-file-invoice"></i></div>
            <h3>Instant Bill Receipts</h3>
            <p>Download a detailed PDF receipt for every payment — perfect for insurance claims and records.</p>
        </div>
        <div class="service-card">
            <div class="service-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <h3>Secure & Private</h3>
            <p>Your health data is protected. Secure logins for patients, doctors, and administrators.</p>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="how">
    <div style="max-width:1100px;margin:0 auto">
        <div class="section-tag">Simple Process</div>
        <h2>How Afya One Works</h2>
        <p class="section-sub">Get from registration to consultation in four simple steps.</p>
        <div class="steps-grid">
            <div class="step">
                <div class="step-num">1</div>
                <h3>Register</h3>
                <p>Create your free patient account with your name, email, and phone number.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3>Book Appointment</h3>
                <p>Choose a specialist, pick a date and time that works for you.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3>Get Prescription</h3>
                <p>Your doctor reviews your case and issues a digital prescription to your portal.</p>
            </div>
            <div class="step">
                <div class="step-num">4</div>
                <h3>Pay via M-Pesa</h3>
                <p>Receive an STK push, enter your PIN, and download your receipt instantly.</p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-top">
        <div class="footer-brand">
            <h2>Afya <span>One</span></h2>
            <p>Kenya's modern hospital management system — connecting patients and doctors digitally.</p>
        </div>
        <div class="footer-links">
            <h4>Navigation</h4>
            <a href="#services">Services</a>
            <a href="#how">How It Works</a>
            <a href="contact.html">Contact Us</a>
        </div>
        <div class="footer-links">
            <h4>Portals</h4>
            <a href="#" onclick="setTab('patient')">Patient Login</a>
            <a href="#" onclick="setTab('doctor')">Doctor Login</a>
            <a href="#" onclick="setTab('admin')">Admin Login</a>
        </div>
        <div class="footer-links">
            <h4>Contact</h4>
            <a href="#">info@afyaone.co.ke</a>
            <a href="#">+254 700 000 000</a>
            <a href="#">Nairobi, Kenya</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© <?= date('Y') ?> Afya One Hospital Management System. All rights reserved.</span>
        <span>Built with LOVE  for Kenyan Healthcare</span>
    </div>
</footer>

<script>
    // Tab switching
    function setTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.login-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('panel-' + tab).classList.add('active');
        document.getElementById('tab-' + tab).classList.add('active');
        // scroll to login card on mobile
        document.getElementById('login-section').scrollIntoView({behavior:'smooth'});
    }

    // Toggle patient login/register
    let showingRegister = false;
    function togglePatientForm() {
        showingRegister = !showingRegister;
        document.getElementById('pat-login-form').style.display = showingRegister ? 'none' : 'block';
        document.getElementById('pat-register-form').style.display = showingRegister ? 'block' : 'none';
        document.querySelector('#panel-patient h3').textContent = showingRegister ? 'Create Account 📋' : 'Welcome Back 👋';
    }

    // Password validation
    function check() {
        const p1 = document.getElementById('password').value;
        const p2 = document.getElementById('cpassword').value;
        const msg = document.getElementById('message');
        if (!p2) { msg.innerHTML = ''; return; }
        if (p1 === p2) {
            msg.innerHTML = '<i class="fa-solid fa-check"></i> Passwords match';
            msg.className = 'pass-match match-ok';
        } else {
            msg.innerHTML = '<i class="fa-solid fa-xmark"></i> Not matching';
            msg.className = 'pass-match match-err';
        }
    }
    function alphaOnly(e) { var k=e.keyCode; return((k>=65&&k<=90)||k==8||k==32); }
    function checklen() {
        var p = document.getElementById('password');
        if (p.value.length < 6) { alert('Password must be at least 6 characters!'); return false; }
    }

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').style.background =
            window.scrollY > 50 ? 'rgba(15,23,42,.98)' : 'rgba(15,23,42,.85)';
    });
</script>
</body>
</html>