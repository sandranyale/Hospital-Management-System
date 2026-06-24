<!DOCTYPE html>
<?php
$con = mysqli_connect("localhost", "root", "", "myhmsdb");
include('newfunc.php');

if (isset($_POST['docsub'])) {
    $doctor = $_POST['doctor'];
    $dpassword = $_POST['dpassword'];
    $demail = $_POST['demail'];
    $spec = $_POST['special'];
    $docFees = $_POST['docFees'];
    $query = "insert into doctb(username,password,email,spec,docFees)values('$doctor','$dpassword','$demail','$spec','$docFees')";
    $result = mysqli_query($con, $query);
    if ($result) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Doctor added successfully!','success'); });</script>";
    }
}

if (isset($_POST['docsub1'])) {
    $demail = $_POST['demail'];
    $query = "delete from doctb where email='$demail';";
    $result = mysqli_query($con, $query);
    if ($result) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Doctor removed successfully!','success'); });</script>";
    } else {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Unable to delete doctor!','error'); });</script>";
    }
}

// Fetch stats
$totalDoctors = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM doctb"))[0];
$totalPatients = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM patreg"))[0];
$totalAppointments = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb"))[0];
$totalMessages = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM contact"))[0];
$activeAppointments = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE userStatus=1 AND doctorStatus=1"))[0];
$totalPrescriptions = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM prestb"))[0];
$totalPaid    = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE payment_status='Paid'"))[0] ?? 0;
$totalPending = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE payment_status='Pending' AND userStatus=1 AND doctorStatus=1"))[0] ?? 0;
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Afya One — Admin Panel</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <style>
        :root {
            --teal:    #0d9488;
            --teal-dk: #0f766e;
            --teal-lt: #ccfbf1;
            --navy:    #0f172a;
            --slate:   #1e293b;
            --muted:   #64748b;
            --border:  #e2e8f0;
            --bg:      #f1f5f9;
            --white:   #ffffff;
            --red:     #ef4444;
            --amber:   #f59e0b;
            --green:   #10b981;
            --sidebar-w: 260px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--navy);
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy);
            display: flex; flex-direction: column;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: var(--teal);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
            margin-bottom: 10px;
        }
        .sidebar-brand h1 {
            font-family: 'Syne', sans-serif;
            font-size: 18px; font-weight: 800;
            color: #fff; line-height: 1.2;
        }
        .sidebar-brand span { color: var(--teal); }
        .sidebar-brand p {
            font-size: 11px; color: var(--muted);
            margin-top: 2px; letter-spacing: .5px;
            text-transform: uppercase;
        }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
        .nav-section-label {
            font-size: 10px; font-weight: 600;
            color: rgba(255,255,255,.25);
            text-transform: uppercase; letter-spacing: 1.2px;
            padding: 12px 12px 6px;
        }

        .nav-item { margin-bottom: 2px; }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,.55);
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }
        .nav-link i { width: 18px; font-size: 15px; }
        .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        .nav-link.active { background: var(--teal); color: #fff; }
        .nav-link .badge-pill {
            margin-left: auto;
            background: rgba(255,255,255,.15);
            color: #fff;
            font-size: 11px; padding: 2px 7px;
            border-radius: 20px;
        }
        .nav-link.active .badge-pill { background: rgba(255,255,255,.25); }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .sidebar-footer a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,.5);
            font-size: 13px; text-decoration: none;
            transition: all .2s;
        }
        .sidebar-footer a:hover { background: rgba(239,68,68,.15); color: #f87171; }

        /* ── TOPBAR ── */
        .topbar {
            position: fixed; top: 0;
            left: var(--sidebar-w); right: 0;
            height: 64px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 28px;
            z-index: 99;
            gap: 16px;
        }
        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px; font-weight: 700;
            color: var(--navy); flex: 1;
        }
        .topbar-title span { color: var(--teal); }
        .topbar-badge {
            display: flex; align-items: center; gap: 8px;
            background: var(--teal-lt);
            color: var(--teal-dk);
            padding: 6px 14px; border-radius: 30px;
            font-size: 13px; font-weight: 600;
        }
        .avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dk));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 14px;
        }

        /* ── MAIN ── */
        .main {
            margin-left: var(--sidebar-w);
            padding-top: 64px;
            min-height: 100vh;
        }
        .content { padding: 28px; }

        /* ── TAB PANES ── */
        .tab-pane { display: none; }
        .tab-pane.active { display: block; animation: fadeUp .3s ease; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 18px; margin-bottom: 28px;
        }
        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 22px;
            border: 1px solid var(--border);
            position: relative; overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,.08); }
        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0;
            width: 4px; height: 100%;
        }
        .stat-card.teal::before  { background: var(--teal); }
        .stat-card.green::before { background: var(--green); }
        .stat-card.amber::before { background: var(--amber); }
        .stat-card.red::before   { background: var(--red); }
        .stat-card.navy::before  { background: var(--navy); }
        .stat-card.purple::before{ background: #8b5cf6; }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-bottom: 14px;
        }
        .stat-card.teal   .stat-icon { background: var(--teal-lt);         color: var(--teal); }
        .stat-card.green  .stat-icon { background: #d1fae5;                 color: var(--green); }
        .stat-card.amber  .stat-icon { background: #fef3c7;                 color: var(--amber); }
        .stat-card.red    .stat-icon { background: #fee2e2;                 color: var(--red); }
        .stat-card.navy   .stat-icon { background: #e0e7ff;                 color: #4338ca; }
        .stat-card.purple .stat-icon { background: #ede9fe;                 color: #7c3aed; }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 30px; font-weight: 800;
            color: var(--navy); line-height: 1;
        }
        .stat-label { font-size: 13px; color: var(--muted); margin-top: 4px; }

        /* ── QUICK ACTIONS ── */
        .quick-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px; margin-bottom: 28px;
        }
        .quick-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            cursor: pointer; text-decoration: none; color: var(--navy);
            transition: all .2s;
        }
        .quick-card:hover {
            border-color: var(--teal); color: var(--teal);
            box-shadow: 0 4px 20px rgba(13,148,136,.1);
            transform: translateY(-2px);
        }
        .quick-card-icon {
            width: 42px; height: 42px;
            background: var(--teal-lt); color: var(--teal);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;
        }
        .quick-card-text strong { font-size: 14px; font-weight: 600; display: block; }
        .quick-card-text span   { font-size: 12px; color: var(--muted); }

        /* ── SECTION HEADER ── */
        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .section-header h2 {
            font-family: 'Syne', sans-serif;
            font-size: 18px; font-weight: 700;
        }

        /* ── CARD ── */
        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }
        .card-header-custom {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .card-header-custom h3 {
            font-family: 'Syne', sans-serif;
            font-size: 16px; font-weight: 700; flex: 1;
        }
        .card-body-custom { padding: 20px; }

        /* ── TABLE ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: var(--bg);
            padding: 12px 14px;
            font-size: 12px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .6px;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody td {
            padding: 13px 14px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: #f8fafc; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 12px; font-weight: 600;
        }
        .status-active   { background: #d1fae5; color: #065f46; }
        .status-cancel-p { background: #fee2e2; color: #991b1b; }
        .status-cancel-d { background: #fef3c7; color: #92400e; }

        /* ── FORM ── */
        .form-section {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            max-width: 560px;
        }
        .form-section h3 {
            font-family: 'Syne', sans-serif;
            font-size: 18px; font-weight: 700;
            margin-bottom: 22px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }
        .form-label { font-size: 13px; font-weight: 600; color: var(--slate); margin-bottom: 6px; }
        .form-control {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px; width: 100%;
            transition: border-color .2s;
            outline: none;
        }
        .form-control:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,148,136,.1); }
        .form-group-row { margin-bottom: 16px; }
        .btn-primary-custom {
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px 24px;
            font-size: 14px; font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .15s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary-custom:hover { background: var(--teal-dk); transform: translateY(-1px); }
        .btn-danger-custom {
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px 24px;
            font-size: 14px; font-weight: 600;
            cursor: pointer;
            transition: background .2s;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-danger-custom:hover { background: #dc2626; }

        .password-match { font-size: 12px; font-weight: 600; margin-top: 4px; }
        .match-ok  { color: var(--green); }
        .match-err { color: var(--red); }

        /* ── SEARCH BAR ── */
        .search-bar {
            display: flex; gap: 10px; margin-bottom: 16px;
        }
        .search-bar input {
            flex: 1;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 14px; outline: none;
            transition: border-color .2s;
        }
        .search-bar input:focus { border-color: var(--teal); }
        .search-bar button {
            background: var(--teal); color: #fff;
            border: none; border-radius: 10px;
            padding: 9px 18px; font-size: 14px;
            cursor: pointer; font-weight: 600;
        }

        /* ── TOAST ── */
        .toast-container {
            position: fixed; top: 20px; right: 20px;
            z-index: 9999; display: flex; flex-direction: column; gap: 8px;
        }
        .toast-msg {
            background: var(--navy); color: #fff;
            padding: 12px 18px; border-radius: 12px;
            font-size: 14px; display: flex; align-items: center; gap: 10px;
            box-shadow: 0 8px 30px rgba(0,0,0,.2);
            animation: slideIn .3s ease;
        }
        .toast-msg.success { border-left: 4px solid var(--green); }
        .toast-msg.error   { border-left: 4px solid var(--red); }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .topbar { left: 0; }
        }
    </style>
</head>
<body>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toastContainer"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-hospital"></i></div>
        <h1>Afya <span>One</span></h1>
        <p>Hospital Management System</p>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <div class="nav-item">
            <a class="nav-link active" onclick="showTab('dash')" id="nav-dash">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
        </div>

        <div class="nav-section-label" style="margin-top:8px;">Management</div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('doctors')" id="nav-doctors">
                <i class="fa-solid fa-user-doctor"></i> Doctor List
                <span class="badge-pill"><?= $totalDoctors ?></span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('patients')" id="nav-patients">
                <i class="fa-solid fa-users"></i> Patient List
                <span class="badge-pill"><?= $totalPatients ?></span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('appointments')" id="nav-appointments">
                <i class="fa-solid fa-calendar-check"></i> Appointments
                <span class="badge-pill"><?= $totalAppointments ?></span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('prescriptions')" id="nav-prescriptions">
                <i class="fa-solid fa-file-medical"></i> Prescriptions
                <span class="badge-pill"><?= $totalPrescriptions ?></span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('messages')" id="nav-messages">
                <i class="fa-solid fa-envelope"></i> Queries
                <span class="badge-pill"><?= $totalMessages ?></span>
            </a>
        </div>

        <div class="nav-section-label" style="margin-top:8px;">Finance</div>
        <div class="nav-item">
            <a class="nav-link" href="payment-status.php" id="nav-payments">
                <i class="fa-solid fa-credit-card"></i> Payment Status
                <?php
                    $pendingCount = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE payment_status='Pending' AND userStatus=1 AND doctorStatus=1"))[0] ?? 0;
                    if ($pendingCount > 0): ?>
                        <span class="badge-pill" style="background:#f59e0b;color:#fff"><?= $pendingCount ?></span>
                <?php endif; ?>
            </a>
        </div>

        <div class="nav-section-label" style="margin-top:8px;">Doctors</div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('adddoc')" id="nav-adddoc">
                <i class="fa-solid fa-user-plus"></i> Add Doctor
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('deldoc')" id="nav-deldoc">
                <i class="fa-solid fa-user-minus"></i> Remove Doctor
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="logout1.php">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
        </a>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <button onclick="document.getElementById('sidebar').classList.toggle('open')"
        style="background:none;border:none;font-size:20px;color:var(--muted);cursor:pointer;display:none;" id="menuBtn">
        <i class="fa-solid fa-bars"></i>
    </button>
    <div class="topbar-title" id="topbarTitle">Dashboard <span>Overview</span></div>
    <div class="topbar-badge"><i class="fa-solid fa-circle" style="font-size:8px;color:var(--green)"></i> System Online</div>
    <div class="avatar">AD</div>
</div>

<!-- MAIN -->
<div class="main">
<div class="content">

    <!-- ═══════════ DASHBOARD ═══════════ -->
    <div class="tab-pane active" id="tab-dash">
        <div class="stats-grid">
            <div class="stat-card teal">
                <div class="stat-icon"><i class="fa-solid fa-user-doctor"></i></div>
                <div class="stat-num"><?= $totalDoctors ?></div>
                <div class="stat-label">Total Doctors</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-num"><?= $totalPatients ?></div>
                <div class="stat-label">Registered Patients</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-num"><?= $totalAppointments ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
            <div class="stat-card navy">
                <div class="stat-icon"><i class="fa-solid fa-calendar-day"></i></div>
                <div class="stat-num"><?= $activeAppointments ?></div>
                <div class="stat-label">Active Appointments</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon"><i class="fa-solid fa-file-medical"></i></div>
                <div class="stat-num"><?= $totalPrescriptions ?></div>
                <div class="stat-label">Prescriptions</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-envelope"></i></div>
                <div class="stat-num"><?= $totalMessages ?></div>
                <div class="stat-label">Queries</div>
            </div>
        </div>

        <div class="section-header"><h2>Quick Actions</h2></div>
        <div class="quick-grid">
            <a class="quick-card" onclick="showTab('adddoc')">
                <div class="quick-card-icon"><i class="fa-solid fa-user-plus"></i></div>
                <div class="quick-card-text">
                    <strong>Add Doctor</strong>
                    <span>Register a new doctor</span>
                </div>
            </a>
            <a class="quick-card" onclick="showTab('doctors')">
                <div class="quick-card-icon"><i class="fa-solid fa-user-doctor"></i></div>
                <div class="quick-card-text">
                    <strong>View Doctors</strong>
                    <span><?= $totalDoctors ?> doctors on record</span>
                </div>
            </a>
            <a class="quick-card" onclick="showTab('patients')">
                <div class="quick-card-icon"><i class="fa-solid fa-users"></i></div>
                <div class="quick-card-text">
                    <strong>View Patients</strong>
                    <span><?= $totalPatients ?> patients registered</span>
                </div>
            </a>
            <a class="quick-card" onclick="showTab('appointments')">
                <div class="quick-card-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="quick-card-text">
                    <strong>Appointments</strong>
                    <span><?= $activeAppointments ?> active today</span>
                </div>
            </a>
            <a class="quick-card" onclick="showTab('messages')">
                <div class="quick-card-icon"><i class="fa-solid fa-envelope"></i></div>
                <div class="quick-card-text">
                    <strong>Patient Queries</strong>
                    <span><?= $totalMessages ?> messages received</span>
                </div>
            </a>
            <a class="quick-card" onclick="showTab('prescriptions')">
                <div class="quick-card-icon"><i class="fa-solid fa-file-medical"></i></div>
                <div class="quick-card-text">
                    <strong>Prescriptions</strong>
                    <span><?= $totalPrescriptions ?> on record</span>
                </div>
            </a>
            <a class="quick-card" href="payment-status.php">
                <div class="quick-card-icon" style="background:#fef3c7;color:#d97706"><i class="fa-solid fa-credit-card"></i></div>
                <div class="quick-card-text">
                    <strong>Payment Status</strong>
                    <span>Track paid &amp; pending bills</span>
                </div>
            </a>
        </div>

        <!-- Recent Appointments -->
        <div class="section-header"><h2>Recent Appointments</h2></div>
        <div class="card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Patient</th><th>Doctor</th>
                            <th>Date</th><th>Time</th><th>Fees</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $rq = "SELECT * FROM appointmenttb ORDER BY ID DESC LIMIT 8";
                    $rr = mysqli_query($con, $rq);
                    while ($row = mysqli_fetch_array($rr)):
                        if ($row['userStatus']==1 && $row['doctorStatus']==1) {
                            $badge = '<span class="status-badge status-active"><i class="fa-solid fa-circle" style="font-size:7px"></i> Active</span>';
                        } elseif ($row['userStatus']==0) {
                            $badge = '<span class="status-badge status-cancel-p"><i class="fa-solid fa-xmark"></i> Cancelled by Patient</span>';
                        } else {
                            $badge = '<span class="status-badge status-cancel-d"><i class="fa-solid fa-xmark"></i> Cancelled by Doctor</span>';
                        }
                    ?>
                    <tr>
                        <td><strong>#<?= $row['ID'] ?></strong></td>
                        <td><?= $row['fname'].' '.$row['lname'] ?></td>
                        <td><?= $row['doctor'] ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= $row['apptime'] ?></td>
                        <td>KES <?= number_format($row['docFees']) ?></td>
                        <td><?= $badge ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ DOCTORS ═══════════ -->
    <div class="tab-pane" id="tab-doctors">
        <div class="section-header">
            <h2>Doctor List</h2>
        </div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-user-doctor" style="color:var(--teal)"></i>
                <h3>All Doctors</h3>
                <form action="doctorsearch.php" method="post" style="display:flex;gap:8px;margin:0">
                    <input type="text" name="doctor_contact" placeholder="Search by email…"
                        style="border:1.5px solid var(--border);border-radius:8px;padding:7px 12px;font-size:13px;outline:none">
                    <button type="submit" name="doctor_search_submit"
                        style="background:var(--teal);color:#fff;border:none;border-radius:8px;padding:7px 14px;cursor:pointer;font-size:13px">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Name</th><th>Specialization</th><th>Email</th><th>Fees (KES)</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $q = "select * from doctb";
                    $r = mysqli_query($con,$q);
                    while ($row = mysqli_fetch_array($r)):
                    ?>
                    <tr>
                        <td><strong><?= $row['username'] ?></strong></td>
                        <td>
                            <span class="status-badge" style="background:#e0f2fe;color:#0369a1">
                                <?= $row['spec'] ?>
                            </span>
                        </td>
                        <td><?= $row['email'] ?></td>
                        <td>KES <?= number_format($row['docFees']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ PATIENTS ═══════════ -->
    <div class="tab-pane" id="tab-patients">
        <div class="section-header"><h2>Patient List</h2></div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-users" style="color:var(--teal)"></i>
                <h3>Registered Patients</h3>
                <form action="patientsearch.php" method="post" style="display:flex;gap:8px;margin:0">
                    <input type="text" name="patient_contact" placeholder="Search by contact…"
                        style="border:1.5px solid var(--border);border-radius:8px;padding:7px 12px;font-size:13px;outline:none">
                    <button type="submit" name="patient_search_submit"
                        style="background:var(--teal);color:#fff;border:none;border-radius:8px;padding:7px 14px;cursor:pointer;font-size:13px">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Gender</th><th>Email</th><th>Contact</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $q = "select * from patreg";
                    $r = mysqli_query($con,$q);
                    while ($row = mysqli_fetch_array($r)):
                    ?>
                    <tr>
                        <td><strong>#<?= $row['pid'] ?></strong></td>
                        <td><?= $row['fname'] ?></td>
                        <td><?= $row['lname'] ?></td>
                        <td><?= $row['gender'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['contact'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ APPOINTMENTS ═══════════ -->
    <div class="tab-pane" id="tab-appointments">
        <div class="section-header"><h2>Appointment Details</h2></div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-calendar-check" style="color:var(--teal)"></i>
                <h3>All Appointments</h3>
                <form action="appsearch.php" method="post" style="display:flex;gap:8px;margin:0">
                    <input type="text" name="app_contact" placeholder="Search by contact…"
                        style="border:1.5px solid var(--border);border-radius:8px;padding:7px 12px;font-size:13px;outline:none">
                    <button type="submit" name="app_search_submit"
                        style="background:var(--teal);color:#fff;border:none;border-radius:8px;padding:7px 14px;cursor:pointer;font-size:13px">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Appt ID</th><th>Patient</th><th>Gender</th>
                            <th>Contact</th><th>Doctor</th><th>Fees</th>
                            <th>Date</th><th>Time</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $q = "select * from appointmenttb ORDER BY ID DESC";
                    $r = mysqli_query($con,$q);
                    while ($row = mysqli_fetch_array($r)):
                        if ($row['userStatus']==1 && $row['doctorStatus']==1) {
                            $badge = '<span class="status-badge status-active"><i class="fa-solid fa-circle" style="font-size:7px"></i> Active</span>';
                        } elseif ($row['userStatus']==0) {
                            $badge = '<span class="status-badge status-cancel-p">Cancelled by Patient</span>';
                        } else {
                            $badge = '<span class="status-badge status-cancel-d">Cancelled by Doctor</span>';
                        }
                    ?>
                    <tr>
                        <td><strong>#<?= $row['ID'] ?></strong></td>
                        <td><?= $row['fname'].' '.$row['lname'] ?></td>
                        <td><?= $row['gender'] ?></td>
                        <td><?= $row['contact'] ?></td>
                        <td><?= $row['doctor'] ?></td>
                        <td>KES <?= number_format($row['docFees']) ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= $row['apptime'] ?></td>
                        <td><?= $badge ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ PRESCRIPTIONS ═══════════ -->
    <div class="tab-pane" id="tab-prescriptions">
        <div class="section-header"><h2>Prescription List</h2></div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-file-medical" style="color:var(--teal)"></i>
                <h3>All Prescriptions</h3>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Doctor</th><th>Patient ID</th><th>Appt ID</th>
                            <th>Patient Name</th><th>Date</th><th>Time</th>
                            <th>Disease</th><th>Allergy</th><th>Prescription</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $q = "select * from prestb";
                    $r = mysqli_query($con,$q);
                    while ($row = mysqli_fetch_array($r)):
                    ?>
                    <tr>
                        <td><?= $row['doctor'] ?></td>
                        <td>#<?= $row['pid'] ?></td>
                        <td>#<?= $row['ID'] ?></td>
                        <td><?= $row['fname'].' '.$row['lname'] ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= $row['apptime'] ?></td>
                        <td><span class="status-badge" style="background:#fef3c7;color:#92400e"><?= $row['disease'] ?></span></td>
                        <td><?= $row['allergy'] ?></td>
                        <td><?= $row['prescription'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ MESSAGES ═══════════ -->
    <div class="tab-pane" id="tab-messages">
        <div class="section-header"><h2>Patient Queries</h2></div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-envelope" style="color:var(--teal)"></i>
                <h3>Contact Messages</h3>
                <form action="messearch.php" method="post" style="display:flex;gap:8px;margin:0">
                    <input type="text" name="mes_contact" placeholder="Search by contact…"
                        style="border:1.5px solid var(--border);border-radius:8px;padding:7px 12px;font-size:13px;outline:none">
                    <button type="submit" name="mes_search_submit"
                        style="background:var(--teal);color:#fff;border:none;border-radius:8px;padding:7px 14px;cursor:pointer;font-size:13px">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Contact</th><th>Message</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $q = "select * from contact";
                    $r = mysqli_query($con,$q);
                    while ($row = mysqli_fetch_array($r)):
                    ?>
                    <tr>
                        <td><strong><?= $row['name'] ?></strong></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['contact'] ?></td>
                        <td style="max-width:300px"><?= $row['message'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ ADD DOCTOR ═══════════ -->
    <div class="tab-pane" id="tab-adddoc">
        <div class="section-header"><h2>Add New Doctor</h2></div>
        <div class="form-section">
            <h3><i class="fa-solid fa-user-plus" style="color:var(--teal);margin-right:8px"></i>Doctor Registration</h3>
            <form method="post" action="admin-panel1.php">
                <div class="form-group-row">
                    <label class="form-label">Doctor Name</label>
                    <input type="text" class="form-control" name="doctor" onkeydown="return alphaOnly(event);" required placeholder="Full name">
                </div>
                <div class="form-group-row">
                    <label class="form-label">Specialization</label>
                    <select name="special" class="form-control" required>
                        <option value="" disabled selected>Select Specialization</option>
                        <option value="General">General</option>
                        <option value="Cardiologist">Cardiologist</option>
                        <option value="Neurologist">Neurologist</option>
                        <option value="Pediatrician">Pediatrician</option>
                        <option value="Dermatologist">Dermatologist</option>
                        <option value="Orthopedic">Orthopedic</option>
                        <option value="Gynecologist">Gynecologist</option>
                        <option value="Psychiatrist">Psychiatrist</option>
                    </select>
                </div>
                <div class="form-group-row">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="demail" required placeholder="doctor@afyaone.com">
                </div>
                <div class="form-group-row">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="dpassword" id="dpassword" onkeyup="checkPass()" required>
                </div>
                <div class="form-group-row">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="cdpassword" id="cdpassword" onkeyup="checkPass()" required>
                    <div class="password-match" id="passMsg"></div>
                </div>
                <div class="form-group-row">
                    <label class="form-label">Consultancy Fees (KES)</label>
                    <input type="number" class="form-control" name="docFees" required placeholder="e.g. 1500">
                </div>
                <button type="submit" name="docsub" class="btn-primary-custom">
                    <i class="fa-solid fa-user-plus"></i> Add Doctor
                </button>
            </form>
        </div>
    </div>

    <!-- ═══════════ DELETE DOCTOR ═══════════ -->
    <div class="tab-pane" id="tab-deldoc">
        <div class="section-header"><h2>Remove Doctor</h2></div>
        <div class="form-section">
            <h3><i class="fa-solid fa-user-minus" style="color:var(--red);margin-right:8px"></i>Delete Doctor Account</h3>
            <p style="font-size:14px;color:var(--muted);margin-bottom:20px">
                Enter the email address of the doctor you wish to remove from the system. This action cannot be undone.
            </p>
            <form method="post" action="admin-panel1.php" onsubmit="return confirm('Are you sure you want to delete this doctor? This cannot be undone.')">
                <div class="form-group-row">
                    <label class="form-label">Doctor Email Address</label>
                    <input type="email" class="form-control" name="demail" required placeholder="doctor@afyaone.com">
                </div>
                <button type="submit" name="docsub1" class="btn-danger-custom">
                    <i class="fa-solid fa-trash"></i> Remove Doctor
                </button>
            </form>
        </div>
    </div>

</div><!-- /content -->
</div><!-- /main -->

<script>
    // Tab switching
    function showTab(tab) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        document.getElementById('nav-' + tab).classList.add('active');

        const titles = {
            dash: 'Dashboard <span>Overview</span>',
            doctors: 'Doctor <span>List</span>',
            patients: 'Patient <span>List</span>',
            appointments: 'Appointment <span>Details</span>',
            prescriptions: 'Prescription <span>List</span>',
            messages: 'Patient <span>Queries</span>',
            adddoc: 'Add <span>Doctor</span>',
            deldoc: 'Remove <span>Doctor</span>',
        };
        document.getElementById('topbarTitle').innerHTML = titles[tab] || tab;
    }

    // Password match checker
    function checkPass() {
        const p1 = document.getElementById('dpassword').value;
        const p2 = document.getElementById('cdpassword').value;
        const msg = document.getElementById('passMsg');
        if (!p2) { msg.innerHTML = ''; return; }
        if (p1 === p2) {
            msg.innerHTML = '<i class="fa-solid fa-check"></i> Passwords match';
            msg.className = 'password-match match-ok';
        } else {
            msg.innerHTML = '<i class="fa-solid fa-xmark"></i> Passwords do not match';
            msg.className = 'password-match match-err';
        }
    }

    // Alpha only
    function alphaOnly(event) {
        var key = event.keyCode;
        return ((key >= 65 && key <= 90) || key == 8 || key == 32);
    }

    // Toast notifications
    function showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'toast-msg ' + type;
        toast.innerHTML = `<i class="fa-solid fa-${type==='success'?'circle-check':'circle-xmark'}"></i> ${message}`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // Mobile menu
    window.addEventListener('resize', () => {
        document.getElementById('menuBtn').style.display = window.innerWidth <= 768 ? 'block' : 'none';
    });
    if (window.innerWidth <= 768) document.getElementById('menuBtn').style.display = 'block';
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>