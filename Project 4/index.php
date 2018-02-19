<!--
Name: Aly Abdelrahman
 Course: CNT 4714 – Summer 2016
 Assignment title: A Three-Tier Distributed Web-Based Application Using PHP and Apache
 Due Date: August 2, 2016
  -->
  
<?php
error_reporting(E_ALL ^ E_DEPRECATED ^ E_WARNING);
session_start();

// Redirect user to query page if logged in
if(isset($_SESSION["loggedInUser"])) {
	header("location: query.php");
	die();
}

if(isset($_POST["login"])) {
	// Validate login 
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	if(!empty($username) && mysql_connect("localhost", $username, $password)) {
		$_SESSION["loggedInUser"] = $username;
		$_SESSION["loggedInPassword"] = $password;
		
		// If login successful, redirect user to query page
		header("location: query.php");
		die();
	}
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
					<?php
					if(isset($_POST["login"])) {
						echo "<p style='color: red'>Invalid login. Please try again.</p>";
					}
					?>
					<form method="post" action="index.php">
						<b>Username</b><br />
						<input type="text" name="username" /><br />
						<b>Password</b><br />
						<input type="password" name="password"/><br />
						<br />
						<input type="submit" value="Login" style="background-color: red; color: white; border: solid 1px gray;" name="login" />
					</form>
				</td>
				<td style="vertical-align: top">
					<b>Welcome to the Database Client</b>
					<p>This system will allow any register users to run SQL queries and</p>
					<p>Update statements on teh database shown below, once login</p>
					<p>the system is sucessful </p>
					<b>Database connection:</b>
					<p> Connection is to the MYSQL database named:localhost:3306/project4<p>
					<b>User Login</b>
					<p>Username Root</p>
					<p>Password</p>
				</td>
			</tr>
		</table>
		<hr />
		<p style="text-align: center; color: blue"><b>CNT 4714 PHP based Database Client</b></p>
	</body>
</html>