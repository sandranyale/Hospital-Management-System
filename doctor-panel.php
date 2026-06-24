<!DOCTYPE html>
<?php
include('func1.php');
$con = mysqli_connect("localhost", "root", "", "myhmsdb");
$doctor = $_SESSION['dname'];

// Approve appointment
if (isset($_GET['approve'])) {
    $q = mysqli_query($con, "UPDATE appointmenttb SET doctorStatus='1' WHERE ID='" . $_GET['ID'] . "' AND doctor='$doctor'");
    if ($q) echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Appointment approved!','success'); });</script>";
}

// Cancel appointment
if (isset($_GET['cancel'])) {
    $q = mysqli_query($con, "UPDATE appointmenttb SET doctorStatus='2' WHERE ID='" . $_GET['ID'] . "' AND doctor='$doctor'");
    if ($q) echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Appointment cancelled.','warning'); });</script>";
}

// Stats for this doctor
$totalAppointments  = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE doctor='$doctor'"))[0];
$activeAppointments = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE doctor='$doctor' AND userStatus=1 AND doctorStatus=1"))[0];
$pendingAppointments= mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE doctor='$doctor' AND userStatus=1 AND doctorStatus=0"))[0];
$cancelledByDoc     = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE doctor='$doctor' AND doctorStatus=2"))[0];
$totalPrescriptions = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM prestb WHERE doctor='$doctor'"))[0];
$uniquePatients     = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(DISTINCT pid) FROM appointmenttb WHERE doctor='$doctor'"))[0];

// Doctor's specialization & fees
$docInfo = mysqli_fetch_assoc(mysqli_query($con, "SELECT spec, docFees FROM doctb WHERE username='$doctor'"));
$docSpec  = $docInfo['spec']     ?? 'Specialist';
$docFees  = $docInfo['docFees']  ?? '—';
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Afya One — Dr. <?= htmlspecialchars($doctor) ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
    <style>
        :root {
            --indigo:    #4f46e5;
            --indigo-dk: #4338ca;
            --indigo-lt: #e0e7ff;
            --navy:      #0f172a;
            --slate:     #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --bg:        #f1f5f9;
            --white:     #ffffff;
            --red:       #ef4444;
            --amber:     #f59e0b;
            --green:     #10b981;
            --teal:      #0d9488;
            --sidebar-w: 265px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--navy); min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--navy);
            display: flex; flex-direction: column;
            z-index: 100; transition: transform .3s;
        }
        .sidebar-brand { padding: 26px 22px 18px; border-bottom: 1px solid rgba(255,255,255,.08); }
        .brand-icon {
            width: 42px; height: 42px; background: var(--indigo);
            border-radius: 11px; display: flex; align-items: center;
            justify-content: center; font-size: 18px; color: #fff; margin-bottom: 10px;
        }
        .sidebar-brand h1 { font-family:'Syne',sans-serif; font-size:17px; font-weight:800; color:#fff; line-height:1.2; }
        .sidebar-brand h1 span { color: var(--indigo); }
        /* Doctor profile card in sidebar */
        .doc-profile {
            margin: 14px 14px 0;
            background: rgba(255,255,255,.06);
            border-radius: 12px; padding: 14px;
            display: flex; align-items: center; gap: 12px;
        }
        .doc-avatar {
            width: 46px; height: 46px; border-radius: 50%;
            background: linear-gradient(135deg, var(--indigo), #818cf8);
            display: flex; align-items: center; justify-content: center;
            font-family:'Syne',sans-serif; font-size:16px; font-weight:800; color:#fff;
            flex-shrink: 0;
        }
        .doc-info h4 { font-size:13px; font-weight:700; color:#fff; }
        .doc-info span { font-size:11px; color:rgba(255,255,255,.45); }
        .doc-fee-badge {
            margin: 10px 14px 0;
            background: rgba(79,70,229,.2);
            border: 1px solid rgba(79,70,229,.3);
            border-radius: 8px; padding: 8px 12px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .doc-fee-badge span { font-size:12px; color:rgba(255,255,255,.5); }
        .doc-fee-badge strong { font-size:13px; color:#a5b4fc; font-weight:700; }

        .sidebar-nav { flex:1; padding:14px 12px; overflow-y:auto; }
        .nav-section-label {
            font-size:10px; font-weight:600; color:rgba(255,255,255,.25);
            text-transform:uppercase; letter-spacing:1.2px; padding:10px 12px 5px;
        }
        .nav-item { margin-bottom:2px; }
        .nav-link {
            display:flex; align-items:center; gap:12px; padding:11px 14px;
            border-radius:10px; color:rgba(255,255,255,.5);
            font-size:14px; font-weight:500; text-decoration:none; cursor:pointer;
            transition: all .2s;
        }
        .nav-link i { width:18px; font-size:15px; }
        .nav-link:hover { background:rgba(255,255,255,.07); color:#fff; }
        .nav-link.active { background:var(--indigo); color:#fff; }
        .nav-link .badge-pill {
            margin-left:auto; background:rgba(255,255,255,.15);
            color:#fff; font-size:11px; padding:2px 7px; border-radius:20px;
        }
        .nav-link.active .badge-pill { background:rgba(255,255,255,.25); }
        .sidebar-footer { padding:14px 12px; border-top:1px solid rgba(255,255,255,.08); }
        .sidebar-footer a {
            display:flex; align-items:center; gap:10px; padding:10px 14px;
            border-radius:10px; color:rgba(255,255,255,.45); font-size:13px; text-decoration:none; transition:all .2s;
        }
        .sidebar-footer a:hover { background:rgba(239,68,68,.15); color:#f87171; }

        /* ── TOPBAR ── */
        .topbar {
            position:fixed; top:0; left:var(--sidebar-w); right:0; height:64px;
            background:var(--white); border-bottom:1px solid var(--border);
            display:flex; align-items:center; padding:0 28px; z-index:99; gap:16px;
        }
        .topbar-title { font-family:'Syne',sans-serif; font-size:20px; font-weight:700; color:var(--navy); flex:1; }
        .topbar-title span { color:var(--indigo); }
        .topbar-badge {
            display:flex; align-items:center; gap:8px;
            background:var(--indigo-lt); color:var(--indigo-dk);
            padding:6px 14px; border-radius:30px; font-size:13px; font-weight:600;
        }
        .today-date { font-size:13px; color:var(--muted); }

        /* ── MAIN ── */
        .main { margin-left:var(--sidebar-w); padding-top:64px; min-height:100vh; }
        .content { padding:28px; }
        .tab-pane { display:none; }
        .tab-pane.active { display:block; animation:fadeUp .3s ease; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        /* ── STAT CARDS ── */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:16px; margin-bottom:26px; }
        .stat-card {
            background:var(--white); border-radius:16px; padding:20px;
            border:1px solid var(--border); position:relative; overflow:hidden;
            transition:transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 30px rgba(0,0,0,.08); }
        .stat-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; }
        .stat-card.indigo::before { background:var(--indigo); }
        .stat-card.green::before  { background:var(--green); }
        .stat-card.amber::before  { background:var(--amber); }
        .stat-card.red::before    { background:var(--red); }
        .stat-card.teal::before   { background:var(--teal); }
        .stat-icon {
            width:42px; height:42px; border-radius:11px;
            display:flex; align-items:center; justify-content:center;
            font-size:17px; margin-bottom:14px;
        }
        .stat-card.indigo .stat-icon { background:var(--indigo-lt);  color:var(--indigo); }
        .stat-card.green  .stat-icon { background:#d1fae5;            color:var(--green); }
        .stat-card.amber  .stat-icon { background:#fef3c7;            color:var(--amber); }
        .stat-card.red    .stat-icon { background:#fee2e2;            color:var(--red); }
        .stat-card.teal   .stat-icon { background:#ccfbf1;            color:var(--teal); }
        .stat-num { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; color:var(--navy); line-height:1; }
        .stat-label { font-size:13px; color:var(--muted); margin-top:4px; }

        /* ── QUICK ACTIONS ── */
        .quick-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:14px; margin-bottom:26px; }
        .quick-card {
            background:var(--white); border:1px solid var(--border); border-radius:14px;
            padding:18px 20px; display:flex; align-items:center; gap:14px;
            cursor:pointer; text-decoration:none; color:var(--navy); transition:all .2s;
        }
        .quick-card:hover { border-color:var(--indigo); color:var(--indigo); box-shadow:0 4px 20px rgba(79,70,229,.1); transform:translateY(-2px); }
        .quick-card-icon { width:42px; height:42px; background:var(--indigo-lt); color:var(--indigo); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0; }
        .quick-card-text strong { font-size:14px; font-weight:600; display:block; }
        .quick-card-text span { font-size:12px; color:var(--muted); }

        /* ── SECTION HEADER ── */
        .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .section-header h2 { font-family:'Syne',sans-serif; font-size:18px; font-weight:700; }

        /* ── CARD ── */
        .card { background:var(--white); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .card-header-custom {
            padding:16px 20px; border-bottom:1px solid var(--border);
            display:flex; align-items:center; gap:10px; flex-wrap:wrap;
        }
        .card-header-custom h3 { font-family:'Syne',sans-serif; font-size:16px; font-weight:700; flex:1; }

        /* ── TABLE ── */
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        thead th {
            background:var(--bg); padding:11px 14px;
            font-size:11px; font-weight:600; text-transform:uppercase;
            letter-spacing:.6px; color:var(--muted); border-bottom:1px solid var(--border); white-space:nowrap;
        }
        tbody td { padding:13px 14px; font-size:14px; border-bottom:1px solid var(--border); }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover { background:#f8fafc; }

        .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .status-active    { background:#d1fae5; color:#065f46; }
        .status-cancel-p  { background:#fee2e2; color:#991b1b; }
        .status-cancel-d  { background:#fef3c7; color:#92400e; }

        /* ── ACTION BUTTONS ── */
        .btn-danger-sm {
            background:var(--red); color:#fff; border:none; border-radius:8px;
            padding:6px 12px; font-size:12px; font-weight:600; cursor:pointer;
            display:inline-flex; align-items:center; gap:5px; transition:background .2s;
            text-decoration:none;
        }
        .btn-danger-sm:hover { background:#dc2626; color:#fff; }
        .btn-success-sm {
            background:var(--green); color:#fff; border:none; border-radius:8px;
            padding:6px 12px; font-size:12px; font-weight:600; cursor:pointer;
            display:inline-flex; align-items:center; gap:5px; transition:background .2s;
            text-decoration:none;
        }
        .btn-success-sm:hover { background:#059669; color:#fff; }

        /* ── AVAILABILITY TOGGLE ── */
        .availability-card {
            background:var(--white); border:1px solid var(--border); border-radius:16px;
            padding:24px; margin-bottom:26px;
            display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;
        }
        .availability-info h3 { font-family:'Syne',sans-serif; font-size:16px; font-weight:700; margin-bottom:4px; }
        .availability-info p { font-size:13px; color:var(--muted); }
        .toggle-wrap { display:flex; align-items:center; gap:12px; }
        .toggle-label { font-size:14px; font-weight:600; color:var(--navy); }
        .toggle {
            position:relative; width:52px; height:28px;
            background:#e2e8f0; border-radius:14px; cursor:pointer; transition:background .3s;
        }
        .toggle.on { background:var(--green); }
        .toggle::after {
            content:''; position:absolute; top:3px; left:3px;
            width:22px; height:22px; background:#fff; border-radius:50%;
            transition:transform .3s; box-shadow:0 1px 4px rgba(0,0,0,.2);
        }
        .toggle.on::after { transform:translateX(24px); }

        /* ── TOAST ── */
        .toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
        .toast-msg {
            background:var(--navy); color:#fff; padding:12px 18px; border-radius:12px;
            font-size:14px; display:flex; align-items:center; gap:10px;
            box-shadow:0 8px 30px rgba(0,0,0,.2); animation:slideIn .3s ease;
        }
        .toast-msg.success { border-left:4px solid var(--green); }
        .toast-msg.warning { border-left:4px solid var(--amber); }
        .toast-msg.error   { border-left:4px solid var(--red); }
        @keyframes slideIn { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }

        /* ── TODAY'S APPOINTMENTS HIGHLIGHT ── */
        .today-row { background:#fafaf7 !important; }
        .today-pill { background:#fef3c7; color:#92400e; font-size:11px; padding:2px 7px; border-radius:10px; font-weight:600; margin-left:6px; }

        @media(max-width:768px){
            .sidebar { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main { margin-left:0; }
            .topbar { left:0; }
        }
    </style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-stethoscope"></i></div>
        <h1>Afya <span>One</span></h1>
    </div>

    <!-- Doctor profile in sidebar -->
    <div class="doc-profile">
        <div class="doc-avatar"><?= strtoupper(substr($doctor,0,1)) ?></div>
        <div class="doc-info">
            <h4>Dr. <?= htmlspecialchars($doctor) ?></h4>
            <span><?= htmlspecialchars($docSpec) ?></span>
        </div>
    </div>
    <div class="doc-fee-badge">
        <span>Consultation Fee</span>
        <strong>KES <?= number_format($docFees) ?></strong>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label" style="margin-top:10px">Overview</div>
        <div class="nav-item">
            <a class="nav-link active" onclick="showTab('dash')" id="nav-dash">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
        </div>
        <div class="nav-section-label" style="margin-top:8px">My Practice</div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('appointments')" id="nav-appointments">
                <i class="fa-solid fa-calendar-check"></i> Appointments
                <span class="badge-pill"><?= $totalAppointments ?></span>
            </a>
        </div>
        <?php if ($pendingAppointments > 0): ?>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('appointments')" style="color:#fbbf24">
                <i class="fa-solid fa-clock"></i> Pending Approval
                <span class="badge-pill" style="background:#f59e0b"><?= $pendingAppointments ?></span>
            </a>
        </div>
        <?php endif; ?>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('prescriptions')" id="nav-prescriptions">
                <i class="fa-solid fa-file-medical"></i> Prescriptions
                <span class="badge-pill"><?= $totalPrescriptions ?></span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="logout1.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-title" id="topbarTitle">Dashboard <span>Overview</span></div>
    <div class="today-date"><i class="fa-regular fa-calendar" style="margin-right:5px"></i><?= date('l, F j, Y') ?></div>
    <div class="topbar-badge"><i class="fa-solid fa-circle" style="font-size:8px;color:var(--green)"></i> On Duty</div>
</div>

<!-- MAIN -->
<div class="main">
<div class="content">

    <!-- ═══════════ DASHBOARD ═══════════ -->
    <div class="tab-pane active" id="tab-dash">

        <div class="stats-grid">
            <div class="stat-card indigo">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-num"><?= $totalAppointments ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-num"><?= $activeAppointments ?></div>
                <div class="stat-label">Approved</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-num"><?= $pendingAppointments ?></div>
                <div class="stat-label">Pending Approval</div>
            </div>
            <div class="stat-card teal">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-num"><?= $uniquePatients ?></div>
                <div class="stat-label">Unique Patients</div>
            </div>
            <div class="stat-card purple" style="--purple:#8b5cf6">
                <div class="stat-icon" style="background:#ede9fe;color:#7c3aed"><i class="fa-solid fa-file-medical"></i></div>
                <div class="stat-num"><?= $totalPrescriptions ?></div>
                <div class="stat-label">Prescriptions Issued</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                <div class="stat-num"><?= $cancelledByDoc ?></div>
                <div class="stat-label">Cancelled by You</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-header"><h2>Quick Actions</h2></div>
        <div class="quick-grid">
            <a class="quick-card" onclick="showTab('appointments')">
                <div class="quick-card-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="quick-card-text">
                    <strong>View Appointments</strong>
                    <span><?= $activeAppointments ?> active now</span>
                </div>
            </a>
            <a class="quick-card" onclick="showTab('prescriptions')">
                <div class="quick-card-icon"><i class="fa-solid fa-file-medical"></i></div>
                <div class="quick-card-text">
                    <strong>Prescription List</strong>
                    <span><?= $totalPrescriptions ?> issued</span>
                </div>
            </a>
        </div>

        <!-- Today's Appointments -->
        <div class="section-header"><h2>Today's Appointments</h2></div>
        <div class="card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Patient</th><th>Gender</th><th>Contact</th><th>Time</th><th>Status</th><th>Action</th><th>Prescribe</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $today = date('Y-m-d');
                    $tq = "SELECT * FROM appointmenttb WHERE doctor='$doctor' AND appdate='$today' ORDER BY apptime ASC";
                    $tr = mysqli_query($con, $tq);
                    $cnt = 0;
                    while ($row = mysqli_fetch_array($tr)):
                        $cnt++;
                        if ($row['userStatus']==1 && $row['doctorStatus']==1) {
                            $badge = '<span class="status-badge status-active"><i class="fa-solid fa-circle" style="font-size:7px"></i> Active</span>';
                        } elseif ($row['userStatus']==0) {
                            $badge = '<span class="status-badge status-cancel-p">Cancelled by Patient</span>';
                        } else {
                            $badge = '<span class="status-badge status-cancel-d">Cancelled by You</span>';
                        }
                        $active = ($row['userStatus']==1 && $row['doctorStatus']==1);
                    ?>
                    <tr>
                        <td><strong><?= $row['fname'].' '.$row['lname'] ?></strong><br><span style="font-size:12px;color:var(--muted)">#<?= $row['pid'] ?></span></td>
                        <td><?= $row['gender'] ?></td>
                        <td><?= $row['contact'] ?></td>
                        <td><strong><?= date('g:i A', strtotime($row['apptime'])) ?></strong></td>
                        <td><?= $badge ?></td>
                        <td>
                            <?php if ($active): ?>
                            <a href="doctor-panel.php?ID=<?= $row['ID'] ?>&cancel=update"
                               onclick="return confirm('Cancel this appointment?')"
                               class="btn-danger-sm"><i class="fa-solid fa-xmark"></i> Cancel</a>
                            <?php else: ?><span style="color:var(--muted);font-size:13px">—</span><?php endif; ?>
                        </td>
                        <td>
                            <?php if ($active): ?>
                            <a href="prescribe.php?pid=<?= $row['pid'] ?>&ID=<?= $row['ID'] ?>&fname=<?= urlencode($row['fname']) ?>&lname=<?= urlencode($row['lname']) ?>&appdate=<?= $row['appdate'] ?>&apptime=<?= $row['apptime'] ?>"
                               class="btn-success-sm"><i class="fa-solid fa-pen-to-square"></i> Prescribe</a>
                            <?php else: ?><span style="color:var(--muted);font-size:13px">—</span><?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile;
                    if ($cnt === 0): ?>
                    <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--muted)">
                        <i class="fa-regular fa-calendar-xmark" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        No appointments scheduled for today
                    </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ APPOINTMENTS ═══════════ -->
    <div class="tab-pane" id="tab-appointments">
        <div class="section-header"><h2>All Appointments</h2></div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-calendar-check" style="color:var(--indigo)"></i>
                <h3>My Appointment List</h3>
                <span style="font-size:13px;color:var(--muted)"><?= $totalAppointments ?> total &bull; <?= $activeAppointments ?> active</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Appt ID</th><th>Patient</th><th>Gender</th>
                            <th>Email</th><th>Contact</th>
                            <th>Date</th><th>Time</th><th>Status</th>
                            <th>Cancel</th><th>Prescribe</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $aq = "SELECT * FROM appointmenttb WHERE doctor='$doctor' ORDER BY appdate DESC, apptime ASC";
                    $ar = mysqli_query($con, $aq);
                    while ($row = mysqli_fetch_array($ar)):
                        $isToday = ($row['appdate'] === date('Y-m-d'));
                        $approved  = ($row['userStatus']==1 && $row['doctorStatus']==1);
                        $pending   = ($row['userStatus']==1 && $row['doctorStatus']==0);
                        $cancelledP= ($row['userStatus']==0);
                        $cancelledD= ($row['doctorStatus']==2);

                        if ($approved)
                            $badge = '<span class="status-badge status-active"><i class="fa-solid fa-circle" style="font-size:7px"></i> Approved</span>';
                        elseif ($pending)
                            $badge = '<span class="status-badge" style="background:#fef3c7;color:#92400e"><i class="fa-solid fa-clock"></i> Pending</span>';
                        elseif ($cancelledP)
                            $badge = '<span class="status-badge status-cancel-p">Patient Cancelled</span>';
                        else
                            $badge = '<span class="status-badge status-cancel-d">You Cancelled</span>';
                    ?>
                    <tr <?= $isToday ? 'class="today-row"' : '' ?>>
                        <td><strong>#<?= $row['ID'] ?></strong></td>
                        <td>
                            <?= $row['fname'].' '.$row['lname'] ?>
                            <?php if ($isToday): ?><span class="today-pill">Today</span><?php endif; ?>
                        </td>
                        <td><?= $row['gender'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['contact'] ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= date('g:i A', strtotime($row['apptime'])) ?></td>
                        <td><?= $badge ?></td>
                        <td>
                            <?php if ($pending): ?>
                                <a href="doctor-panel.php?ID=<?= $row['ID'] ?>&approve=1"
                                   class="btn-success-sm" style="margin-bottom:4px">
                                   <i class="fa-solid fa-check"></i> Approve
                                </a>
                                <a href="doctor-panel.php?ID=<?= $row['ID'] ?>&cancel=1"
                                   onclick="return confirm('Cancel this appointment?')"
                                   class="btn-danger-sm">
                                   <i class="fa-solid fa-xmark"></i> Cancel
                                </a>
                            <?php elseif ($approved): ?>
                                <a href="doctor-panel.php?ID=<?= $row['ID'] ?>&cancel=1"
                                   onclick="return confirm('Cancel this appointment?')"
                                   class="btn-danger-sm">
                                   <i class="fa-solid fa-xmark"></i> Cancel
                                </a>
                            <?php else: ?>
                                <span style="color:var(--muted);font-size:13px">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($approved): ?>
                            <a href="prescribe.php?pid=<?= $row['pid'] ?>&ID=<?= $row['ID'] ?>&fname=<?= urlencode($row['fname']) ?>&lname=<?= urlencode($row['lname']) ?>&appdate=<?= $row['appdate'] ?>&apptime=<?= $row['apptime'] ?>"
                               class="btn-success-sm"><i class="fa-solid fa-pen-to-square"></i> Prescribe</a>
                            <?php else: ?><span style="color:var(--muted);font-size:13px">—</span><?php endif; ?>
                        </td>
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
                <i class="fa-solid fa-file-medical" style="color:var(--indigo)"></i>
                <h3>Prescriptions Issued</h3>
                <span style="font-size:13px;color:var(--muted)"><?= $totalPrescriptions ?> total</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Patient ID</th><th>Patient Name</th><th>Appt ID</th>
                            <th>Date</th><th>Time</th><th>Disease</th><th>Allergy</th><th>Prescription</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $pq = "SELECT * FROM prestb WHERE doctor='$doctor' ORDER BY appdate DESC";
                    $pr = mysqli_query($con, $pq);
                    $pcnt = 0;
                    while ($row = mysqli_fetch_array($pr)):
                        $pcnt++;
                    ?>
                    <tr>
                        <td><strong>#<?= $row['pid'] ?></strong></td>
                        <td><?= $row['fname'].' '.$row['lname'] ?></td>
                        <td>#<?= $row['ID'] ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= date('g:i A', strtotime($row['apptime'])) ?></td>
                        <td><span class="status-badge" style="background:#fef3c7;color:#92400e"><?= $row['disease'] ?></span></td>
                        <td><?= $row['allergy'] ?: '—' ?></td>
                        <td style="max-width:220px"><?= $row['prescription'] ?></td>
                    </tr>
                    <?php endwhile;
                    if ($pcnt === 0): ?>
                    <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--muted)">
                        <i class="fa-regular fa-file" style="font-size:24px;display:block;margin-bottom:8px"></i>
                        No prescriptions issued yet
                    </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div><!-- /content -->
</div><!-- /main -->

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('nav-' + tab).classList.add('active');
    const titles = {
        dash: 'Dashboard <span>Overview</span>',
        appointments: 'My <span>Appointments</span>',
        prescriptions: 'My <span>Prescriptions</span>',
    };
    document.getElementById('topbarTitle').innerHTML = titles[tab] || tab;
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast-msg ' + type;
    const icons = { success:'circle-check', warning:'triangle-exclamation', error:'circle-xmark' };
    toast.innerHTML = `<i class="fa-solid fa-${icons[type]||'circle-check'}"></i> ${message}`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>