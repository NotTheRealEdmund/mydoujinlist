<?php
	// We need to use sessions, so you should always start sessions using the below code
	session_start();
	
	// If the user is not logged in redirect to the login page
	if (!isset($_SESSION['loggedin'])) {
		header('Location: index.html');
		exit;
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Doujin List</title>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
  		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link href="main.css" rel="stylesheet" type="text/css">
		<style> 
			table {
				border-spacing: 1;
				border-collapse: collapse;
				background: white;
				border-radius: 10px;
				overflow: hidden;
				width: 100%;
				margin: 0 auto;
				position: relative;
				box-shadow: 0 0 0.3125em 0 rgba(0, 0, 0, 0.1);
			}
			table thead tr {
				height: 60px;
				background: #36304a;
			}
			table tbody tr {
				height: 50px;
			}
			table td, table th {
			        text-align: left;
			}
			thead th{
				font-family: sans-serif;
				font-size: 22px;
				color: #fff;
				line-height: 1.2;
				font-weight: unset;
			}
			tbody tr {
				font-family: sans-serif;
				font-size: 18px;
				line-height: 1.2;
				border-bottom: 0.0625em solid #e0e0e3;
				padding: 100px;
			}
			.column1 {	/* Title */
				padding-left: 30px;
				width: 200px;
			}
			.column2 {	/* Artist */
				padding: 10px;
				width: 100px;
			}
			.column3 {	/* Tags */
				padding: 10px;
				width: 500px;
			}
			.column4 {	/* Link */
				padding: 10px;
				width: 300px;
			}
			.column5 {	/* Score */
				text-align: center;
				padding-right: 30px;
				width: 100px;
			}
			.tag-container {
				display: inline-block;
				border: 2px solid black;
  				border-radius: 5px;
  				background: #d9d9d9;
  				margin: .5px;
  				padding: .5px;
			}
		</style>
	</head>
	<body class="loggedin">
		<?php require 'navbar.html'; ?>
		
		<div class="content">
			<h2>Doujin List</h2>
			
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
				
				// Read from the selections table in database
				// Prepare our SQL, preparing the SQL statement will prevent SQL injection
				if ($stmt = $conn->prepare('SELECT * FROM selections WHERE user = ?')) {
					// Bind parameters (s = string, i = int, b = blob, etc)
					$stmt->bind_param('s', $_SESSION['name']);
					$stmt->execute();
					$result = $stmt->get_result();
				
					// Check if any doujins have been added
					if ($result->num_rows <= 0) {
						echo '<div>No doujins added yet!</div>';
					} else {
						// Display top row
						echo '<table>';
						echo '<thead>';
						echo '<tr>';
						echo '<th class="column1">Doujin Title</th>';
						echo '<th class="column2">Artist</th>';
						echo '<th class="column3">Tags</th>';
						echo '<th class="column4">Link</th>';
						echo '<th class="column5">Score</th>';
						echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
					
						// Store result into a 2D array with key-value pairs of doujinNumber-score
						$score_array = array();
						while($row = $result->fetch_assoc()) {
							array_push($score_array, array($row["doujinNumber"], $row["score"]));
						}
						
						// For each doujin, find the entry in the doujins table in database
						foreach($score_array as &$pair) {  // $pair[0] is the doujinNumber and $pair[1] is the score
							// Prepare our SQL, preparing the SQL statement will prevent SQL injection
							if ($stmt = $conn->prepare('SELECT * FROM doujins where id = ?')) {
								// Bind parameters (s = string, i = int, b = blob, etc)
								$stmt->bind_param('i', $pair[0]);
								$stmt->execute();
								$result = $stmt->get_result();
								
								// Display the doujin's title, artist, tags, link, and score in a row
								while($row = $result->fetch_assoc()) {
									echo '<tr>';
									echo '<td class="column1">' . $row["title"] . '</td>';
									echo '<td class="column2">' . $row["artist"] . '</td>';
									echo '<td class="column3">';
									$myTags = explode(',', $row["tag"]);	// Split string via comma
									foreach($myTags as &$myTag) {
										echo '<span class="tag-container">' . $myTag . '</span>';
									}
									echo '</td>';
									echo '<td class="column4">' . $row["link"] . '</td>';
									echo '<td class="column5">' . $pair[1] . '</td>';
									echo '</tr>';
								}
							}
						}
						echo '</tbody>';
						echo '</table>';
					}
				}
			?>
			
		</div>
	</body>
</html>
