<?php
// func.php — Patient Login (with password_verify)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

if (isset($_POST['patsub'])) {
    $email    = $_POST['email'];
    $password = $_POST['password2'];

    // Fetch patient by email ONLY — do NOT check password in SQL
    // This is because the stored password is a hash, not plain text
    // We cannot compare hashes in SQL — we use password_verify() in PHP
    $query  = "SELECT * FROM patreg WHERE email='$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // password_verify() takes:
        // Argument 1: the plain text password the user just typed
        // Argument 2: the hash stored in the database
        // It re-hashes the plain text and compares — returns true or false
        if (password_verify($password, $row['password'])) {
            // Passwords match — set session variables
            $_SESSION['pid']      = $row['pid'];
            $_SESSION['username'] = $row['fname'] . " " . $row['lname'];
            $_SESSION['fname']    = $row['fname'];
            $_SESSION['lname']    = $row['lname'];
            $_SESSION['gender']   = $row['gender'];
            $_SESSION['contact']  = $row['contact'];
            $_SESSION['email']    = $row['email'];
            header("Location: patient-panel.php");
            exit;
        } else {
            // Email found but password wrong
            echo "<script>alert('Invalid email or password. Please try again.');
                  window.location.href = 'index.php';</script>";
        }
    } else {
        // No account with that email
        echo "<script>alert('Invalid email or password. Please try again.');
              window.location.href = 'index.php';</script>";
    }
}

if (isset($_POST['update_data'])) {
    $contact = $_POST['contact'];
    $status  = $_POST['status'];
    $query   = "UPDATE appointmenttb SET payment='$status' WHERE contact='$contact'";
    $result  = mysqli_query($con, $query);
    if ($result) {
        header("Location: updated.php");
        exit;
    }
}

function display_specs() {
    global $con;
    $query  = "SELECT DISTINCT spec FROM doctb ORDER BY spec ASC";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_array($result)) {
        $spec = htmlspecialchars($row['spec']);
        echo '<option value="' . $spec . '">' . $spec . '</option>';
    }
}

function display_docs() {
    global $con;
    $query  = "SELECT username, docFees, spec FROM doctb ORDER BY username ASC";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_array($result)) {
        $username = htmlspecialchars($row['username']);
        $price    = htmlspecialchars($row['docFees']);
        $spec     = htmlspecialchars($row['spec']);
        echo '<option value="' . $username . '" data-value="' . $price
           . '" data-spec="' . $spec . '">'
           . $username . ' — KES ' . number_format($price) . '</option>';
    }
}
?>