<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Blood Bank System</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<h1>Blood Bank Managemnet System</h1>
	<h2>Dashboard</h2>

	<div class="dashboard">

		<a href="donor.php">🧑 Donor Management</a>
		<a href="patient.php">🏥 Patient Management</a>
		<a href="bloodbank.php">🏦 Blood Bank</a>
		<a href="bloodunit.php">🩸 Blood Units</a>
		<a href="request.php">📄 Blood Requests</a>
		<a href="staff.php">👨‍💼 Staff Management</a>
		<a href="report.php">📊 Reports</a>

	</div>
	<div class="logout">
		<a href="logout.php">Logout</a>
	</div>
</body>
</html>