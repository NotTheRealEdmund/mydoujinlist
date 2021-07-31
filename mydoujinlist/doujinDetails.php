<?php
	// We need to use sessions, so you should always start sessions using the below code
	session_start();
	
	// If the user is not logged in, redirect to the login page
	if (!isset($_SESSION['loggedin'])) {
		header('Location: index.html');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Doujin details</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="main.css" rel="stylesheet" type="text/css">
		<style>
			img {
				width: 300px;
				float: left;
				margin-right: 50px;
			}
			p {
				height: 500px;
			}
			.myTable {
				display: table;
			}
			.myRow {
			    	display: table-row;
			}
			.myCellLabel {
			    	display: table-cell;
			    	width: 80px;
			}
			.myCellValue {
			    	display: table-cell;
			    	max-width: 1000px;
			}
			.tag-container {
				display: inline-block;
				border: 2px solid black;
  				border-radius: 5px;
  				background: #d9d9d9;
  				margin: .5px;
  				padding: .5px;
			}
			.myButton {
				cursor: pointer;
				background: #e9658d;
				border: 0;
				color: white;
			  	padding: 10px;
			  	border-radius: 5px;
			}
		</style>
	</head>
	<body class="loggedin">
		<?php require 'navbar.html'; ?>
		
		<div class="content">
			<form action="scoreDoujin.php" method="post">
				<div class="content">
					<?php
						// Database information	
						$DATABASE_HOST = 'localhost';
						$DATABASE_USER = 'root';
						$DATABASE_PASS = '';
						$DATABASE_NAME = 'mydoujinlist';
						
						// Connect to database
						$conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
						// Exception handling
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}
						
						// Check if the data from the home page was submitted, isset() will check if the data exists
						if (!isset($_POST['doujinNumber'])) {
							exit('Could not get the data of doujin!');
						}
						
						// Read from the doujins table in database to create the form
						// Prepare our SQL, preparing the SQL statement will prevent SQL injection
						if ($stmt = $conn->prepare('SELECT * FROM doujins WHERE id = ?')) {
							// Bind parameters (s = string, i = int, b = blob, etc)
							$stmt->bind_param('i', $_POST['doujinNumber']);
							$stmt->execute();
							$result = $stmt->get_result();
							
							while($row = $result->fetch_assoc()) {
								echo '<h2>' . $row["title"] . '</h2>';
								echo '<p>';
									echo '<img src="' . $row["image_directory"] . '">';
									echo '<span class="myTable">';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Artist:</span>';
											echo '<span class="myCellValue">' . $row["artist"] . '</span>';
										echo '</span>';
										echo '<br>';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Tags:</span>';
											echo '<span class="myCellValue">';
											$myTags = explode(',', $row["tag"]);	// Split string via comma
											foreach($myTags as &$myTag) {
												echo '<span class="tag-container">' . $myTag . '</span>';
											} 
											echo '</span>';
										echo '</span>';
										echo '<br>';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Link:</span>';
											echo '<span class="myCellValue">' . $row["link"] . '</span>';
										echo '</span>';
										echo '<br>';
										echo '<input type="hidden" name="doujinNumber" value="' . $_POST['doujinNumber'] . '">';
										echo '<input type="submit" value="Add to doujin list!" class="myButton">';
									echo '</span>';
								echo '</p>';
								
								// Read from the selections table in database where this particular doujin has been selected 
								// Prepare our SQL, preparing the SQL statement will prevent SQL injection
								if ($stmt = $conn->prepare('SELECT * FROM selections WHERE doujinNumber = ? AND review IS NOT NULL')) {
									// Bind parameters (s = string, i = int, b = blob, etc)
									$stmt->bind_param('i', $_POST['doujinNumber']);
									$stmt->execute();
									$result = $stmt->get_result();
									
									// If reviews are found
									if ($result->num_rows > 0) {
										// Post reviews
										echo '<h2>Reviews</h2>';
										while($row = $result->fetch_assoc()) {
											echo '<p>';
											echo '<span class="myTable">';
											echo '<span class="myRow">';
											echo '<span class="myCellLabel">' . $row['user'] . ':</span>';
											echo '<span class="myCellValue">' . $row['review'] . '</span>';
											echo '</span>';
											echo '</span>';
											echo '</p>';
										}
									} else {
										echo '<h2 style="text-align: center;">No reviews yet!</h2>';
									}
								}
							}
						}
					?>
				</div>
			</form>
		</div>
	</body>
</html>
