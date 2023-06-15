<?php
session_start();

include("connection.php");
include("functions.php");

if (isset($_SESSION['user_id']) && $_SESSION['logged_in'] === true) {
    $user_id = $_SESSION['user_id'];
    $conn = new PDO($dsn, $user, $pass);
    try {
        $query = "delete from users where user_id = :id limit 1";
        $stmt = $conn->prepare($query);
		$stmt->bindParam(':id', $user_id);
        $stmt->execute();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['role']);
        $_SESSION['logged_in'] = false;
        header("Location: login.php");
    } catch (Throwable $th) {
        throw $th;
        echo "Something went wrong!";
    }
}
