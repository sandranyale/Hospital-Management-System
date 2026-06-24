<?php
// func1.php — Doctor Login (with password_verify)
session_start();
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

if (isset($_POST['docsub1'])) {
    $dname = $_POST['username3'];
    $dpass = $_POST['password3'];

    // Fetch doctor by username ONLY first
    // Same reason as patient login — stored password is a hash
    $query  = "SELECT * FROM doctb WHERE username='$dname' LIMIT 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // password_verify() compares typed password against stored hash
        if (password_verify($dpass, $row['password'])) {
            $_SESSION['dname'] = $row['username'];
            header("Location: doctor-panel.php");
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
?>