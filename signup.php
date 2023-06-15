<?php
session_start();

include("connection.php");
include("functions.php");
require_once 'vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] == "POST") { 

	$user_name = filter_input(INPUT_POST,'user_name',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	
	if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        $conn = new PDO($dsn, $user, $pass);
		$query = "select * from users where user_name = :n AND password = :p limit 1";
        $stmt = $conn->prepare($query);
		$stmt->bindParam(':n', $user_name); 
		$stmt->bindParam(':p', $password);
        $stmt->execute();
        $user = $stmt->fetch();
		if($user){
			echo "This user already exits";
			exit();
		}
		try {
		$user_id = random_num(20);
		$query = "insert into users (user_id, user_name, password) values (:id, :name, :password)";
        $stmt = $conn->prepare($query);
		
		$stmt->bindParam(':id', $user_id);
		$stmt->bindParam(':name', $user_name);
		$stmt->bindParam(':password', $password);

        $stmt->execute();
		
		header("Location: login.php");
		die('signup successful!');
        } catch (\Throwable $th) {
            throw $th;
            echo "something went wrong";
        }
	}else{
		echo "Please enter some valid information";
	}
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
		}
		.container {
			margin: 50px auto;
			width: 400px;
			background-color: #fff;
			padding: 20px;
			border-radius: 5px;
			box-shadow: 0px 0px 10px #aaa;
		}
		h1 {
			text-align: center;
			margin-bottom: 30px;
		}
		input[type=text], input[type=password] {
			width: 100%;
			padding: 12px 20px;
			margin: 8px 0;
			display: inline-block;
			border: 1px solid #ccc;
			border-radius: 4px;
			box-sizing: border-box;
			font-size: 16px;
		}
		button {
			background-color: #6666ff;
			color: white;
			padding: 14px 20px;
			margin: 8px 0;
            margin-bottom: 30px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			width: 100%;
			font-size: 16px;
		}
		button:hover {
			background-color: #0000b3;
		}
		#google{
			background-color: #6666ff;
			color: white;
			padding: 14px 20px;
			margin: 8px 0;
            margin-bottom: 30px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			width: 100%;
			font-size: 16px;
		}
        a {
			background-color: #4CAF50;
			color: white;
			padding: 14px 20px;
			margin: 8px 0;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			width: 100%;
			font-size: 16px;
		}
		.container label {
			font-size: 16px;
			font-weight: bold;
		}
		.container p {
			text-align: center;
			font-size: 14px;
			color: red;
			margin: 0;
		}
		@media screen and (max-width: 600px) {
			.container {
				width: 100%;
			}
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>Sign Up Form</h1>
		<form action="#" method="post">
			<label for="username"><b>Username</b></label>
			<input type="text" placeholder="Enter Username" name="user_name" required>

			<label for="password"><b>Password</b></label>
			<input type="password" placeholder="Enter Password" name="password" required>

			<button type="submit">Sign Up</button>
		</form>
		<a href="login.php">Login</a>
		<?php  echo "<a id ='google' href='".$client->createAuthUrl()."'>Google Sign up</a>" ?>
	</div>
</body>
</html>