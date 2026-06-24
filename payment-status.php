<!DOCTYPE html>
<?php
// payment-status.php — Admin Payment Status Overview
// Include this as a tab in admin-panel1.php OR link to it directly
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

// Handle manual status update by admin
if (isset($_POST['update_payment'])) {
    $appt_id = $_POST['appt_id'];
    $status  = $_POST['status'];
    $query   = mysqli_query($con, "UPDATE appointmenttb SET payment_status='$status' WHERE ID='$appt_id'");
    if ($query) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Payment status updated!','success'); });</script>";
    }
}

// Stats
$totalPaid    = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE payment_status='Paid'"))[0];
$totalPending = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE payment_status='Pending'"))[0];
$totalRevenue = mysqli_fetch_row(mysqli_query($con, "SELECT SUM(docFees) FROM appointmenttb WHERE payment_status='Paid'"))[0] ?? 0;
$pendingRev   = mysqli_fetch_row(mysqli_query($con, "SELECT SUM(docFees) FROM appointmenttb WHERE payment_status='Pending' AND userStatus=1 AND doctorStatus=1"))[0] ?? 0;

// Filter
$filter = $_GET['filter'] ?? 'all';
$where  = match($filter) {
    'paid'    => "WHERE payment_status='Paid'",
    'pending' => "WHERE payment_status='Pending'",
    default   => ""
};
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Afya One — Payment Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal:    #0d9488;
            --teal-dk: #0f766e;
            --teal-lt: #ccfbf1;
            --navy:    #0f172a;
            --muted:   #64748b;
            --border:  #e2e8f0;
            --bg:      #f1f5f9;
            --white:   #ffffff;
            --red:     #ef4444;
            --amber:   #f59e0b;
            --green:   #10b981;
            --sidebar-w: 260px;
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'DM Sans',sans-serif; background:var(--bg); color:var(--navy); min-height:100vh; }

        /* TOPBAR */
        .topbar {
            background:var(--white); border-bottom:1px solid var(--border);
            padding:0 32px; height:64px;
            display:flex; align-items:center; justify-content:space-between;
            position:sticky; top:0; z-index:100;
            box-shadow:0 1px 10px rgba(0,0,0,.06);
        }
        .topbar-left { display:flex; align-items:center; gap:16px; }
        .topbar-brand {
            font-family:'Syne',sans-serif; font-size:18px; font-weight:800;
            color:var(--navy); display:flex; align-items:center; gap:8px;
        }
        .topbar-brand .icon { width:34px; height:34px; background:var(--teal); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:15px; }
        .topbar-brand span { color:var(--teal); }
        .page-title { font-size:15px; color:var(--muted); padding-left:16px; border-left:1px solid var(--border); }
        .topbar-right { display:flex; align-items:center; gap:10px; }
        .btn-back {
            display:inline-flex; align-items:center; gap:7px;
            padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600;
            text-decoration:none; background:var(--bg); color:var(--muted);
            border:1px solid var(--border); transition:all .2s;
        }
        .btn-back:hover { background:var(--teal-lt); color:var(--teal); border-color:var(--teal); }

        /* MAIN */
        .main { padding:28px 32px; max-width:1200px; margin:0 auto; }

        /* STATS */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin-bottom:28px; }
        .stat-card { background:var(--white); border:1px solid var(--border); border-radius:16px; padding:22px; position:relative; overflow:hidden; transition:transform .2s,box-shadow .2s; }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 30px rgba(0,0,0,.08); }
        .stat-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; }
        .stat-card.green::before { background:var(--green); }
        .stat-card.amber::before { background:var(--amber); }
        .stat-card.teal::before  { background:var(--teal); }
        .stat-card.red::before   { background:var(--red); }
        .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; margin-bottom:14px; }
        .stat-card.green .stat-icon { background:#d1fae5; color:var(--green); }
        .stat-card.amber .stat-icon { background:#fef3c7; color:var(--amber); }
        .stat-card.teal  .stat-icon { background:var(--teal-lt); color:var(--teal); }
        .stat-card.red   .stat-icon { background:#fee2e2; color:var(--red); }
        .stat-num { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; line-height:1; }
        .stat-label { font-size:13px; color:var(--muted); margin-top:4px; }

        /* FILTER BAR */
        .filter-bar { display:flex; align-items:center; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
        .filter-bar h2 { font-family:'Syne',sans-serif; font-size:18px; font-weight:700; flex:1; }
        .filter-btn {
            padding:8px 18px; border-radius:20px; font-size:13px; font-weight:600;
            text-decoration:none; transition:all .2s; border:1.5px solid var(--border);
            background:var(--white); color:var(--muted);
        }
        .filter-btn:hover { border-color:var(--teal); color:var(--teal); }
        .filter-btn.active { background:var(--teal); color:#fff; border-color:var(--teal); }

        /* TABLE CARD */
        .table-card { background:var(--white); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        thead th { background:var(--bg); padding:12px 14px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--muted); border-bottom:1px solid var(--border); white-space:nowrap; }
        tbody td { padding:14px 14px; font-size:14px; border-bottom:1px solid var(--border); }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover { background:#f8fafc; }

        /* STATUS BADGES */
        .badge { display:inline-flex; align-items:center; gap:5px; padding:5px 12px; border-radius:20px; font-size:12px; font-weight:700; }
        .badge-paid    { background:#d1fae5; color:#065f46; }
        .badge-pending { background:#fef3c7; color:#92400e; }

        /* UPDATE FORM */
        .update-form { display:flex; align-items:center; gap:8px; }
        .status-select {
            border:1.5px solid var(--border); border-radius:8px;
            padding:5px 10px; font-size:12px; outline:none;
            background:#fff; color:var(--navy); cursor:pointer;
        }
        .status-select:focus { border-color:var(--teal); }
        .btn-update {
            background:var(--teal); color:#fff; border:none;
            border-radius:8px; padding:6px 12px; font-size:12px;
            font-weight:600; cursor:pointer; transition:background .2s;
            display:inline-flex; align-items:center; gap:4px;
        }
        .btn-update:hover { background:var(--teal-dk); }

        /* TOAST */
        .toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
        .toast-msg { background:var(--navy); color:#fff; padding:12px 18px; border-radius:12px; font-size:14px; display:flex; align-items:center; gap:10px; box-shadow:0 8px 30px rgba(0,0,0,.2); animation:slideIn .3s ease; }
        .toast-msg.success { border-left:4px solid var(--green); }
        @keyframes slideIn { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }

        .empty-state { text-align:center; padding:50px; color:var(--muted); }
        .empty-state i { font-size:36px; display:block; margin-bottom:12px; opacity:.4; }
    </style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-left">
        <div class="topbar-brand">
            <div class="icon"><i class="fa-solid fa-hospital"></i></div>
            Afya <span>One</span>
        </div>
        <div class="page-title"><i class="fa-solid fa-credit-card" style="margin-right:6px;color:var(--teal)"></i>Payment Status</div>
    </div>
    <div class="topbar-right">
        <a href="admin-panel1.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-num"><?= $totalPaid ?></div>
            <div class="stat-label">Payments Received</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-num"><?= $totalPending ?></div>
            <div class="stat-label">Pending Payments</div>
        </div>
        <div class="stat-card teal">
            <div class="stat-icon"><i class="fa-solid fa-coins"></i></div>
            <div class="stat-num">KES <?= number_format($totalRevenue) ?></div>
            <div class="stat-label">Total Revenue Collected</div>
        </div>
        <div class="stat-card red">
            <div class="stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="stat-num">KES <?= number_format($pendingRev) ?></div>
            <div class="stat-label">Outstanding Amount</div>
        </div>
    </div>

    <!-- Filter + Table -->
    <div class="filter-bar">
        <h2>All Appointments</h2>
        <a href="?filter=all"     class="filter-btn <?= $filter==='all'     ? 'active' : '' ?>">All</a>
        <a href="?filter=paid"    class="filter-btn <?= $filter==='paid'    ? 'active' : '' ?>"><i class="fa-solid fa-circle-check"></i> Paid</a>
        <a href="?filter=pending" class="filter-btn <?= $filter==='pending' ? 'active' : '' ?>"><i class="fa-solid fa-clock"></i> Pending</a>
    </div>

    <div class="table-card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Appt ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Fees (KES)</th>
                        <th>Appt Status</th>
                        <th>Payment</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $q = "SELECT * FROM appointmenttb $where ORDER BY ID DESC";
                $r = mysqli_query($con, $q);
                $cnt = 0;
                while ($row = mysqli_fetch_array($r)):
                    $cnt++;
                    $payStatus = $row['payment_status'] ?? 'Pending';

                    if ($row['userStatus']==1 && $row['doctorStatus']==1)
                        $apptBadge = '<span class="badge" style="background:#d1fae5;color:#065f46">Active</span>';
                    elseif ($row['userStatus']==0)
                        $apptBadge = '<span class="badge" style="background:#fee2e2;color:#991b1b">Pat. Cancelled</span>';
                    else
                        $apptBadge = '<span class="badge" style="background:#fef3c7;color:#92400e">Doc. Cancelled</span>';

                    $payBadge = $payStatus === 'Paid'
                        ? '<span class="badge badge-paid"><i class="fa-solid fa-check"></i> Paid</span>'
                        : '<span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Pending</span>';
                ?>
                <tr>
                    <td><strong>#<?= $row['ID'] ?></strong></td>
                    <td>
                        <strong><?= $row['fname'].' '.$row['lname'] ?></strong>
                        <br><span style="font-size:12px;color:var(--muted)"><?= $row['contact'] ?></span>
                    </td>
                    <td><?= $row['doctor'] ?></td>
                    <td>
                        <?= $row['appdate'] ?>
                        <br><span style="font-size:12px;color:var(--muted)"><?= date('g:i A',strtotime($row['apptime'])) ?></span>
                    </td>
                    <td><strong>KES <?= number_format($row['docFees']) ?></strong></td>
                    <td><?= $apptBadge ?></td>
                    <td><?= $payBadge ?></td>
                    <td>
                        <form method="post" class="update-form">
                            <input type="hidden" name="appt_id" value="<?= $row['ID'] ?>">
                            <select name="status" class="status-select">
                                <option value="Pending" <?= $payStatus==='Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Paid"    <?= $payStatus==='Paid'    ? 'selected' : '' ?>>Paid</option>
                            </select>
                            <button type="submit" name="update_payment" class="btn-update">
                                <i class="fa-solid fa-check"></i> Save
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endwhile;
                if ($cnt === 0): ?>
                <tr><td colspan="8">
                    <div class="empty-state">
                        <i class="fa-regular fa-folder-open"></i>
                        No appointments found for this filter.
                    </div>
                </td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast-msg ' + type;
    toast.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${message}`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}
</script>
</body>
</html>