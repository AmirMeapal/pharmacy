<?php
    session_start();

    include("connection.php"); 
    include("functions.php");

    $user_data = check_login();
    $user_prives = check_privs();
    if (!$user_prives['update_user']) {
        header("Location: index.php");
        exit();
    }
    $conn = new PDO($dsn, $user, $pass);
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_name = filter_input(INPUT_POST,'user_name',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST,'password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_role = filter_input(INPUT_POST,'role',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if (!empty($user_name) && !empty($password) && !is_numeric($user_name) && is_numeric($id)) {
            try {
            $query = "update users set user_name= :name , user_role= :role , password= :password where id = :id ";
            $stmt = $conn->prepare($query);

			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':name', $user_name);
			$stmt->bindParam(':password', $password);
			$stmt->bindParam(':role', $user_role);

            $stmt->execute();
            
            header("Location: users.php");
            die('Added successful!');
            } catch (\Throwable $th) {
                throw $th;
                echo "something went wrong";
            }
        }else{
            echo "Please enter some valid information";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update user</title>
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
		<h1>Update User</h1>
		<form action="#" method="post">
            <label for="user_id"><b>User DB ID</b></label>
			<input type="number" placeholder="Enter User ID" name="id" required></br></br>
			
            <label for="username"><b>Username</b></label>
			<input type="text" placeholder="Enter Username" name="user_name" required>

			<label for="password"><b>Password</b></label>
			<input type="password" placeholder="Enter Password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="admin">Admin</option>
                <option value="supplier">Supplier</option>
                <option value="pharmacist">Pharmacist</option>
                <option value="client">Client</option>
            </select></br></br>

			<button type="submit">Update</button>
		</form>
        <a href="index.php">Home</a>
	</div>
</body>
</html>