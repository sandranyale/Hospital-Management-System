<?php
// session already started in func.php
include('func.php');
include('newfunc.php');
$con = mysqli_connect("localhost", "root", "", "myhmsdb");
$pid      = $_SESSION['pid'];
$username = $_SESSION['username'];
$email    = $_SESSION['email'];
$fname    = $_SESSION['fname'];
$lname    = $_SESSION['lname'];
$gender   = $_SESSION['gender'];
$contact  = $_SESSION['contact'];

// Book appointment
if (isset($_POST['app-submit'])) {
    $doctor   = $_POST['doctor'];
    $docFees  = $_POST['docFees'];
    $appdate  = $_POST['appdate'];
    $apptime  = $_POST['apptime'];
    $cur_date = date("Y-m-d");
    date_default_timezone_set('Africa/Nairobi');
    $cur_time  = date("H:i:s");
    $apptime1  = strtotime($apptime);
    $appdate1  = strtotime($appdate);

    if (date("Y-m-d", $appdate1) >= $cur_date) {
        if ((date("Y-m-d", $appdate1) == $cur_date && date("H:i:s", $apptime1) > $cur_time) || date("Y-m-d", $appdate1) > $cur_date) {
            $check = mysqli_query($con, "select apptime from appointmenttb where doctor='$doctor' and appdate='$appdate' and apptime='$apptime'");
            if (mysqli_num_rows($check) == 0) {
                $q = mysqli_query($con, "insert into appointmenttb(pid,fname,lname,gender,email,contact,doctor,docFees,appdate,apptime,userStatus,doctorStatus) values($pid,'$fname','$lname','$gender','$email','$contact','$doctor','$docFees','$appdate','$apptime','1','0')");
                if ($q) {
                    echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Appointment booked successfully!','success'); setTimeout(()=>showTab('history'),1500); });</script>";
                } else {
                    echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Unable to book. Please try again.','error'); });</script>";
                }
            } else {
                echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Doctor not available at that time. Choose another slot.','error'); });</script>";
            }
        } else {
            echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Please select a future date and time.','error'); });</script>";
        }
    } else {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Please select a future date and time.','error'); });</script>";
    }
}

// Cancel appointment
if (isset($_GET['cancel'])) {
    $q = mysqli_query($con, "update appointmenttb set userStatus='0' where ID='" . $_GET['ID'] . "'");
    if ($q) {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){ showToast('Appointment cancelled.','warning'); });</script>";
    }
}

// Generate bill PDF
function generate_bill() {
    $con = mysqli_connect("localhost", "root", "", "myhmsdb");
    $pid = $_SESSION['pid'];
    $ID  = intval($_GET['ID']);

    $row = mysqli_fetch_assoc(mysqli_query($con,
        "SELECT p.pid, p.ID, p.fname, p.lname, p.doctor,
                p.appdate, p.apptime, p.disease, p.allergy, p.prescription,
                a.docFees,
                IFNULL(p.medicine_total, 0) as medicine_total
         FROM prestb p
         INNER JOIN appointmenttb a ON p.ID = a.ID
         WHERE p.pid = '$pid' AND p.ID = '$ID'
         LIMIT 1"));

    if (!$row) return '<p>Bill not found.</p>';

    $consult    = floatval($row['docFees']);
    $medTotal   = floatval($row['medicine_total']);
    $grandTotal = $consult + $medTotal;

    $medsRows = '';
    $medsQ = mysqli_query($con, "SELECT med_name, quantity, unit_price, total FROM prescriptionmeds WHERE pres_id = '$ID' AND pid = '$pid'");
    if ($medsQ && mysqli_num_rows($medsQ) > 0) {
        while ($med = mysqli_fetch_assoc($medsQ)) {
            $medsRows .= '<tr>
                <td style="padding:5px 6px;border:1px solid #999;width:90mm;">' . htmlspecialchars($med['med_name']) . '</td>
                <td style="padding:5px 6px;border:1px solid #999;text-align:center;width:20mm;">' . intval($med['quantity']) . '</td>
                <td style="padding:5px 6px;border:1px solid #999;width:35mm;">KES ' . number_format($med['unit_price'], 2) . '</td>
                <td style="padding:5px 6px;border:1px solid #999;width:35mm;">KES ' . number_format($med['total'], 2) . '</td>
            </tr>';
        }
    } else {
        $medsRows = '<tr><td colspan="4" style="padding:6px 8px;border:1px solid #ccc;color:#888;font-style:italic;">No medicines prescribed</td></tr>';
    }

    return '
    <table cellpadding="4" style="width:180mm;font-size:10px;margin-bottom:6px;">
      <tr><td style="width:50mm;"><b>Patient Name</b></td><td>' . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . '</td></tr>
      <tr><td><b>Patient ID</b></td><td>#' . $row['pid'] . '</td></tr>
      <tr><td><b>Appointment ID</b></td><td>#' . $row['ID'] . '</td></tr>
      <tr><td><b>Doctor</b></td><td>Dr. ' . htmlspecialchars($row['doctor']) . '</td></tr>
      <tr><td><b>Date</b></td><td>' . date("d M Y", strtotime($row['appdate'])) . ' at ' . date("g:i A", strtotime($row['apptime'])) . '</td></tr>
      <tr><td><b>Diagnosis</b></td><td>' . htmlspecialchars($row['disease']) . '</td></tr>
      <tr><td><b>Allergies</b></td><td>' . htmlspecialchars($row['allergy'] ? $row['allergy'] : 'None') . '</td></tr>
      <tr><td><b>Clinical Notes</b></td><td>' . htmlspecialchars($row['prescription']) . '</td></tr>
    </table>
    <br/>
    <b style="font-size:10px;">PRESCRIBED MEDICINES</b>
    <table cellpadding="3" style="width:180mm;font-size:10px;border-collapse:collapse;margin-top:3px;">
      <tr>
        <th style="padding:5px 6px;border:1px solid #999;text-align:left;background-color:#dddddd;width:90mm;">Medicine</th>
        <th style="padding:5px 6px;border:1px solid #999;text-align:center;background-color:#dddddd;width:20mm;">Qty</th>
        <th style="padding:5px 6px;border:1px solid #999;text-align:right;background-color:#dddddd;width:35mm;">Unit Price</th>
        <th style="padding:5px 6px;border:1px solid #999;text-align:right;background-color:#dddddd;width:35mm;">Total</th>
      </tr>
      ' . $medsRows . '
    </table>
    <br/>
    <b style="font-size:10px;">BILL SUMMARY</b>
    <table cellpadding="4" style="width:180mm;font-size:11px;border-collapse:collapse;margin-top:3px;">
      <tr>
        <td style="border:1px solid #999;width:130mm;padding:6px 8px;"><b>Consultation Fee</b></td>
        <td style="border:1px solid #999;width:50mm;padding:6px 8px;">KES ' . number_format($consult, 2) . '</td>
      </tr>
      <tr>
        <td style="border:1px solid #999;padding:6px 8px;"><b>Medicine Total</b></td>
        <td style="border:1px solid #999;padding:6px 8px;">KES ' . number_format($medTotal, 2) . '</td>
      </tr>
      <tr>
        <td style="border:2px solid #000;padding:8px;background-color:#dddddd;"><b style="font-size:13px;">GRAND TOTAL</b></td>
        <td style="border:2px solid #000;padding:8px;background-color:#dddddd;"><b style="font-size:13px;">KES ' . number_format($grandTotal, 2) . '</b></td>
      </tr>
      <tr>
        <td style="border:1px solid #999;padding:6px 8px;"><b>Payment Method</b></td>
        <td style="border:1px solid #999;padding:6px 8px;">M-Pesa</td>
      </tr>
      <tr>
        <td style="border:1px solid #999;padding:6px 8px;"><b>Payment Status</b></td>
        <td style="border:1px solid #999;padding:6px 8px;"><b>PAID</b></td>
      </tr>
      <tr>
        <td style="border:1px solid #999;padding:6px 8px;"><b>Receipt Date</b></td>
        <td style="border:1px solid #999;padding:6px 8px;">' . date("d M Y, g:i A") . '</td>
      </tr>
    </table>';
}

if (isset($_GET["generate_bill"])) {
    require_once("TCPDF/tcpdf.php");
    $obj_pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
    $obj_pdf->SetCreator('Afya One HMS');
    $obj_pdf->SetTitle('Medical Bill - Afya One Hospital');
    $obj_pdf->SetPrintHeader(false);
    $obj_pdf->SetPrintFooter(false);
    $obj_pdf->SetMargins(10, 10, 10);
    $obj_pdf->SetAutoPageBreak(false);
    $obj_pdf->SetFont('helvetica', '', 11);
    $obj_pdf->AddPage();
    $content  = '<h2 style="text-align:center;">AFYA ONE HOSPITAL</h2>';
    $content .= '<h4 style="text-align:center;">Medical Bill & Payment Receipt</h4>';
    $content .= '<p style="text-align:center;font-size:10px;">Nairobi, Kenya | +254 701 234 556 | info@afyaone.co.ke</p>';
    $content .= '<hr/>';
    $content .= generate_bill();
    $content .= '<br/><hr/>';
    $content .= '<p style="text-align:center;font-size:9px;color:#666;">Official receipt generated by Afya One Hospital Management System on ' . date('Y-m-d H:i:s') . '</p>';
    $obj_pdf->writeHTML($content, true, false, true, false, '');
    ob_end_clean();
    $obj_pdf->Output('afyaone_bill.pdf', 'I');
    exit;
}

function get_specs() {
    $con = mysqli_connect("localhost", "root", "", "myhmsdb");
    $query = mysqli_query($con, "select username,spec from doctb");
    $docarray = [];
    while ($row = mysqli_fetch_assoc($query)) $docarray[] = $row;
    return json_encode($docarray);
}

// Notifications — fetch recent appointment status changes
$notifications = [];
$nq = mysqli_query($con, "SELECT ID, doctor, appdate, apptime, userStatus, doctorStatus FROM appointmenttb WHERE pid='$pid' ORDER BY ID DESC LIMIT 20");
while ($n = mysqli_fetch_assoc($nq)) {
    if ($n['userStatus']==1 && $n['doctorStatus']==1)
        $notifications[] = ['type'=>'approved','icon'=>'circle-check','color'=>'#10b981','msg'=>"Dr. {$n['doctor']} approved your appointment on {$n['appdate']} at ".date('g:i A',strtotime($n['apptime'])),'id'=>$n['ID']];
    elseif ($n['doctorStatus']==2)
        $notifications[] = ['type'=>'cancelled','icon'=>'circle-xmark','color'=>'#ef4444','msg'=>"Dr. {$n['doctor']} cancelled your appointment on {$n['appdate']}.",'id'=>$n['ID']];
    elseif ($n['userStatus']==1 && $n['doctorStatus']==0)
        $notifications[] = ['type'=>'pending','icon'=>'clock','color'=>'#f59e0b','msg'=>"Appointment with Dr. {$n['doctor']} on {$n['appdate']} is awaiting approval.",'id'=>$n['ID']];
}
$unreadCount     = count(array_filter($notifications, fn($n) => in_array($n['type'],['approved','cancelled'])));
$myAppointments  = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE pid='$pid'"))[0];
$activeAppts     = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE pid='$pid' AND userStatus=1 AND doctorStatus=1"))[0];
$myPrescriptions = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM prestb WHERE pid='$pid'"))[0];
$cancelledAppts  = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM appointmenttb WHERE pid='$pid' AND (userStatus=0 OR doctorStatus=2)"))[0];
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Afya One — Patient Portal</title>
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
            --sidebar-w: 265px;
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'DM Sans',sans-serif; background:var(--bg); color:var(--navy); min-height:100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            position:fixed; top:0; left:0; width:var(--sidebar-w); height:100vh;
            background:var(--navy); display:flex; flex-direction:column; z-index:100;
        }
        .sidebar-brand { padding:26px 22px 18px; border-bottom:1px solid rgba(255,255,255,.08); }
        .brand-icon { width:42px; height:42px; background:var(--teal); border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#fff; margin-bottom:10px; }
        .sidebar-brand h1 { font-family:'Syne',sans-serif; font-size:17px; font-weight:800; color:#fff; }
        .sidebar-brand h1 span { color:var(--teal); }

        /* Patient profile card */
        .pat-profile {
            margin:14px 14px 0; background:rgba(255,255,255,.06);
            border-radius:12px; padding:14px; display:flex; align-items:center; gap:12px;
        }
        .pat-avatar {
            width:46px; height:46px; border-radius:50%;
            background:linear-gradient(135deg,var(--teal),#34d399);
            display:flex; align-items:center; justify-content:center;
            font-family:'Syne',sans-serif; font-size:16px; font-weight:800; color:#fff; flex-shrink:0;
        }
        .pat-info h4 { font-size:13px; font-weight:700; color:#fff; }
        .pat-info span { font-size:11px; color:rgba(255,255,255,.45); }
        .pat-id-badge {
            margin:10px 14px 0; background:rgba(13,148,136,.2);
            border:1px solid rgba(13,148,136,.3); border-radius:8px; padding:7px 12px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .pat-id-badge span { font-size:12px; color:rgba(255,255,255,.5); }
        .pat-id-badge strong { font-size:13px; color:#5eead4; font-weight:700; }

        .sidebar-nav { flex:1; padding:14px 12px; overflow-y:auto; }
        .nav-section-label { font-size:10px; font-weight:600; color:rgba(255,255,255,.25); text-transform:uppercase; letter-spacing:1.2px; padding:10px 12px 5px; }
        .nav-item { margin-bottom:2px; }
        .nav-link { display:flex; align-items:center; gap:12px; padding:11px 14px; border-radius:10px; color:rgba(255,255,255,.5); font-size:14px; font-weight:500; text-decoration:none; cursor:pointer; transition:all .2s; }
        .nav-link i { width:18px; font-size:15px; }
        .nav-link:hover { background:rgba(255,255,255,.07); color:#fff; }
        .nav-link.active { background:var(--teal); color:#fff; }
        .nav-link .badge-pill { margin-left:auto; background:rgba(255,255,255,.15); color:#fff; font-size:11px; padding:2px 7px; border-radius:20px; }
        .nav-link.active .badge-pill { background:rgba(255,255,255,.25); }
        .sidebar-footer { padding:14px 12px; border-top:1px solid rgba(255,255,255,.08); }
        .sidebar-footer a { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; color:rgba(255,255,255,.45); font-size:13px; text-decoration:none; transition:all .2s; }
        .sidebar-footer a:hover { background:rgba(239,68,68,.15); color:#f87171; }

        /* ── TOPBAR ── */
        .topbar { position:fixed; top:0; left:var(--sidebar-w); right:0; height:64px; background:var(--white); border-bottom:1px solid var(--border); display:flex; align-items:center; padding:0 28px; z-index:99; gap:16px; }
        .topbar-title { font-family:'Syne',sans-serif; font-size:20px; font-weight:700; color:var(--navy); flex:1; }
        .topbar-title span { color:var(--teal); }
        .topbar-badge { display:flex; align-items:center; gap:8px; background:var(--teal-lt); color:var(--teal-dk); padding:6px 14px; border-radius:30px; font-size:13px; font-weight:600; }

        /* ── MAIN ── */
        .main { margin-left:var(--sidebar-w); padding-top:64px; min-height:100vh; }
        .content { padding:28px; }
        .tab-pane { display:none; }
        .tab-pane.active { display:block; animation:fadeUp .3s ease; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }

        /* ── WELCOME BANNER ── */
        .welcome-banner {
            background:linear-gradient(135deg,var(--teal) 0%,var(--teal-dk) 100%);
            border-radius:16px; padding:26px 28px;
            display:flex; align-items:center; justify-content:space-between;
            margin-bottom:24px; overflow:hidden; position:relative;
        }
        .welcome-banner::after {
            content:''; position:absolute; right:-20px; top:-20px;
            width:160px; height:160px; border-radius:50%;
            background:rgba(255,255,255,.07);
        }
        .welcome-banner h2 { font-family:'Syne',sans-serif; font-size:22px; font-weight:800; color:#fff; margin-bottom:4px; }
        .welcome-banner p { font-size:14px; color:rgba(255,255,255,.7); }
        .welcome-banner .date-chip { background:rgba(255,255,255,.15); color:#fff; padding:8px 16px; border-radius:20px; font-size:13px; font-weight:600; }

        /* ── STAT CARDS ── */
        .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:24px; }
        .stat-card { background:var(--white); border-radius:16px; padding:20px; border:1px solid var(--border); position:relative; overflow:hidden; transition:transform .2s,box-shadow .2s; }
        .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 30px rgba(0,0,0,.08); }
        .stat-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; }
        .stat-card.teal::before  { background:var(--teal); }
        .stat-card.green::before { background:var(--green); }
        .stat-card.amber::before { background:var(--amber); }
        .stat-card.red::before   { background:var(--red); }
        .stat-icon { width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:17px; margin-bottom:14px; }
        .stat-card.teal  .stat-icon { background:var(--teal-lt); color:var(--teal); }
        .stat-card.green .stat-icon { background:#d1fae5;        color:var(--green); }
        .stat-card.amber .stat-icon { background:#fef3c7;        color:var(--amber); }
        .stat-card.red   .stat-icon { background:#fee2e2;        color:var(--red); }
        .stat-num { font-family:'Syne',sans-serif; font-size:28px; font-weight:800; color:var(--navy); line-height:1; }
        .stat-label { font-size:13px; color:var(--muted); margin-top:4px; }

        /* ── QUICK ACTIONS ── */
        .quick-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:14px; margin-bottom:24px; }
        .quick-card { background:var(--white); border:1px solid var(--border); border-radius:14px; padding:18px 20px; display:flex; align-items:center; gap:14px; cursor:pointer; text-decoration:none; color:var(--navy); transition:all .2s; }
        .quick-card:hover { border-color:var(--teal); color:var(--teal); box-shadow:0 4px 20px rgba(13,148,136,.1); transform:translateY(-2px); }
        .quick-card-icon { width:42px; height:42px; background:var(--teal-lt); color:var(--teal); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0; }
        .quick-card-text strong { font-size:14px; font-weight:600; display:block; }
        .quick-card-text span { font-size:12px; color:var(--muted); }

        /* ── SECTION HEADER ── */
        .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .section-header h2 { font-family:'Syne',sans-serif; font-size:18px; font-weight:700; }

        /* ── CARD ── */
        .card { background:var(--white); border:1px solid var(--border); border-radius:16px; overflow:hidden; margin-bottom:20px; }
        .card-header-custom { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:10px; }
        .card-header-custom h3 { font-family:'Syne',sans-serif; font-size:16px; font-weight:700; flex:1; }
        .card-body-custom { padding:24px; }

        /* ── TABLE ── */
        .table-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; }
        thead th { background:var(--bg); padding:11px 14px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--muted); border-bottom:1px solid var(--border); white-space:nowrap; }
        tbody td { padding:13px 14px; font-size:14px; border-bottom:1px solid var(--border); }
        tbody tr:last-child td { border-bottom:none; }
        tbody tr:hover { background:#f8fafc; }
        .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; }
        .status-active   { background:#d1fae5; color:#065f46; }
        .status-cancel-p { background:#fee2e2; color:#991b1b; }
        .status-cancel-d { background:#fef3c7; color:#92400e; }

        /* ── BOOK APPOINTMENT FORM ── */
        .book-form { background:var(--white); border:1px solid var(--border); border-radius:16px; padding:28px; max-width:580px; }
        .book-form h3 { font-family:'Syne',sans-serif; font-size:18px; font-weight:700; margin-bottom:22px; padding-bottom:14px; border-bottom:1px solid var(--border); }
        .form-label { font-size:13px; font-weight:600; color:var(--slate); margin-bottom:6px; display:block; }
        .form-control { border:1.5px solid var(--border); border-radius:10px; padding:10px 14px; font-size:14px; width:100%; transition:border-color .2s; outline:none; background:#fff; }
        .form-control:focus { border-color:var(--teal); box-shadow:0 0 0 3px rgba(13,148,136,.1); }
        .form-group-row { margin-bottom:18px; }
        .fees-display {
            background:var(--teal-lt); border:1.5px solid var(--teal);
            border-radius:10px; padding:10px 14px; font-size:15px;
            font-weight:700; color:var(--teal-dk);
        }
        .btn-book {
            background:var(--teal); color:#fff; border:none; border-radius:10px;
            padding:12px 28px; font-size:14px; font-weight:700; cursor:pointer;
            display:inline-flex; align-items:center; gap:8px; transition:all .2s;
            width:100%; justify-content:center;
        }
        .btn-book:hover { background:var(--teal-dk); transform:translateY(-1px); }

        /* ── ACTION BUTTONS ── */
        .btn-danger-sm { background:var(--red); color:#fff; border:none; border-radius:8px; padding:6px 12px; font-size:12px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:5px; transition:background .2s; text-decoration:none; }
        .btn-danger-sm:hover { background:#dc2626; color:#fff; }
        .btn-pay { background:var(--green); color:#fff; border:none; border-radius:8px; padding:6px 14px; font-size:12px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:all .2s; text-decoration:none; }
        .btn-pay:hover { background:#059669; color:#fff; }

        /* ── MPESA MODAL ── */
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center; }
        .modal-overlay.open { display:flex; }
        .mpesa-modal {
            background:#fff; border-radius:20px; width:360px;
            padding:0; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.3);
            animation:popIn .3s ease;
        }
        @keyframes popIn { from{opacity:0;transform:scale(.9)} to{opacity:1;transform:scale(1)} }
        .mpesa-header {
            background:linear-gradient(135deg,#4caf50,#2e7d32);
            padding:24px; text-align:center; color:#fff;
        }
        .mpesa-logo { font-size:36px; margin-bottom:6px; }
        .mpesa-header h3 { font-family:'Syne',sans-serif; font-size:20px; font-weight:800; }
        .mpesa-header p { font-size:13px; opacity:.8; margin-top:2px; }
        .mpesa-body { padding:24px; }
        .mpesa-amount { text-align:center; margin-bottom:20px; }
        .mpesa-amount .label { font-size:13px; color:var(--muted); margin-bottom:4px; }
        .mpesa-amount .amount { font-family:'Syne',sans-serif; font-size:32px; font-weight:800; color:#2e7d32; }
        .mpesa-phone-wrap { margin-bottom:20px; }
        .mpesa-phone-wrap label { font-size:13px; font-weight:600; color:var(--slate); margin-bottom:6px; display:block; }
        .mpesa-phone-input {
            width:100%; border:2px solid var(--border); border-radius:10px;
            padding:11px 14px; font-size:15px; outline:none; transition:border-color .2s;
        }
        .mpesa-phone-input:focus { border-color:#4caf50; }
        .mpesa-note { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px; font-size:12px; color:#166534; margin-bottom:20px; display:flex; gap:8px; align-items:flex-start; }
        .btn-mpesa-pay { width:100%; background:linear-gradient(135deg,#4caf50,#2e7d32); color:#fff; border:none; border-radius:12px; padding:14px; font-size:15px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:opacity .2s; }
        .btn-mpesa-pay:hover { opacity:.9; }
        .btn-mpesa-cancel { width:100%; background:none; border:1.5px solid var(--border); border-radius:12px; padding:11px; font-size:14px; color:var(--muted); cursor:pointer; margin-top:8px; }
        .btn-mpesa-cancel:hover { background:var(--bg); }

        /* ── STK PROCESSING SCREEN ── */
        .stk-screen { display:none; text-align:center; padding:10px 0; }
        .stk-spinner { width:60px; height:60px; border:4px solid #e8f5e9; border-top:4px solid #4caf50; border-radius:50%; animation:spin 1s linear infinite; margin:0 auto 16px; }
        @keyframes spin { to{transform:rotate(360deg)} }
        .stk-screen h4 { font-family:'Syne',sans-serif; font-size:17px; font-weight:700; margin-bottom:8px; }
        .stk-screen p { font-size:13px; color:var(--muted); }
        .stk-phone-display { background:#f0fdf4; border-radius:10px; padding:10px 14px; font-size:15px; font-weight:700; color:#2e7d32; margin:12px 0; }

        /* ── SUCCESS SCREEN ── */
        .success-screen { display:none; text-align:center; padding:10px 0; }
        .success-icon { width:70px; height:70px; background:linear-gradient(135deg,#4caf50,#2e7d32); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; }
        .success-icon i { font-size:32px; color:#fff; }
        .success-screen h4 { font-family:'Syne',sans-serif; font-size:18px; font-weight:800; margin-bottom:6px; }
        .success-screen p { font-size:13px; color:var(--muted); margin-bottom:16px; }
        .mpesa-ref { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:10px; font-size:13px; color:#166534; font-weight:700; margin-bottom:16px; }
        .btn-download { background:var(--teal); color:#fff; border:none; border-radius:10px; padding:11px 20px; font-size:14px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }

        /* ── NOTIFICATIONS ── */
        .notif-banner {
            border-radius:14px; padding:14px 18px; margin-bottom:14px;
            display:flex; align-items:flex-start; gap:14px;
            animation:fadeUp .3s ease;
        }
        .notif-banner.cancelled { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }
        .notif-banner.approved  { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
        .notif-icon { font-size:20px; flex-shrink:0; margin-top:1px; }
        .notif-text h4 { font-size:14px; font-weight:700; margin-bottom:3px; }
        .notif-text p  { font-size:13px; opacity:.8; }

        /* ── NOTIFICATION BELL ── */
        .notif-wrap { position:relative; }
        .notif-btn {
            width:40px; height:40px; border-radius:10px;
            background:var(--bg); border:1px solid var(--border);
            display:flex; align-items:center; justify-content:center;
            font-size:16px; color:var(--muted); cursor:pointer;
            position:relative; transition:all .2s;
        }
        .notif-btn:hover { background:var(--teal-lt); color:var(--teal); border-color:var(--teal); }
        .notif-badge {
            position:absolute; top:-5px; right:-5px;
            background:var(--red); color:#fff;
            font-size:10px; font-weight:700;
            width:18px; height:18px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            border:2px solid var(--white);
        }
        .notif-dropdown {
            display:none; position:absolute; top:48px; right:0;
            width:340px; background:var(--white);
            border:1px solid var(--border); border-radius:16px;
            box-shadow:0 12px 40px rgba(0,0,0,.15); z-index:200;
            overflow:hidden;
        }
        .notif-dropdown.open { display:block; animation:fadeUp .2s ease; }
        .notif-header {
            padding:14px 18px; border-bottom:1px solid var(--border);
            display:flex; justify-content:space-between; align-items:center;
        }
        .notif-header strong { font-family:'Syne',sans-serif; font-size:15px; font-weight:700; }
        .notif-header span { font-size:12px; color:var(--muted); }
        .notif-list { max-height:340px; overflow-y:auto; }
        .notif-item {
            display:flex; gap:12px; padding:14px 18px;
            border-bottom:1px solid var(--border); transition:background .2s;
        }
        .notif-item:last-child { border-bottom:none; }
        .notif-item:hover { background:var(--bg); }
        .notif-icon { font-size:18px; flex-shrink:0; padding-top:2px; }
        .notif-text p { font-size:13px; line-height:1.5; color:var(--navy); }
        .notif-text span { font-size:11px; color:var(--muted); margin-top:3px; display:block; }
        .notif-empty { padding:30px; text-align:center; color:var(--muted); }
        .notif-empty i { font-size:28px; display:block; margin-bottom:8px; opacity:.4; }
        .notif-empty p { font-size:13px; }

        /* ── TOAST ── */
        .toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; }        .toast-msg { background:var(--navy); color:#fff; padding:12px 18px; border-radius:12px; font-size:14px; display:flex; align-items:center; gap:10px; box-shadow:0 8px 30px rgba(0,0,0,.2); animation:slideIn .3s ease; }
        .toast-msg.success { border-left:4px solid var(--green); }
        .toast-msg.warning { border-left:4px solid var(--amber); }
        .toast-msg.error   { border-left:4px solid var(--red); }
        @keyframes slideIn { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }

        @media(max-width:768px){ .sidebar{transform:translateX(-100%);} .sidebar.open{transform:translateX(0);} .main{margin-left:0;} .topbar{left:0;} }
    </style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<!-- ═══ MPESA MODAL ═══ -->
<div class="modal-overlay" id="mpesaModal">
    <div class="mpesa-modal">
        <div class="mpesa-header">
            <div class="mpesa-logo"></div>
            <h3>M-Pesa Payment</h3>
            <p>Afya One Hospital</p>
        </div>
        <div class="mpesa-body">
            <!-- Step 1: Enter Phone -->
            <div id="mpesaStep1">
                <div class="mpesa-amount">
                    <div class="label">Amount to Pay</div>
                    <div class="amount" id="modalAmount">KES 0</div>
                </div>
                <div class="mpesa-phone-wrap">
                    <label>M-Pesa Phone Number</label>
                    <input type="tel" class="mpesa-phone-input" id="mpesaPhone" placeholder="e.g. 0712345678" maxlength="10">
                </div>
                <div class="mpesa-note">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>An STK push will be sent to your phone. Enter your M-Pesa PIN to complete payment.</span>
                </div>
                <button class="btn-mpesa-pay" onclick="sendSTKPush()">
                    <i class="fa-solid fa-paper-plane"></i> Send STK Push
                </button>
                <button class="btn-mpesa-cancel" onclick="closeMpesa()">Cancel</button>
            </div>

            <!-- Step 2: STK Processing -->
            <div class="stk-screen" id="mpesaStep2">
                <div class="stk-spinner"></div>
                <h4>STK Push Sent!</h4>
                <p>Check your phone:</p>
                <div class="stk-phone-display" id="stkPhone"></div>
                <p>Enter your <strong>M-Pesa PIN</strong> to complete payment.<br>Waiting for confirmation...</p>
            </div>

            <!-- Step 3: Success -->
            <div class="success-screen" id="mpesaStep3">
                <div class="success-icon"><i class="fa-solid fa-check"></i></div>
                <h4>Payment Successful!</h4>
                <p>Your consultation fee has been received.</p>
                <div class="mpesa-ref" id="mpesaRef"></div>
                <a id="billDownloadBtn" href="#" class="btn-download" style="width:100%;justify-content:center;margin-bottom:8px">
                    <i class="fa-solid fa-file-pdf"></i> Download Bill Receipt
                </a>
                <button class="btn-mpesa-cancel" onclick="closeMpesa()">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-hospital"></i></div>
        <h1>Afya <span>One</span></h1>
    </div>

    <div class="pat-profile">
        <div class="pat-avatar"><?= strtoupper(substr($fname,0,1)) ?></div>
        <div class="pat-info">
            <h4><?= htmlspecialchars($fname.' '.$lname) ?></h4>
            <span><?= htmlspecialchars($gender) ?> Patient</span>
        </div>
    </div>
    <div class="pat-id-badge">
        <span>Patient ID</span>
        <strong>#<?= $pid ?></strong>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label" style="margin-top:10px">My Portal</div>
        <div class="nav-item">
            <a class="nav-link active" onclick="showTab('dash')" id="nav-dash">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('book')" id="nav-book">
                <i class="fa-solid fa-calendar-plus"></i> Book Appointment
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('history')" id="nav-history">
                <i class="fa-solid fa-clock-rotate-left"></i> My Appointments
                <span class="badge-pill"><?= $myAppointments ?></span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" onclick="showTab('prescriptions')" id="nav-prescriptions">
                <i class="fa-solid fa-file-medical"></i> Prescriptions & Bills
                <span class="badge-pill"><?= $myPrescriptions ?></span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-title" id="topbarTitle">Dashboard <span>Overview</span></div>
    <!-- Notification Bell -->
    <div class="notif-wrap" id="notifWrap">
        <button class="notif-btn" onclick="toggleNotif()" id="notifBtn">
            <i class="fa-solid fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
            <span class="notif-badge"><?= $unreadCount ?></span>
            <?php endif; ?>
        </button>
        <div class="notif-dropdown" id="notifDropdown">
            <div class="notif-header">
                <strong>Notifications</strong>
                <span><?= count($notifications) ?> updates</span>
            </div>
            <div class="notif-list">
            <?php if (empty($notifications)): ?>
                <div class="notif-empty"><i class="fa-regular fa-bell-slash"></i><p>No notifications yet</p></div>
            <?php else: ?>
                <?php foreach ($notifications as $n): ?>
                <div class="notif-item notif-<?= $n['type'] ?>">
                    <div class="notif-icon" style="color:<?= $n['color'] ?>">
                        <i class="fa-solid fa-<?= $n['icon'] ?>"></i>
                    </div>
                    <div class="notif-text">
                        <p><?= $n['msg'] ?></p>
                        <span>Appointment #<?= $n['id'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="topbar-badge"><i class="fa-solid fa-circle" style="font-size:8px;color:var(--green)"></i> Patient Portal</div>
</div>

<!-- MAIN -->
<div class="main">
<div class="content">

    <!-- ═══════════ DASHBOARD ═══════════ -->
    <div class="tab-pane active" id="tab-dash">

        <div class="welcome-banner">
            <div>
                <h2>Welcome back, <?= htmlspecialchars($fname) ?>! </h2>
                <p>Here's a summary of your health activity at Afya One.</p>
            </div>
            <div class="date-chip"><i class="fa-regular fa-calendar" style="margin-right:6px"></i><?= date('M j, Y') ?></div>
        </div>

        <div class="stats-grid">
            <div class="stat-card teal">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div class="stat-num"><?= $myAppointments ?></div>
                <div class="stat-label">Total Appointments</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fa-solid fa-calendar-day"></i></div>
                <div class="stat-num"><?= $activeAppts ?></div>
                <div class="stat-label">Active Appointments</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon"><i class="fa-solid fa-file-medical"></i></div>
                <div class="stat-num"><?= $myPrescriptions ?></div>
                <div class="stat-label">Prescriptions</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                <div class="stat-num"><?= $cancelledAppts ?></div>
                <div class="stat-label">Cancelled</div>
            </div>
        </div>

        <div class="section-header"><h2>Quick Actions</h2></div>
        <div class="quick-grid">
            <a class="quick-card" onclick="showTab('book')">
                <div class="quick-card-icon"><i class="fa-solid fa-calendar-plus"></i></div>
                <div class="quick-card-text"><strong>Book Appointment</strong><span>See available doctors</span></div>
            </a>
            <a class="quick-card" onclick="showTab('history')">
                <div class="quick-card-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                <div class="quick-card-text"><strong>My Appointments</strong><span><?= $activeAppts ?> active</span></div>
            </a>
            <a class="quick-card" onclick="showTab('prescriptions')">
                <div class="quick-card-icon"><i class="fa-solid fa-file-medical"></i></div>
                <div class="quick-card-text"><strong>Prescriptions & Bills</strong><span><?= $myPrescriptions ?> records</span></div>
            </a>
        </div>

        <!-- Recent appointments on dashboard -->
        <div class="section-header"><h2>Recent Appointments</h2></div>
        <div class="card">
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Doctor</th><th>Fees</th><th>Date</th><th>Time</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php
                    $rq = "SELECT * FROM appointmenttb WHERE pid='$pid' ORDER BY appdate DESC LIMIT 5";
                    $rr = mysqli_query($con, $rq);
                    $rc = 0;
                    while ($row = mysqli_fetch_array($rr)):
                        $rc++;
                        if ($row['userStatus']==1 && $row['doctorStatus']==1) $badge = '<span class="status-badge status-active"><i class="fa-solid fa-circle" style="font-size:7px"></i> Active</span>';
                        elseif ($row['userStatus']==0) $badge = '<span class="status-badge status-cancel-p">Cancelled by You</span>';
                        else $badge = '<span class="status-badge status-cancel-d">Cancelled by Doctor</span>';
                    ?>
                    <tr>
                        <td><strong><?= $row['doctor'] ?></strong></td>
                        <td>KES <?= number_format($row['docFees']) ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= date('g:i A', strtotime($row['apptime'])) ?></td>
                        <td><?= $badge ?></td>
                    </tr>
                    <?php endwhile;
                    if ($rc===0): ?>
                    <tr><td colspan="5" style="text-align:center;padding:28px;color:var(--muted)">
                        No appointments yet. <a onclick="showTab('book')" style="color:var(--teal);cursor:pointer;font-weight:600">Book one now →</a>
                    </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ BOOK APPOINTMENT ═══════════ -->
    <div class="tab-pane" id="tab-book">
        <div class="section-header"><h2>Book an Appointment</h2></div>
        <div class="book-form">
            <h3><i class="fa-solid fa-calendar-plus" style="color:var(--teal);margin-right:8px"></i>New Appointment</h3>
            <form method="post" action="patient-panel.php">
                <div class="form-group-row">
                    <label class="form-label">Specialization</label>
                    <select name="spec" class="form-control" id="spec">
                        <option value="" disabled selected>Select Specialization</option>
                        <?php display_specs(); ?>
                    </select>
                </div>
                <div class="form-group-row">
                    <label class="form-label">Doctor</label>
                    <select name="doctor" class="form-control" id="doctor" required>
                        <option value="" disabled selected>Select Doctor</option>
                        <?php display_docs(); ?>
                    </select>
                </div>
                <div class="form-group-row">
                    <label class="form-label">Consultation Fee (KES)</label>
                    <div class="fees-display" id="docFees">—</div>
                    <input type="hidden" name="docFees" id="docFeesHidden" value="">
                </div>
                <div class="form-group-row">
                    <label class="form-label">Appointment Date</label>
                    <input type="date" class="form-control" name="appdate" min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group-row">
                    <label class="form-label">Appointment Time</label>
                    <select name="apptime" class="form-control" id="apptime" required>
                        <option value="" disabled selected>Select Time</option>
                        <option value="08:00:00">8:00 AM</option>
                        <option value="10:00:00">10:00 AM</option>
                        <option value="12:00:00">12:00 PM</option>
                        <option value="14:00:00">2:00 PM</option>
                        <option value="16:00:00">4:00 PM</option>
                    </select>
                </div>
                <button type="submit" name="app-submit" class="btn-book">
                    <i class="fa-solid fa-calendar-check"></i> Confirm Appointment
                </button>
            </form>
        </div>
    </div>

    <!-- ═══════════ APPOINTMENT HISTORY ═══════════ -->
    <div class="tab-pane" id="tab-history">
        <div class="section-header"><h2>My Appointments</h2></div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-clock-rotate-left" style="color:var(--teal)"></i>
                <h3>Appointment History</h3>
                <span style="font-size:13px;color:var(--muted)"><?= $myAppointments ?> total</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Doctor</th><th>Fees</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php
                    $hq = "SELECT ID,doctor,docFees,appdate,apptime,userStatus,doctorStatus FROM appointmenttb WHERE fname='$fname' AND lname='$lname' ORDER BY appdate DESC";
                    $hr = mysqli_query($con, $hq);
                    $hc = 0;
                    while ($row = mysqli_fetch_array($hr)):
                        $hc++;
                        $active = ($row['userStatus']==1 && $row['doctorStatus']==1);
                        if ($active) $badge = '<span class="status-badge status-active"><i class="fa-solid fa-circle" style="font-size:7px"></i> Active</span>';
                        elseif ($row['userStatus']==0) $badge = '<span class="status-badge status-cancel-p">Cancelled by You</span>';
                        else $badge = '<span class="status-badge status-cancel-d">Cancelled by Doctor</span>';
                    ?>
                    <tr>
                        <td><strong><?= $row['doctor'] ?></strong></td>
                        <td>KES <?= number_format($row['docFees']) ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= date('g:i A', strtotime($row['apptime'])) ?></td>
                        <td><?= $badge ?></td>
                        <td>
                            <?php if ($active): ?>
                            <a href="patient-panel.php?ID=<?= $row['ID'] ?>&cancel=update"
                               onclick="return confirm('Cancel this appointment?')"
                               class="btn-danger-sm"><i class="fa-solid fa-xmark"></i> Cancel</a>
                            <?php else: ?>
                            <span style="color:var(--muted);font-size:13px">Cancelled</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile;
                    if ($hc===0): ?>
                    <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--muted)">No appointments found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ═══════════ PRESCRIPTIONS & BILLS ═══════════ -->
    <div class="tab-pane" id="tab-prescriptions">
        <div class="section-header"><h2>Prescriptions & Bills</h2></div>
        <div class="card">
            <div class="card-header-custom">
                <i class="fa-solid fa-file-medical" style="color:var(--teal)"></i>
                <h3>My Prescriptions</h3>
                <span style="font-size:13px;color:var(--muted)"><?= $myPrescriptions ?> records</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Doctor</th><th>Appt ID</th><th>Date</th><th>Time</th><th>Disease</th><th>Allergy</th><th>Prescription</th><th>Payment</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $pq = "SELECT doctor,ID,appdate,apptime,disease,allergy,prescription FROM prestb WHERE pid='$pid' ORDER BY appdate DESC";
                    $pr = mysqli_query($con, $pq);
                    $pc = 0;
                    while ($row = mysqli_fetch_array($pr)):
                        $pc++;
                        // Get fees for this appointment
                        $feeRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT a.docFees, COALESCE(p.medicine_total,0) as medicine_total FROM appointmenttb a LEFT JOIN prestb p ON a.ID=p.ID WHERE a.ID='" . $row['ID'] . "'"));
                        $fee = floatval($feeRow['docFees'] ?? 0) + floatval($feeRow['medicine_total'] ?? 0);
                    ?>
                    <tr>
                        <td><strong><?= $row['doctor'] ?></strong></td>
                        <td>#<?= $row['ID'] ?></td>
                        <td><?= $row['appdate'] ?></td>
                        <td><?= date('g:i A', strtotime($row['apptime'])) ?></td>
                        <td><span class="status-badge" style="background:#fef3c7;color:#92400e"><?= $row['disease'] ?></span></td>
                        <td><?= $row['allergy'] ?: '—' ?></td>
                        <td style="max-width:180px;font-size:13px"><?= $row['prescription'] ?></td>
                        <td>
                            <button class="btn-pay"
                                onclick="openMpesa(<?= $row['ID'] ?>, <?= $fee ?>)"
                                title="Consult: KES <?= number_format($feeRow['docFees'],2) ?> + Medicine: KES <?= number_format($feeRow['medicine_total'],2) ?>"
                                data-id="<?= $row['ID'] ?>">
                                <i class="fa-brands fa-android"></i> Pay KES <?= number_format($fee,2) ?>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile;
                    if ($pc===0): ?>
                    <tr><td colspan="8" style="text-align:center;padding:28px;color:var(--muted)">No prescriptions yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div><!-- /content -->
</div><!-- /main -->

<script>
// ── Notification bell ──
function toggleNotif() {
    document.getElementById('notifDropdown').classList.toggle('open');
}
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('notifWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('notifDropdown').classList.remove('open');
    }
});

// ── Tab switching ──
function showTab(tab) {
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('nav-' + tab).classList.add('active');
    const titles = {
        dash: 'Dashboard <span>Overview</span>',
        book: 'Book <span>Appointment</span>',
        history: 'My <span>Appointments</span>',
        prescriptions: 'Prescriptions <span>& Bills</span>',
    };
    document.getElementById('topbarTitle').innerHTML = titles[tab] || tab;
}

// ── Doctor spec filter ──
document.getElementById('spec').onchange = function() {
    let spec = this.value;
    let opts = [...document.getElementById('doctor').options];
    opts.forEach(el => {
        el.style.display = (el.getAttribute('data-spec') === spec || el.value === '') ? '' : 'none';
    });
    document.getElementById('doctor').value = '';
    document.getElementById('docFees').textContent = '—';
    document.getElementById('docFeesHidden').value = '';
};

document.getElementById('doctor').onchange = function() {
    let fee = this.options[this.selectedIndex].getAttribute('data-value');
    document.getElementById('docFees').textContent = fee ? 'KES ' + parseInt(fee).toLocaleString() : '—';
    document.getElementById('docFeesHidden').value = fee || '';
};

// ── M-Pesa Modal ──
let currentBillId = null;
let currentFee    = null;

function openMpesa(appointmentId, fee) {
    currentBillId = appointmentId;
    currentFee = fee;
    document.getElementById('modalAmount').textContent = 'KES ' + parseFloat(fee).toLocaleString('en-KE', {minimumFractionDigits:2});
    document.getElementById('mpesaStep1').style.display = 'block';
    document.getElementById('mpesaStep2').style.display = 'none';
    document.getElementById('mpesaStep3').style.display = 'none';
    document.getElementById('mpesaPhone').value = '';
    document.getElementById('mpesaModal').classList.add('open');
}

function closeMpesa() {
    document.getElementById('mpesaModal').classList.remove('open');
}

function sendSTKPush() {
    const phone = document.getElementById('mpesaPhone').value.trim();
    if (!phone || phone.length < 9) {
        showToast('Please enter a valid phone number.', 'error');
        return;
    }
    // Format display number
    let displayPhone = phone.startsWith('0') ? '+254' + phone.slice(1) : phone;
    document.getElementById('stkPhone').textContent = displayPhone;
    document.getElementById('mpesaStep1').style.display = 'none';
    document.getElementById('mpesaStep2').style.display = 'block';

    // Simulate STK push delay (3–5 seconds)
    setTimeout(() => {
        document.getElementById('mpesaStep2').style.display = 'none';
        document.getElementById('mpesaStep3').style.display = 'block';

        // Generate dummy M-Pesa ref
        const ref = 'QKJ' + Math.random().toString(36).substr(2,7).toUpperCase();
        document.getElementById('mpesaRef').innerHTML =
            '<i class="fa-solid fa-receipt" style="margin-right:6px"></i>M-Pesa Ref: <strong>' + ref + '</strong>';

        // Set download link
        document.getElementById('billDownloadBtn').href =
            'patient-panel.php?ID=' + currentBillId + '&generate_bill=1';
    }, 4000);
}

// Close modal on overlay click
document.getElementById('mpesaModal').addEventListener('click', function(e) {
    if (e.target === this) closeMpesa();
});

// ── Toast ──
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