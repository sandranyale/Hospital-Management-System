<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

if (isset($_POST['patsub1'])) {
    $fname     = $_POST['fname'];
    $lname     = $_POST['lname'];
    $gender    = $_POST['gender'];
    $email     = $_POST['email'];
    $contact   = $_POST['contact'];
    $password  = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password == $cpassword) {

        // password_hash() applies bcrypt algorithm to the password
        // PASSWORD_DEFAULT uses bcrypt — the strongest default algorithm in PHP
        // It automatically generates a salt (random data) and embeds it in the hash
        // The result looks like: $2y$10$abcdefghijk... (60+ characters)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        
        // cpassword column stores the same hash (we don't need to store it but keeping for DB compatibility)
        $query = "INSERT INTO patreg(fname,lname,gender,email,contact,password,cpassword)
                  VALUES ('$fname','$lname','$gender','$email','$contact','$hashed_password','$hashed_password')";

        $result = mysqli_query($con, $query);

        if ($result) {
            // Fetch the newly inserted patient to get their pid
            $new_patient = mysqli_fetch_assoc(mysqli_query($con,
                "SELECT pid FROM patreg WHERE email='$email' LIMIT 1"));

            $_SESSION['pid']      = $new_patient['pid'];
            $_SESSION['username'] = $fname . " " . $lname;
            $_SESSION['fname']    = $fname;
            $_SESSION['lname']    = $lname;
            $_SESSION['gender']   = $gender;
            $_SESSION['contact']  = $contact;
            $_SESSION['email']    = $email;
            header("Location: patient-panel.php");
            exit;
        }
    } else {
        header("Location: error1.php");
        exit;
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