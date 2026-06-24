<!DOCTYPE html>
<?php
include('func1.php');
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

$pid = $ID = $appdate = $apptime = $fname = $lname = '';
$doctor = $_SESSION['dname'];

if (isset($_GET['pid'], $_GET['ID'], $_GET['appdate'], $_GET['apptime'], $_GET['fname'], $_GET['lname'])) {
    $pid     = $_GET['pid'];
    $ID      = $_GET['ID'];
    $fname   = urldecode($_GET['fname']);
    $lname   = urldecode($_GET['lname']);
    $appdate = $_GET['appdate'];
    $apptime = $_GET['apptime'];
}

if (isset($_POST['prescribe'], $_POST['pid'], $_POST['ID'])) {
    $appdate      = $_POST['appdate'];
    $apptime      = $_POST['apptime'];
    $disease      = $_POST['disease'];
    $allergy      = $_POST['allergy'];
    $fname        = $_POST['fname'];
    $lname        = $_POST['lname'];
    $pid          = $_POST['pid'];
    $ID           = $_POST['ID'];
    $prescription = $_POST['prescription'];
    $med_ids      = $_POST['med_ids']    ?? [];
    $quantities   = $_POST['quantities'] ?? [];
    $medicine_total = 0;

    $query = mysqli_query($con, "INSERT INTO prestb(doctor,pid,ID,fname,lname,appdate,apptime,disease,allergy,prescription,medicine_total)
        VALUES ('$doctor','$pid','$ID','$fname','$lname','$appdate','$apptime','$disease','$allergy','$prescription','0')");

    if ($query) {
        foreach ($med_ids as $idx => $med_id) {
            if (empty($med_id)) continue;
            $qty = max(1, intval($quantities[$idx] ?? 1));
            $med = mysqli_fetch_assoc(mysqli_query($con, "SELECT name, price FROM pharmacytb WHERE med_id='$med_id'"));
            if ($med) {
                $unit_price = $med['price'];
                $total      = $unit_price * $qty;
                $medicine_total += $total;
                $med_name = mysqli_real_escape_string($con, $med['name']);
                mysqli_query($con, "INSERT INTO prescriptionmeds(pres_id,pid,med_id,med_name,quantity,unit_price,total)
                    VALUES('$ID','$pid','$med_id','$med_name','$qty','$unit_price','$total')");
            }
        }
        mysqli_query($con, "UPDATE prestb SET medicine_total='$medicine_total' WHERE ID='$ID' AND pid='$pid'");
        echo "<script>document.addEventListener('DOMContentLoaded',function(){
            showToast('Prescription saved! Medicine total: KES " . number_format($medicine_total,2) . "','success');
            setTimeout(()=>{ window.location.href='doctor-panel.php'; }, 2500);
        });</script>";
    } else {
        echo "<script>document.addEventListener('DOMContentLoaded',function(){
            showToast('Unable to save. Please try again.','error');
        });</script>";
    }
}

// Fetch consultation fee for this appointment
$feeRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT docFees FROM appointmenttb WHERE ID='$ID'"));
$consultFeeVal = floatval($feeRow['docFees'] ?? 0);

// Load medicines grouped by category
$medsResult = mysqli_query($con, "SELECT * FROM pharmacytb ORDER BY category, name");
$medicines  = [];
while ($row = mysqli_fetch_assoc($medsResult)) {
    $medicines[$row['category']][] = $row;
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Afya One — Prescribe</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
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
            --green:     #10b981;
            --red:       #ef4444;
            --amber:     #f59e0b;
            --teal:      #0d9488;
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'DM Sans',sans-serif; background:var(--bg); color:var(--navy); min-height:100vh; }

        /* TOPBAR */
        .topbar { background:var(--navy); padding:0 32px; height:62px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:100; box-shadow:0 2px 20px rgba(0,0,0,.2); }
        .topbar-brand { display:flex; align-items:center; gap:10px; font-family:'Syne',sans-serif; font-size:18px; font-weight:800; color:#fff; }
        .topbar-brand .brand-icon { width:34px; height:34px; background:var(--indigo); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:15px; }
        .topbar-brand span { color:#818cf8; }
        .doc-chip { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); border-radius:20px; padding:5px 12px; font-size:13px; color:rgba(255,255,255,.8); display:flex; align-items:center; gap:6px; }
        .topbar-right { display:flex; align-items:center; gap:10px; }
        .btn-back { display:inline-flex; align-items:center; gap:7px; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; color:rgba(255,255,255,.6); background:rgba(255,255,255,.07); transition:all .2s; }
        .btn-back:hover { background:rgba(255,255,255,.12); color:#fff; }
        .btn-logout { color:#f87171; background:rgba(239,68,68,.1); display:inline-flex; align-items:center; gap:7px; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; }
        .btn-logout:hover { background:rgba(239,68,68,.2); }

        /* LAYOUT */
        .page-wrap { padding:28px 32px; max-width:1160px; margin:0 auto; }

        /* PATIENT CARD */
        .patient-card { background:var(--white); border:1px solid var(--border); border-radius:16px; padding:20px 26px; margin-bottom:24px; display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
        .patient-avatar { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,var(--indigo),#818cf8); display:flex; align-items:center; justify-content:center; font-family:'Syne',sans-serif; font-size:20px; font-weight:800; color:#fff; flex-shrink:0; }
        .patient-name { font-family:'Syne',sans-serif; font-size:17px; font-weight:700; }
        .patient-meta { display:flex; gap:14px; flex-wrap:wrap; margin-top:5px; }
        .meta-item { font-size:13px; color:var(--muted); display:flex; align-items:center; gap:5px; }
        .meta-item i { color:var(--indigo); font-size:12px; }
        .patient-badges { margin-left:auto; display:flex; gap:8px; }
        .badge { display:inline-flex; align-items:center; gap:5px; padding:5px 12px; border-radius:20px; font-size:12px; font-weight:600; }
        .badge-date { background:#d1fae5; color:#065f46; }
        .badge-time { background:var(--indigo-lt); color:var(--indigo-dk); }

        /* GRID */
        .main-grid { display:grid; grid-template-columns:1fr 340px; gap:22px; align-items:start; }

        /* PRESCRIPTION CARD */
        .presc-card { background:var(--white); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .presc-header { background:linear-gradient(135deg,var(--indigo),var(--indigo-dk)); padding:20px 26px; display:flex; align-items:center; gap:12px; }
        .presc-header-icon { width:42px; height:42px; background:rgba(255,255,255,.15); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:18px; color:#fff; }
        .presc-header h2 { font-family:'Syne',sans-serif; font-size:18px; font-weight:800; color:#fff; }
        .presc-header p { font-size:13px; color:rgba(255,255,255,.65); margin-top:1px; }
        .presc-body { padding:24px; }

        .section-title { font-family:'Syne',sans-serif; font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.8px; margin:20px 0 12px; padding-bottom:8px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:8px; }
        .section-title:first-child { margin-top:0; }
        .section-title i { color:var(--indigo); }
        .form-label { display:block; font-size:13px; font-weight:600; color:var(--slate); margin-bottom:6px; }
        .form-label span { color:var(--red); }
        .form-group { margin-bottom:16px; }

        textarea { width:100%; padding:12px 14px; border:1.5px solid var(--border); border-radius:10px; font-size:14px; font-family:'DM Sans',sans-serif; color:var(--navy); resize:vertical; outline:none; transition:border-color .2s,box-shadow .2s; line-height:1.6; background:#fafbfc; }
        textarea:focus { border-color:var(--indigo); box-shadow:0 0 0 3px rgba(79,70,229,.1); background:#fff; }
        textarea::placeholder { color:#b0bec5; }

        .quick-tags { display:flex; flex-wrap:wrap; gap:5px; margin-bottom:8px; }
        .quick-tag { background:var(--indigo-lt); color:var(--indigo-dk); border:none; border-radius:20px; padding:3px 10px; font-size:11px; font-weight:600; cursor:pointer; transition:all .2s; font-family:'DM Sans',sans-serif; }
        .quick-tag:hover { background:var(--indigo); color:#fff; }

        .action-bar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; padding:18px 24px; border-top:1px solid var(--border); background:#fafbfc; }
        .action-hint { font-size:13px; color:var(--muted); display:flex; align-items:center; gap:6px; }
        .action-hint i { color:var(--amber); }
        .btn-submit { background:var(--indigo); color:#fff; border:none; border-radius:10px; padding:12px 28px; font-size:14px; font-weight:700; cursor:pointer; font-family:'DM Sans',sans-serif; display:inline-flex; align-items:center; gap:8px; transition:all .2s; }
        .btn-submit:hover { background:var(--indigo-dk); transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,.25); }

        /* PHARMACY PANEL */
        .pharmacy-panel { display:flex; flex-direction:column; gap:16px; }
        .pharmacy-card { background:var(--white); border:1px solid var(--border); border-radius:16px; overflow:hidden; }
        .pharmacy-header { background:linear-gradient(135deg,var(--teal),#0f766e); padding:18px 20px; display:flex; align-items:center; gap:10px; }
        .pharmacy-header i { font-size:20px; color:#fff; }
        .pharmacy-header h3 { font-family:'Syne',sans-serif; font-size:16px; font-weight:800; color:#fff; }
        .pharmacy-header p { font-size:12px; color:rgba(255,255,255,.7); margin-top:1px; }
        .pharmacy-body { padding:16px; }

        .med-search { width:100%; padding:9px 12px; border:1.5px solid var(--border); border-radius:9px; font-size:13px; font-family:'DM Sans',sans-serif; outline:none; transition:border-color .2s; margin-bottom:10px; }
        .med-search:focus { border-color:var(--teal); }

        .cat-tabs { display:flex; flex-wrap:wrap; gap:4px; margin-bottom:10px; }
        .cat-tab { padding:4px 10px; border-radius:14px; font-size:11px; font-weight:600; cursor:pointer; background:var(--bg); color:var(--muted); border:1px solid var(--border); transition:all .2s; }
        .cat-tab.active, .cat-tab:hover { background:var(--teal); color:#fff; border-color:var(--teal); }

        .med-list { max-height:280px; overflow-y:auto; display:flex; flex-direction:column; gap:5px; }
        .med-item { display:flex; align-items:center; gap:10px; padding:9px 12px; border:1.5px solid var(--border); border-radius:10px; cursor:pointer; transition:all .2s; background:#fff; }
        .med-item:hover { border-color:var(--teal); background:#f0fdfa; }
        .med-item.selected { border-color:var(--teal); background:#f0fdfa; }
        .med-check { width:18px; height:18px; border-radius:5px; border:2px solid var(--border); display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all .2s; color:transparent; }
        .med-item.selected .med-check { background:var(--teal); border-color:var(--teal); color:#fff; }
        .med-info { flex:1; }
        .med-info strong { font-size:13px; display:block; }
        .med-info span { font-size:11px; color:var(--muted); }
        .med-price { font-size:13px; font-weight:700; color:var(--teal); white-space:nowrap; }

        /* Selected medicines */
        .selected-meds { margin-top:14px; display:none; }
        .selected-header { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.6px; margin-bottom:8px; }
        .selected-list { display:flex; flex-direction:column; gap:6px; }
        .selected-row { display:flex; align-items:center; gap:8px; padding:8px 10px; background:var(--bg); border-radius:8px; border:1px solid var(--border); }
        .selected-row .med-name { flex:1; font-size:13px; font-weight:600; }
        .qty-input { width:50px; padding:4px 6px; border:1.5px solid var(--border); border-radius:6px; font-size:13px; text-align:center; outline:none; font-family:'DM Sans',sans-serif; }
        .qty-input:focus { border-color:var(--indigo); }
        .row-total { font-size:13px; font-weight:700; color:var(--teal); min-width:72px; text-align:right; }
        .remove-med { background:none; border:none; color:var(--red); cursor:pointer; font-size:14px; padding:2px 4px; }

        /* Bill summary */
        .bill-card { background:var(--white); border:1px solid var(--border); border-radius:16px; padding:18px; }
        .bill-card h3 { font-family:'Syne',sans-serif; font-size:15px; font-weight:700; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
        .bill-card h3 i { color:var(--teal); }
        .bill-line { display:flex; justify-content:space-between; font-size:14px; padding:8px 0; border-bottom:1px solid var(--border); color:var(--muted); }
        .bill-line:last-child { border-bottom:none; }
        .bill-total { display:flex; justify-content:space-between; font-size:16px; font-weight:800; padding:10px 0 0; border-top:2px solid var(--border); margin-top:4px; }
        .bill-total span:last-child { color:var(--teal); }

        /* TOAST */
        .toast-container { position:fixed; top:20px; right:20px; z-index:9999; display:flex; flex-direction:column; gap:8px; }
        .toast-msg { background:var(--navy); color:#fff; padding:12px 18px; border-radius:12px; font-size:14px; display:flex; align-items:center; gap:10px; box-shadow:0 8px 30px rgba(0,0,0,.2); animation:slideIn .3s ease; min-width:280px; }
        .toast-msg.success { border-left:4px solid var(--green); }
        .toast-msg.error   { border-left:4px solid var(--red); }
        @keyframes slideIn { from{opacity:0;transform:translateX(40px)} to{opacity:1;transform:translateX(0)} }

        @media(max-width:900px) { .main-grid { grid-template-columns:1fr; } .page-wrap { padding:20px 16px; } }
    </style>
</head>
<body>
<div class="toast-container" id="toastContainer"></div>

<!-- TOPBAR -->
<div class="topbar">
    <div class="topbar-brand">
        <div class="brand-icon"><i class="fa-solid fa-stethoscope" style="color:#fff"></i></div>
        Afya <span>One</span>
    </div>
    <div class="topbar-right">
        <div class="doc-chip"><i class="fa-solid fa-user-doctor"></i> Dr. <?php echo htmlspecialchars($doctor); ?></div>
        <a href="doctor-panel.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back</a>
        <a href="logout1.php" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
    </div>
</div>

<form method="post" action="prescribe.php" id="prescribeForm">
<div class="page-wrap">

    <!-- Patient Card -->
    <div class="patient-card">
        <div class="patient-avatar"><?php echo strtoupper(substr($fname,0,1)); ?></div>
        <div>
            <div class="patient-name"><?php echo htmlspecialchars($fname.' '.$lname); ?></div>
            <div class="patient-meta">
                <div class="meta-item"><i class="fa-solid fa-id-badge"></i> Patient #<?php echo $pid; ?></div>
                <div class="meta-item"><i class="fa-solid fa-hashtag"></i> Appointment #<?php echo $ID; ?></div>
            </div>
        </div>
        <div class="patient-badges">
            <span class="badge badge-date"><i class="fa-regular fa-calendar"></i> <?php echo date('M j, Y', strtotime($appdate)); ?></span>
            <span class="badge badge-time"><i class="fa-regular fa-clock"></i> <?php echo date('g:i A', strtotime($apptime)); ?></span>
        </div>
    </div>

    <div class="main-grid">

        <!-- LEFT: Prescription -->
        <div>
            <div class="presc-card">
                <div class="presc-header">
                    <div class="presc-header-icon"><i class="fa-solid fa-file-medical"></i></div>
                    <div>
                        <h2>Issue Prescription</h2>
                        <p>Clinical notes for <?php echo htmlspecialchars($fname); ?></p>
                    </div>
                </div>
                <div class="presc-body">

                    <div class="section-title"><i class="fa-solid fa-microscope"></i> Diagnosis</div>
                    <div class="form-group">
                        <label class="form-label">Disease / Condition <span>*</span></label>
                        <div class="quick-tags">
                            <button type="button" class="quick-tag" onclick="appendText('disease','Hypertension')">Hypertension</button>
                            <button type="button" class="quick-tag" onclick="appendText('disease','Type 2 Diabetes')">Diabetes</button>
                            <button type="button" class="quick-tag" onclick="appendText('disease','Malaria')">Malaria</button>
                            <button type="button" class="quick-tag" onclick="appendText('disease','Typhoid Fever')">Typhoid</button>
                            <button type="button" class="quick-tag" onclick="appendText('disease','Upper Respiratory Infection')">URI</button>
                            <button type="button" class="quick-tag" onclick="appendText('disease','Gastritis')">Gastritis</button>
                        </div>
                        <textarea id="disease" name="disease" rows="3" placeholder="Describe the diagnosis in detail..." required></textarea>
                    </div>

                    <div class="section-title"><i class="fa-solid fa-triangle-exclamation"></i> Allergies & Contraindications</div>
                    <div class="form-group">
                        <div class="quick-tags">
                            <button type="button" class="quick-tag" onclick="appendText('allergy','None known')">None known</button>
                            <button type="button" class="quick-tag" onclick="appendText('allergy','Penicillin')">Penicillin</button>
                            <button type="button" class="quick-tag" onclick="appendText('allergy','Sulfonamides')">Sulfonamides</button>
                            <button type="button" class="quick-tag" onclick="appendText('allergy','NSAIDs')">NSAIDs</button>
                            <button type="button" class="quick-tag" onclick="appendText('allergy','Aspirin')">Aspirin</button>
                        </div>
                        <textarea id="allergy" name="allergy" rows="2" placeholder="Known allergies or drug contraindications..."></textarea>
                    </div>

                    <div class="section-title"><i class="fa-solid fa-notes-medical"></i> Clinical Instructions</div>
                    <div class="form-group">
                        <label class="form-label">Additional Instructions / Notes <span>*</span></label>
                        <div class="quick-tags">
                            <button type="button" class="quick-tag" onclick="appendText('prescription','Bed rest for 3-5 days.')">Bed Rest</button>
                            <button type="button" class="quick-tag" onclick="appendText('prescription','Drink plenty of fluids.')">Fluids</button>
                            <button type="button" class="quick-tag" onclick="appendText('prescription','Avoid spicy foods.')">Avoid Spicy</button>
                            <button type="button" class="quick-tag" onclick="appendText('prescription','Return for review in 1 week.')">Follow Up</button>
                            <button type="button" class="quick-tag" onclick="appendText('prescription','Take medication after meals.')">After Meals</button>
                        </div>
                        <textarea id="prescription" name="prescription" rows="5"
                            placeholder="e.g. Bed rest for 3 days. Drink plenty of fluids. Return for review in 5 days." required></textarea>
                    </div>

                </div>
                <div class="action-bar">
                    <div class="action-hint"><i class="fa-solid fa-triangle-exclamation"></i> Saved permanently to patient record</div>
                    <button type="submit" name="prescribe" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Save Prescription
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT: Pharmacy + Bill -->
        <div class="pharmacy-panel">

            <!-- Medicine Selector -->
            <div class="pharmacy-card">
                <div class="pharmacy-header">
                    <i class="fa-solid fa-pills"></i>
                    <div>
                        <h3>Pharmacy</h3>
                        <p>Select medicines for this patient</p>
                    </div>
                </div>
                <div class="pharmacy-body">
                    <input type="text" class="med-search" placeholder="Search medicine..." id="medSearch" oninput="filterMeds()">

                    <div class="cat-tabs" id="catTabs">
                        <div class="cat-tab active" onclick="filterCat('all',this)">All</div>
                        <?php foreach (array_keys($medicines) as $cat): ?>
                        <div class="cat-tab" onclick="filterCat('<?php echo $cat; ?>',this)"><?php echo $cat; ?></div>
                        <?php endforeach; ?>
                    </div>

                    <div class="med-list" id="medList">
                        <?php foreach ($medicines as $cat => $meds): ?>
                        <?php foreach ($meds as $med): ?>
                        <div class="med-item"
                             data-id="<?php echo $med['med_id']; ?>"
                             data-name="<?php echo htmlspecialchars($med['name']); ?>"
                             data-price="<?php echo $med['price']; ?>"
                             data-cat="<?php echo $med['category']; ?>"
                             onclick="toggleMed(this)">
                            <div class="med-check"><i class="fa-solid fa-check" style="font-size:10px"></i></div>
                            <div class="med-info">
                                <strong><?php echo htmlspecialchars($med['name']); ?></strong>
                                <span><?php echo $med['category']; ?> &bull; <?php echo $med['unit']; ?></span>
                            </div>
                            <div class="med-price">KES <?php echo number_format($med['price'],2); ?></div>
                        </div>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Selected list -->
                    <div class="selected-meds" id="selectedMeds">
                        <div class="selected-header">Selected Medicines</div>
                        <div class="selected-list" id="selectedList"></div>
                    </div>
                </div>
            </div>

            <!-- Bill Summary -->
            <div class="bill-card">
                <h3><i class="fa-solid fa-receipt"></i> Bill Summary</h3>
                <div class="bill-line">
                    <span>Consultation Fee</span>
                    <span id="consultFeeDisplay">KES <?php echo number_format($consultFeeVal,2); ?></span>
                </div>
                <div class="bill-line">
                    <span>Medicine Total</span>
                    <span id="medTotalDisplay">KES 0.00</span>
                </div>
                <div class="bill-total">
                    <span>Grand Total</span>
                    <span id="grandTotal">KES <?php echo number_format($consultFeeVal,2); ?></span>
                </div>
            </div>

        </div><!-- /pharmacy-panel -->

    </div><!-- /main-grid -->

    <!-- Hidden inputs for medicines -->
    <div id="hiddenMedInputs"></div>

    <!-- Hidden patient fields -->
    <input type="hidden" name="fname"   value="<?php echo htmlspecialchars($fname); ?>">
    <input type="hidden" name="lname"   value="<?php echo htmlspecialchars($lname); ?>">
    <input type="hidden" name="appdate" value="<?php echo htmlspecialchars($appdate); ?>">
    <input type="hidden" name="apptime" value="<?php echo htmlspecialchars($apptime); ?>">
    <input type="hidden" name="pid"     value="<?php echo htmlspecialchars($pid); ?>">
    <input type="hidden" name="ID"      value="<?php echo htmlspecialchars($ID); ?>">

</div><!-- /page-wrap -->
</form>

<script>
const selected = {};
const consultFeeVal = <?php echo $consultFeeVal; ?>;

function toggleMed(el) {
    const id    = el.dataset.id;
    const name  = el.dataset.name;
    const price = parseFloat(el.dataset.price);
    if (selected[id]) {
        delete selected[id];
        el.classList.remove('selected');
    } else {
        selected[id] = { name, price, qty: 1 };
        el.classList.add('selected');
    }
    renderSelected();
    updateBill();
}

function renderSelected() {
    const list   = document.getElementById('selectedList');
    const wrap   = document.getElementById('selectedMeds');
    const hidden = document.getElementById('hiddenMedInputs');
    list.innerHTML   = '';
    hidden.innerHTML = '';
    const ids = Object.keys(selected);
    wrap.style.display = ids.length ? 'block' : 'none';
    ids.forEach((id) => {
        const med      = selected[id];
        const rowTotal = (med.price * med.qty).toFixed(2);
        const row      = document.createElement('div');
        row.className  = 'selected-row';
        row.innerHTML  = `
            <span class="med-name">${med.name}</span>
            <input type="number" class="qty-input" value="${med.qty}" min="1" max="999"
                onchange="updateQty('${id}',this.value)" oninput="updateQty('${id}',this.value)">
            <span class="row-total" id="rowTotal_${id}">KES ${parseFloat(rowTotal).toLocaleString('en-KE',{minimumFractionDigits:2})}</span>
            <button type="button" class="remove-med" onclick="removeMed('${id}')"><i class="fa-solid fa-xmark"></i></button>`;
        list.appendChild(row);
        hidden.innerHTML += `<input type="hidden" name="med_ids[]" value="${id}">
            <input type="hidden" name="quantities[]" id="qty_${id}" value="${med.qty}">`;
    });
}

function updateQty(id, val) {
    const qty = Math.max(1, parseInt(val) || 1);
    selected[id].qty = qty;
    const rt = document.getElementById('rowTotal_' + id);
    if (rt) rt.textContent = 'KES ' + (selected[id].price * qty).toLocaleString('en-KE',{minimumFractionDigits:2});
    const hi = document.getElementById('qty_' + id);
    if (hi) hi.value = qty;
    updateBill();
}

function removeMed(id) {
    delete selected[id];
    const el = document.querySelector('.med-item[data-id="' + id + '"]');
    if (el) el.classList.remove('selected');
    renderSelected();
    updateBill();
}

function updateBill() {
    let medTotal = 0;
    Object.values(selected).forEach(m => medTotal += m.price * m.qty);
    const grand = consultFeeVal + medTotal;
    document.getElementById('medTotalDisplay').textContent = 'KES ' + medTotal.toLocaleString('en-KE',{minimumFractionDigits:2});
    document.getElementById('grandTotal').textContent      = 'KES ' + grand.toLocaleString('en-KE',{minimumFractionDigits:2});
}

function filterMeds() {
    const q = document.getElementById('medSearch').value.toLowerCase();
    document.querySelectorAll('.med-item').forEach(el => {
        el.style.display = el.dataset.name.toLowerCase().includes(q) ? 'flex' : 'none';
    });
}

function filterCat(cat, tabEl) {
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    tabEl.classList.add('active');
    document.querySelectorAll('.med-item').forEach(el => {
        el.style.display = (cat === 'all' || el.dataset.cat === cat) ? 'flex' : 'none';
    });
}

function appendText(id, text) {
    const el = document.getElementById(id);
    el.value = el.value ? el.value + '\n' + text : text;
    el.focus();
}

function showToast(message, type) {
    const container = document.getElementById('toastContainer');
    const toast     = document.createElement('div');
    toast.className = 'toast-msg ' + (type || 'success');
    const icons     = { success:'circle-check', error:'circle-xmark' };
    toast.innerHTML = '<i class="fa-solid fa-' + (icons[type]||'circle-check') + '"></i> ' + message;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}
</script>
</body>
</html>