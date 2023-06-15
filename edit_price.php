<?php
    session_start();

    include("connection.php"); 
    include("functions.php");

    $user_data = check_login();
    $user_prives = check_privs();
    if (!$user_prives['edit_price']) {
        header("Location: index.php");
        exit();
    }
    $conn = new PDO($dsn, $user, $pass);
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
            $id = filter_input(INPUT_POST,'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $new_price = filter_input(INPUT_POST,'new_price',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            if ( !empty($id) && !empty($new_price) && is_numeric($id) && is_numeric($new_price)) {
                
               try {
                $query = "update meds set price=:price where id = :id ";
                $stmt = $conn->prepare($query);

                $stmt->bindParam(':price', $new_price);
			    $stmt->bindParam(':id', $id);

                $stmt->execute();
                header("Location: edit_price.php");
                die('updated successful!');
               } catch (Throwable $th) {
                echo "something went wrong, please retry";
                throw $th;
               }
        }else{
            echo "please fill out everything correctly";
        }} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
		table {
			border-collapse: collapse;
			width: 100%;
			margin-bottom: 20px;
		}

		th, td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}

		th {
			background-color: #f2f2f2;
		}

		tr:nth-child(even) {
			background-color: #f9f9f9;
		}

		tr:hover {
			background-color: #f5f5f5;
		}
	</style>
</head>
<body>
    <a href="logout.php">logout</a></br></br>
    <a href="delete.php">Delete My Account</a></br></br>
	<?php if ($user_prives['edit_price']) {
		echo '<a href="edit_price.php">Edit prices</a></br></br>';
	} 
    if ($user_prives['edit_quantity']) {
		echo '<a href="edit_quantity.php">Edit quantity</a>';
	}
    ?>
    <h1>This is the Products</h1>
    <table>
		<thead>
			<tr>
				<th>DB ID</th>
				<th>Name</th>
                <th>Price</th>
				<th>Quantity</th>
			</tr>
		</thead>
		<tbody>
			<?php
                 $query = "select * from meds";
                 $stmt = $conn->prepare($query);
                 $stmt->execute();
                $result  = $stmt->fetchAll();
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "</tr>";
                }
            ?>
		</tbody>
	</table>
    <form method="post" action="#">
    
    <label for="id">Product ID:</label>
    <input type="number" id="id" name="id"></br>
    
    </br><label for="new_price">New price:</label>
    <input type="number" id="new_price" name="new_price"></br></br>
    
    <input type="submit" value="Update"></br></br>
    </form>
    <a href="index.php">Homepage</a>
</body>
</html>