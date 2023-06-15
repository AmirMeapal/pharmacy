<?php


function check_login(){ 
    
    if(isset($_SESSION['user_id']) && $_SESSION['logged_in'] === true){ 
        $dsn = 'mysql:host=localhost;dbname=login_db';
        $user = 'root';
        $pass = '';
        
        $conn = new PDO($dsn, $user, $pass);
        $id = $_SESSION['user_id'];
        $query = "select * from users where user_id = :id limit 1";
        $stmt = $conn->prepare($query);
		$stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
                $user_data = $result; 
                return $user_data;
            }
    }   

     header("Location: login.php");
     exit();
}

function check_privs(){ 
    
    if(isset($_SESSION['user_id']) && $_SESSION['logged_in'] === true){ 
        $dsn = 'mysql:host=localhost;dbname=login_db';
        $user = 'root';
        $pass = '';
        
        $conn = new PDO($dsn, $user, $pass);
        $role = $_SESSION['user_role'];
        $query = "select * from roles where role = :role limit 1";
        $stmt = $conn->prepare($query);
		$stmt->bindParam(':role', $role);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
                $user_prives = $result; 
                return $user_prives;
            }
    }   

     exit();
}

function random_num($length){ //So that not all user IDs are of same length, also so that they are not incrementable.

    $text = ""; //We will insert the ID here number by number
    //Just in case something goes wrong, I will make the least length of this number == 5
    if ($length < 5) {
        $length = 5;
    }
    $len = rand(4, $length); //this is the actual length of the ID which will be between 5 and the inserted length, hence I made sure it is bigger than 5
    for ($i=0; $i < $len; $i++) { 
        $text .= rand(0,9);
    }
    return $text;
}