<?php
    session_start();

    include("connection.php"); 
    include("functions.php");

    $user_data = check_login();
    $user_prives = check_privs();
    $conn = new PDO($dsn, $user, $pass);

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
    <a href="index.php">Homepage</a>
</body>
</html>