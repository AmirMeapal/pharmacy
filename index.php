<?php

    session_start();

    include("connection.php"); 
    include("functions.php");
    require_once 'vendor/autoload.php';


   
    $conn = new PDO($dsn, $user, $pass);

    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token['access_token']);
      
        // get profile info
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        // $email =  $google_account_info->email;
        // $name =  $google_account_info->name;
    
    
        $userinfo = [
          'email' => $google_account_info['email'],
          'token' => $google_account_info['id'],
        ];
    
        $query = "select * from users where user_name = :n limit 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':n', $userinfo['email']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user){
          $user_data = $user;
          $role = $user_data['user_role'];
          $query = "select * from roles where role = :role limit 1";
          $stmt = $conn->prepare($query);
          $stmt->bindParam(':role', $role);
          $stmt->execute();
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          if($result){
                 $user_prives = $result; 
              }
        }else{
          try {
		      $user_id = random_num(20);
          $query = "insert into users (user_id, user_name, password) values (:id, :name, :password)";
          $stmt = $conn->prepare($query);
          $stmt->bindParam(':id', $user_id);
          $stmt->bindParam(':name', $userinfo['email']);
          $stmt->bindParam(':password', $userinfo['token']);
          $stmt->execute();
         } catch (Throwable $th) {
          echo "Something went wrong";
          throw $th;
         }
         
        }
        $_SESSION['user_token'] = $token;
      }else{
        $user_data = check_login();
        $user_prives = check_privs();
      }
      
      
if ($_SERVER['REQUEST_METHOD'] == "POST") { 
	$current_username = filter_input(INPUT_POST,'current_username',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $current_password = filter_input(INPUT_POST,'current_password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $new_user_name = filter_input(INPUT_POST,'new_username',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$new_password = filter_input(INPUT_POST,'new_password',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	
	if (!empty($current_username) && !empty($current_password) && !empty($new_user_name) && 
    !empty($new_password) && !is_numeric($current_username) && !is_numeric($new_user_name)) {
		
        if ($current_username === $user_data['user_name'] && $current_password === $user_data['password']){
           try {
            $id = $user_data['user_id'];
            $query = "update users set user_name= :name ,password= :password where user_id = :id ";
            $stmt = $conn->prepare($query);
		    
            $stmt->bindParam(':name', $new_user_name);
		    $stmt->bindParam(':password', $new_password);
		    $stmt->bindParam(':id', $id);
            
            $stmt->execute();
            header("Location: index.php");
            die('updated successful!');
           } catch (Throwable $th) {
            echo "something went wrong, please retry";
            throw $th;
           }
        }
        else{
            echo "Please enter some valid information";
        }
	
}else{
    echo "Please fill out everything properly";
}}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>
<body>
    <a href="logout.php">logout</a></br></br>
    <a href="delete.php">Delete My Account</a></br></br>
	<?php if ($user_prives['view_users']) {
		echo '<a href="users.php">View Users</a></br></br>';
	} 
    if ($user_prives['add_user']) {
		echo '<a href="add_user.php">Add user</a></br></br>';
	} 
    if ($user_prives['delete_user']) {
		echo '<a href="delete_user.php">Delete user</a></br></br>';
	}
    if ($user_prives['update_user']) {
		echo '<a href="update_user.php">Update user info</a></br></br>';
	}
    if ($user_prives['view_products']) {
		echo '<a href="products.php">view products</a></br></br>';
	}
    ?>
    <h1>This is the Homepage</h1>
    <h2>Hello, <?php echo $user_data['user_name']; ?></h2>
    <h3>Your role is <?php echo $user_data['user_role']; ?></h3>
    <h3>Privs: <?php  echo $user_prives['role']; ?></h3>
    <h1>Change Username/Password</h1>
	<form action="#" method="post">
		<label for="current_username">Current Username:</label>
		<input type="text" id="current_username" name="current_username"><br><br>
		<label for="current_password">Current Password:</label>
		<input type="password" id="current_password" name="current_password"><br><br>
		<label for="new_username">New Username:</label>
		<input type="text" id="new_username" name="new_username"><br><br>
		<label for="new_password">New Password:</label>
		<input type="password" id="new_password" name="new_password"><br><br>
		<input type="submit" value="Submit">
	</form>
</body>
</html>