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
		<title>Rate doujin</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="main.css" rel="stylesheet" type="text/css">
		<style>
			img {
				width: 150px;
				height: 250px;
				float: left;
				margin-right: 50px;
			}
			.myTable {
				display: table;
			}
			.myRow {
			    	display: table-row;
			}
			.myCellLabel {
			    	display: table-cell;
			    	width: 120px;
			}
			.myCellValue {
				padding-left: 10px;
			    	display: table-cell;
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
			<form action="addDoujin.php" method="post">
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
								echo '<h2>Give the doujin a score!</h2>';
								echo '<p>';
									echo '<img src="' . $row["image_directory"] . '">';
									echo '<span class="myTable">';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Title:</span>';
											echo '<span class="myCellValue">' . $row["title"] . '</span>';
										echo '</span><br><br>';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Artist:</span>';
											echo '<span class="myCellValue">' . $row["artist"] . '</span>';
										echo '</span><br><br>';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Tags:</span>';
											echo '<span class="myCellValue">';
											$myTags = explode(',', $row["tag"]);	// Split string via comma
											foreach($myTags as &$myTag) {
												echo '<span class="tag-container">' . $myTag . '</span>';
											} 
											echo '</span>';
										echo '</span><br><br>';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Link:</span>';
											echo '<span class="myCellValue">' . $row["link"] . '</span>';
										echo '</span><br><br>';
										echo '<span class="myRow">';
											echo '<span class="myCellLabel">Score out of 10:</span>';
											echo '<span class="myCellValue">';
											echo '<select name="score">';
											echo '<option value=10>(10) Gave me an orgasm</option>';
											echo '<option value=9>(9) Got me excited</option>';
											echo '<option value=8>(8) Very attractive</option>';
											echo '<option value=7>(7) Will read again</option>';
											echo '<option value=6>(6) Good</option>';
											echo '<option selected value=5>(5) Average</option>';
											echo '<option value=4>(4) Just not my type</option>';
											echo '<option value=3>(3) Has some flaws</option>';
											echo '<option value=2>(2) Very bad</option>';
									  	        echo '<option value=1>(1) Waste of time</option>';
											echo '</select>';
											echo '</span>';
										echo '</span><br><br>';
										echo '<input type="hidden" name="doujinNumber" value="' . $_POST['doujinNumber'] . '">';
										echo '<input type="submit" value="Submit your doujin!" class="myButton">';
									echo '</span>';
								echo '</p>';
							}
						}
					?>
				</div>
			</form>
		</div>
	</body>
</html>
