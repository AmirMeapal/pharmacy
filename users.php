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
	<?php if ($user_prives['add_user']) {
		echo '<a href="add_user.php">Add a user</a></br></br>';
	} 
    if ($user_prives['update_user']) {
		echo '<a href="update_user.php">Update a user</a></br></br>';
	}
    if ($user_prives['delete_user']) {
		echo '<a href="delete_user.php">Delete a user</a></br>';
	}
    ?>
    <h1>These are the users</h1>
    <table>
		<thead>
			<tr>
				<th>DB ID</th>
				<th>User ID</th>
                <th>Name</th>
				<th>Role</th>
				<th>Password</th>
			</tr>
		</thead>
		<tbody>
			<?php
                 $query = "select * from users";
                 $stmt = $conn->prepare($query);
                 $stmt->execute();
                $result  = $stmt->fetchAll();
                foreach ($result as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['user_id'] . "</td>";
                    echo "<td>" . $row['user_name'] . "</td>";
                    echo "<td>" . $row['user_role'] . "</td>";
                    echo "<td>" . $row['password'] . "</td>";
                    echo "</tr>";
                }
            ?>
		</tbody>
	</table>
    <a href="index.php">Homepage</a>
</body>
</html>