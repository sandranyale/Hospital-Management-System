<?php
// func3.php — Admin Login (with password_verify)
session_start();
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

if (isset($_POST['adsub'])) {
    $username = $_POST['username1'];
    $password = $_POST['password2'];

    // Fetch admin by username ONLY
    $query  = "SELECT * FROM admintb WHERE username='$username' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // password_verify() compares typed password against stored hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: admin-panel1.php");
            exit;
        } else {
            echo "<script>alert('Invalid Username or Password. Try Again!');
                  window.location.href = 'index.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid Username or Password. Try Again!');
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
?>