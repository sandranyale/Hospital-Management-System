<!DOCTYPE html>
<?php
session_start();
session_destroy();
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="3;url=index.php">
    <title>Logged Out | Afya One</title>
    <link rel="shortcut icon" href="images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --teal:#0d9488; --navy:#0f172a; --white:#ffffff; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Outfit',sans-serif;
            background:var(--navy); color:var(--white);
            min-height:100vh; display:flex; flex-direction:column;
            align-items:center; justify-content:center; text-align:center;
            padding:24px;
        }
        .logout-card {
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.08);
            border-radius:24px; padding:52px 48px;
            max-width:420px; width:100%;
            box-shadow:0 24px 80px rgba(0,0,0,.3);
        }
        .logout-icon {
            width:72px; height:72px;
            background:rgba(13,148,136,.15); border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:30px; color:var(--teal); margin:0 auto 24px;
            animation:pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100%{ box-shadow:0 0 0 0 rgba(13,148,136,.3); }
            50%{ box-shadow:0 0 0 14px rgba(13,148,136,0); }
        }
        .brand { font-family:'Playfair Display',serif; font-size:20px; font-weight:900; margin-bottom:6px; }
        .brand span { color:var(--teal); }
        h2 { font-family:'Playfair Display',serif; font-size:26px; font-weight:900; margin-bottom:10px; }
        p { font-size:14px; color:rgba(255,255,255,.55); line-height:1.6; margin-bottom:28px; }
        .redirect-info {
            font-size:13px; color:rgba(255,255,255,.35); margin-bottom:24px;
            display:flex; align-items:center; justify-content:center; gap:6px;
        }
        .spinner {
            width:14px; height:14px; border:2px solid rgba(255,255,255,.15);
            border-top:2px solid var(--teal); border-radius:50%;
            animation:spin .8s linear infinite;
        }
        @keyframes spin { to{transform:rotate(360deg)} }
        .btn-home {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--teal); color:var(--white);
            padding:12px 28px; border-radius:12px;
            font-size:14px; font-weight:700; text-decoration:none;
            transition:all .2s;
        }
        .btn-home:hover { background:#0f766e; transform:translateY(-1px); color:#fff; }
        /* Progress bar */
        .progress-bar {
            width:100%; height:3px; background:rgba(255,255,255,.08);
            border-radius:10px; margin-bottom:24px; overflow:hidden;
        }
        .progress-fill {
            height:100%; background:var(--teal); border-radius:10px;
            animation:fill 3s linear forwards;
        }
        @keyframes fill { from{width:0%} to{width:100%} }
    </style>
</head>
<body>
    <div class="logout-card">
        <div class="logout-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="brand">Afya <span>One</span></div>
        <h2>You've been signed out</h2>
        <p>Your session has ended securely. Thank you for using Afya One Hospital Management System.</p>
        <div class="progress-bar"><div class="progress-fill"></div></div>
        <div class="redirect-info">
            <div class="spinner"></div> Redirecting to home in 3 seconds...
        </div>
        <a href="index.php" class="btn-home">
            <i class="fa-solid fa-house"></i> Go to Home Now
        </a>
    </div>
</body>
</html>