<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Login | Afya One Hospitals</title>
    <link rel="shortcut icon" href="images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal:    #0d9488;
            --teal-dk: #0f766e;
            --teal-lt: #ccfbf1;
            --navy:    #0f172a;
            --white:   #ffffff;
            --border:  rgba(255,255,255,.1);
            --muted:   rgba(255,255,255,.5);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Outfit',sans-serif;
            background:var(--navy);
            color:var(--white);
            min-height:100vh;
            display:flex; flex-direction:column;
        }

        /* ── NAVBAR ── */
        nav {
            padding:18px 48px;
            display:flex; align-items:center; justify-content:space-between;
            border-bottom:1px solid var(--border);
            background:rgba(15,23,42,.8);
            backdrop-filter:blur(16px);
        }
        .nav-brand {
            display:flex; align-items:center; gap:10px;
            font-family:'Playfair Display',serif;
            font-size:20px; font-weight:900; color:var(--white);
            text-decoration:none;
        }
        .nav-brand .icon { width:34px; height:34px; background:var(--teal); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; }
        .nav-brand span { color:var(--teal); }
        .nav-back {
            display:inline-flex; align-items:center; gap:7px;
            color:var(--muted); font-size:13px; font-weight:500;
            text-decoration:none; transition:color .2s;
        }
        .nav-back:hover { color:var(--white); }

        /* ── MAIN ── */
        .main {
            flex:1; display:flex; align-items:center; justify-content:center;
            padding:40px 24px;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(13,148,136,.12) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 20%, rgba(13,148,136,.08) 0%, transparent 50%),
                var(--navy);
        }
        .login-wrapper {
            display:grid; grid-template-columns:1fr 1fr;
            max-width:900px; width:100%;
            background:rgba(255,255,255,.03);
            border:1px solid var(--border);
            border-radius:24px; overflow:hidden;
            box-shadow:0 24px 80px rgba(0,0,0,.4);
        }

        /* LEFT PANEL */
        .left-panel {
            background:linear-gradient(135deg, var(--teal) 0%, var(--teal-dk) 100%);
            padding:48px 40px;
            display:flex; flex-direction:column; justify-content:center;
            position:relative; overflow:hidden;
        }
        .left-panel::before {
            content:'';
            position:absolute; top:-60px; right:-60px;
            width:220px; height:220px;
            background:rgba(255,255,255,.08); border-radius:50%;
        }
        .left-panel::after {
            content:'';
            position:absolute; bottom:-80px; left:-40px;
            width:260px; height:260px;
            background:rgba(255,255,255,.05); border-radius:50%;
        }
        .left-content { position:relative; z-index:2; }
        .left-icon {
            width:60px; height:60px;
            background:rgba(255,255,255,.15); border-radius:16px;
            display:flex; align-items:center; justify-content:center;
            font-size:26px; margin-bottom:24px;
        }
        .left-panel h2 {
            font-family:'Playfair Display',serif;
            font-size:28px; font-weight:900; margin-bottom:12px; line-height:1.2;
        }
        .left-panel p { font-size:14px; opacity:.8; line-height:1.7; margin-bottom:28px; }
        .left-features { display:flex; flex-direction:column; gap:12px; }
        .left-feature {
            display:flex; align-items:center; gap:10px;
            font-size:13px; opacity:.9;
        }
        .left-feature i { color:rgba(255,255,255,.8); font-size:14px; }

        /* RIGHT PANEL */
        .right-panel { padding:48px 40px; }
        .right-panel h3 {
            font-family:'Playfair Display',serif;
            font-size:24px; font-weight:900; margin-bottom:6px;
        }
        .right-panel .subtitle { font-size:14px; color:var(--muted); margin-bottom:32px; }

        .form-group { margin-bottom:18px; }
        .form-label { display:block; font-size:13px; font-weight:600; color:rgba(255,255,255,.65); margin-bottom:7px; }
        .input-wrap { position:relative; }
        .input-wrap i {
            position:absolute; left:14px; top:50%; transform:translateY(-50%);
            color:rgba(255,255,255,.3); font-size:14px;
        }
        .form-input {
            width:100%; padding:12px 14px 12px 40px;
            background:rgba(255,255,255,.07);
            border:1.5px solid rgba(255,255,255,.1);
            border-radius:12px; color:var(--white);
            font-size:14px; font-family:'Outfit',sans-serif;
            outline:none; transition:all .2s;
        }
        .form-input::placeholder { color:rgba(255,255,255,.25); }
        .form-input:focus { border-color:var(--teal); background:rgba(13,148,136,.08); box-shadow:0 0 0 3px rgba(13,148,136,.15); }

        .btn-login {
            width:100%; background:var(--teal); color:var(--white);
            border:none; border-radius:12px; padding:14px;
            font-size:15px; font-weight:700; cursor:pointer;
            font-family:'Outfit',sans-serif;
            display:flex; align-items:center; justify-content:center; gap:8px;
            transition:all .2s; margin-top:8px;
        }
        .btn-login:hover { background:var(--teal-dk); transform:translateY(-1px); box-shadow:0 6px 24px rgba(13,148,136,.35); }

        .divider { display:flex; align-items:center; gap:12px; margin:20px 0; }
        .divider::before, .divider::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.1); }
        .divider span { font-size:12px; color:var(--muted); }

        .register-link {
            display:block; text-align:center;
            background:rgba(255,255,255,.04);
            border:1.5px solid rgba(255,255,255,.1);
            border-radius:12px; padding:13px;
            font-size:14px; font-weight:600; color:rgba(255,255,255,.7);
            text-decoration:none; transition:all .2s;
        }
        .register-link:hover { border-color:var(--teal); color:var(--teal); background:rgba(13,148,136,.07); }
        .register-link span { color:var(--teal); }

        .form-footer { text-align:center; margin-top:20px; font-size:13px; color:var(--muted); }
        .form-footer a { color:var(--teal); text-decoration:none; font-weight:600; }

        /* ERROR TOAST */
        .error-banner {
            background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.3);
            border-radius:10px; padding:12px 16px; margin-bottom:20px;
            display:flex; align-items:center; gap:10px;
            font-size:13px; color:#fca5a5;
        }

        @media(max-width:680px){
            .login-wrapper { grid-template-columns:1fr; }
            .left-panel { display:none; }
            nav { padding:16px 24px; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a class="nav-brand" href="index.php">
        <div class="icon"><i class="fa-solid fa-hospital" style="color:#fff"></i></div>
        Afya <span>One</span>
    </a>
    <a class="nav-back" href="index.php">
        <i class="fa-solid fa-arrow-left"></i> Back to Home
    </a>
</nav>

<!-- MAIN -->
<div class="main">
    <div class="login-wrapper">

        <!-- LEFT -->
        <div class="left-panel">
            <div class="left-content">
                <div class="left-icon"><i class="fa-solid fa-user-injured"></i></div>
                <h2>Patient Portal</h2>
                <p>Sign in to manage your appointments, view prescriptions and pay your bills — all in one place.</p>
                <div class="left-features">
                    <div class="left-feature"><i class="fa-solid fa-calendar-check"></i> Book & manage appointments</div>
                    <div class="left-feature"><i class="fa-solid fa-file-medical"></i> View digital prescriptions</div>
                    <div class="left-feature"><i class="fa-brands fa-android"></i> Pay via M-Pesa instantly</div>
                    <div class="left-feature"><i class="fa-solid fa-file-invoice"></i> Download bill receipts</div>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="right-panel">
            <h3>Welcome Back </h3>
            <p class="subtitle">Sign in to your patient account</p>

            <?php if (isset($_GET['error'])): ?>
            <div class="error-banner">
                <i class="fa-solid fa-circle-exclamation"></i>
                Invalid email or password. Please try again.
            </div>
            <?php endif; ?>

            <form method="post" action="func.php">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" name="email" class="form-input"
                            placeholder="your@email.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="password2" class="form-input"
                            placeholder="Enter your password" required>
                    </div>
                </div>
                <button type="submit" name="patsub" class="btn-login">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In
                </button>
            </form>

            <div class="divider"><span>Don't have an account?</span></div>

            <a href="index.php" class="register-link">
                Create a free account <span>→</span>
            </a>

            <div class="form-footer">
                Are you a doctor? <a href="index.php">Doctor login →</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>