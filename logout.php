<?php
session_start();

if (isset($_SESSION['user_id']) &&  $_SESSION['logged_in'] === true) {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['role']);
    $_SESSION['logged_in'] = false;
}

header("Location: login.php");