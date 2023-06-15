<?php
    session_start();

    include("connection.php"); 
    include("functions.php");

    $user_data = check_login();
    $user_prives = check_privs();
    if (!$user_prives['delete_user']) {
        header("Location: index.php");
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") { 

        $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if (!empty($id) && is_numeric($id)) {
            try {
            $conn = new PDO($dsn, $user, $pass);
            $query = "delete from users where id = :id limit 1";
            $stmt = $conn->prepare($query);
			$stmt->bindParam(':id', $id);
            $stmt->execute();
            
            header("Location: users.php");
            die('deleted successful!');
            } catch (\Throwable $th) {
                throw $th;
                echo "something went wrong";
            }
        }else{
            echo "Please enter a valid ID";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete user</title>
</head>
<body>
    <form action="#" method="post">
			<label for="username"><b>ID of user you want to delete</b></label></br></br>
			<input type="number" placeholder="Enter ID" name="id" required></br></br>

			<button type="submit">Delete</button></br></br>
		</form>
        <a href="users.php">Back</a>
</body>
</html>