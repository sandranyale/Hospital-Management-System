<?php
// newfunc.php — Afya One Hospital Management System
// display_specs() and display_docs() are defined in func.php — not redeclared here.

if (!isset($con) || !$con) {
    $con = mysqli_connect("localhost", "root", "", "myhmsdb");
}
?>