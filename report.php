<?php
include("db/config.php");

$search = isset($_GET['search']) ? $_GET['search'] : '';
$datefilter = isset($_GET['datefilter']) ? $_GET['datefilter'] : '';

$dateCondition = "";

if($datefilter=="today"){
$dateCondition=" AND DATE(Date)=CURDATE()";
}
elseif($datefilter=="week"){
$dateCondition=" AND YEARWEEK(Date)=YEARWEEK(CURDATE())";
}
elseif($datefilter=="month"){
$dateCondition=" AND MONTH(Date)=MONTH(CURDATE())";
}

$reports = [

"Total Donors" =>
$conn->query("SELECT COUNT(*) as total FROM Donor")
->fetch_assoc()['total'],

"Total Patients" =>
$conn->query("SELECT COUNT(*) as total FROM Patient")
->fetch_assoc()['total'],

"Available Blood Units" =>
$conn->query("SELECT COUNT(*) as total
FROM BloodUnit
WHERE Status='Available'")
->fetch_assoc()['total'],

"Used Blood Units" =>
$conn->query("SELECT COUNT(*) as total
FROM BloodUnit
WHERE Status='Used'")
->fetch_assoc()['total'],

"Expired Blood Units" =>
$conn->query("SELECT COUNT(*) as total
FROM BloodUnit
WHERE Status='Expired'")
->fetch_assoc()['total'],

"Pending Requests" =>
$conn->query("SELECT COUNT(*) as total
FROM Request
WHERE Status='Pending' $dateCondition")
->fetch_assoc()['total'],

"Approved Requests" =>
$conn->query("SELECT COUNT(*) as total
FROM Request
WHERE Status='Approved' $dateCondition")
->fetch_assoc()['total'],

"Rejected Requests" =>
$conn->query("SELECT COUNT(*) as total
FROM Request
WHERE Status='Rejected' $dateCondition")
->fetch_assoc()['total'],

"Donation History" =>
$conn->query("SELECT COUNT(*) as total
FROM BloodUnit")
->fetch_assoc()['total']

];
?>

<!DOCTYPE html>
<html>

<head>
<title>Reports</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Blood Bank Reports</h1>

<a href="index.php">Back to Dashboard</a>

<h2>Search Reports</h2>

<form method="GET" action="reports.php">

<input type="text" name="search" placeholder="Search Report Type">

<select name="datefilter">
<option value="">All Dates</option>
<option value="today">Today</option>
<option value="week">This Week</option>
<option value="month">This Month</option>
</select>

<button class="search-btn" type="submit">Search</button>

</form>

<h2>Records</h2>

<table border="1" width="100%">

<tr>
<th>Report Type</th>
<th>Total</th>
</tr>

<?php
foreach($reports as $type=>$total){

if($search=="" || stripos($type,$search)!==false){
?>

<tr>
<td><?php echo $type; ?></td>
<td><?php echo $total; ?></td>
</tr>

<?php
}
}
?>

</table>

<br><br>

<h2>Inventory by Blood Group</h2>

<?php
$result=$conn->query("
SELECT BloodGroup,SUM(Quantity) as total
FROM BloodUnit
WHERE Status='Available'
GROUP BY BloodGroup
");
?>

<table border="1" width="100%">

<tr>
<th>Blood Group</th>
<th>Available Quantity</th>
</tr>

<?php while($row=$result->fetch_assoc()){ ?>

<tr>
<td><?php echo $row['BloodGroup']; ?></td>
<td><?php echo $row['total']; ?></td>
</tr>

<?php } ?>

</table>

</body>
</html>