<!--
Name: Aly Abdelrahman
 Course: CNT 4714 – Summer 2016
 Assignment title: A Three-Tier Distributed Web-Based Application Using PHP and Apache
 Due Date: August 2, 2016
  -->
  
<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING);
session_start();

// Redirect user to login page if not logged in
if(!isset($_SESSION["loggedInUser"])) {
	header("location: index.php");
	die();
}

// Logout user if user choose to
if(isset($_POST["logout"])) {
	session_unset();
	header("location: index.php");
	die();
}

$databaseResult = "";
$sql = "";

// Connect to database and perform query
if(isset($_POST["submitQuery"])) {
	mysql_connect("localhost", $_SESSION["loggedInUser"], $_SESSION["loggedInPassword"]) or die(mysql_error());
	mysql_select_db("project4") or die(mysql_error());
	
	$sql = $_POST["sqlQuery"];
	$databaseResult .= "<table border='1'>";
	
	try {		
		if(empty($sql) || startsWith(strtoupper($sql), "SELECT ")) {
			if(empty($sql)) {
				$sql = "SELECT * FROM suppliers";
			}
			
			// Perform a select statement
			$result = mysql_query($sql);
			
			if(!$result) {
				throw new Exception(mysql_error());
			}
			
			$databaseResult .= "<tr>";
			
			for($i = 0; $i < mysql_num_fields($result); $i++) {
				$databaseResult .= "<th style='color:white; background-color: #333333'>".mysql_field_name($result, $i)."</th>";
			}
			
			$databaseResult .= "</tr>";
			
			for($i = 0; $i < mysql_num_rows($result); $i++) {
				$databaseResult .= "<tr>";
				
				for($j = 0; $j < mysql_num_fields($result); $j++) {
					$databaseResult .= "<td style='background-color: yellow; color: black'>".mysql_result($result, $i, $j)."</td>";
				}
				
				$databaseResult .= "</tr>";
			}
		} else {
			// Perform an INSERT or UPDATE statement
			if(!mysql_query($sql)) {
				throw new Exception(mysql_error());
			}
			
			$affectedRowsCount = mysql_affected_rows();
			
			$databaseResult .= "<tr>";
			$databaseResult .= "<th>";
			$databaseResult .= "<p>The statement executed successfully.<br />".$affectedRowsCount." row(s) affected.</p>";
			
			$sql = strtolower($sql);
			
			if(contains($sql, "shipment") && (startsWith($sql, "insert ") || startsWith($sql, "update "))) {
				$databaseResult .= "<p style='color:#FFFFFF'>";
				$databaseResult .= "Business Logic Detected! - Updating Supplier Status<br />";

				if(!mysql_query("UPDATE suppliers, shipments 
						SET suppliers.status = suppliers.status + 5 
						WHERE suppliers.snum = shipments.snum 
						AND shipments.quantity >= 100")) {
							$databaseResult .= "</p>";
							throw new Exception(mysql_error());
						}

				$affectedRowsCount = mysql_affected_rows();

				$databaseResult .= "Business Logic updated " + $affectedRowsCount + " supplier status marks.";
				$databaseResult .= "</p>";
			}
			
			$databaseResult .= "</th>";
            $databaseResult .= "</tr>";
		}
	} catch(Exception $error) {
		$databaseResult .= "<tr>";
		$databaseResult .= "<th style='background-color: #00FF00; color: #FFFFFF'>";
		$databaseResult .= "<b>Error executing the SQL statement:</b><br />";
		$databaseResult .= $error->getMessage();
		$databaseResult .= "</th>";
		$databaseResult .= "</tr>";
	}
	
	$databaseResult .= "</table>";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>CNT 4714 Remote Database Management System</title>
	</head>
	<body style="width: 900px; margin-left: auto; margin-right: auto; background-color: black; color: yellow">
		<h1 style="color:red; text-align: center;">CNT 4717 - Project four Database Client</h1>
		<hr />
		<table style="width: 100%; color: yellow">
			<tr>
				<td style="width: 30%; vertical-align:top">
					<form method="post" action="query.php">
						<b>Welcome back</b> <?php echo $_SESSION["loggedInUser"]; ?><br /><br />
						<input type="submit" value="Logout" style="background-color: red; color: white; border: solid 1px gray;" name="logout" />
					</form>
				</td>
				<td style="vertical-align: top">
					<form method="post" action="query.php">
						<b>Enter Query</b>
						<p>Please enter a valid SQL query or update statement. You may also just "Submit Query" to run a default query against the database.</p>
						<textarea style="width: 100%; height: 100px;" name="sqlQuery"><?php echo $sql; ?></textarea>
						<input type="submit" style="background-color: red; color: white; border: solid 1px gray;" value="Submit Query" name="submitQuery"  />
						<input type="submit" style="background-color: red; color: white; border: solid 1px gray;" value="Reset Window" />
					</form>
					<?php
					if(!empty($databaseResult)) {
						echo "<br />";
						echo $databaseResult;
					}
					?>
				</td>
			</tr>
		</table>
		<hr />
		<p style="text-align: center; color: blue"><b>CNT 4714 PHP based Database Client</b></p>
	</body>
</html>
<?php
// Check if string starts with another string
function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

// Check if string contains another string
function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}
?>