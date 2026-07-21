<?php
include("db/config.php");

/* AUTO EXPIRE */
$conn->query("UPDATE BloodUnit 
SET Status='Expired' 
WHERE ExpiryDate < CURDATE()");

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $conn->query("DELETE FROM BloodUnit WHERE BloodUnitID='$id'");

    header("Location: bloodunit.php");
    exit();
}

/* UPDATE */
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $bloodgroup = $_POST['bloodgroup'];
    $quantity = $_POST['quantity'];
    $collectiondate = $_POST['collectiondate'];
    $expirydate = $_POST['expirydate'];
    $status = $_POST['status'];
	$donorid = $_POST['donorid'];
    $bankid = $_POST['bankid'];

$sql = "UPDATE BloodUnit SET
DonorID='$donorid',
BankID='$bankid',
BloodGroup='$bloodgroup',
Quantity='$quantity',
CollectionDate='$collectiondate',
ExpiryDate='$expirydate',
Status='$status'
WHERE BloodUnitID='$id'";

    $conn->query($sql);

    header("Location: bloodunit.php");
    exit();
}

/* INSERT */
if(isset($_POST['submit'])){

    $bloodgroup = $_POST['bloodgroup'];
    $quantity = $_POST['quantity'];
    $collectiondate = $_POST['collectiondate'];
    $expirydate = $_POST['expirydate'];
    $status = $_POST['status'];
	$donorid = $_POST['donorid'];

	$bankid = $_POST['bankid'];

$sql = "INSERT INTO BloodUnit
(DonorID, BankID, BloodGroup, Quantity, CollectionDate, ExpiryDate, Status)
VALUES
('$donorid','$bankid','$bloodgroup','$quantity','$collectiondate','$expirydate','$status')";

    $conn->query($sql);

    header("Location: bloodunit.php");
    exit();
}

/* EDIT FETCH */
$edit = null;

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM BloodUnit WHERE BloodUnitID='$id'");
    $edit = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>

<head>
<title>Blood Unit Management</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Blood Unit Management</h1>

<a href="index.php">Back to Dashboard</a>

<form method="POST" action="bloodunit.php">

<input type="hidden" name="id" value="<?= $edit['BloodUnitID'] ?? '' ?>">

<p>Donor ID:</p>
<input type="number" name="donorid"
value="<?= $edit['DonorID'] ?? '' ?>">

<p>Bank ID:</p>
<input type="number" name="bankid"
value="<?= $edit['BankID'] ?? '' ?>">

<p>Blood Group:</p>

<select name="bloodgroup">

<?php
$groups=['A+','A-','B+','B-','O+','O-','AB+','AB-'];

foreach($groups as $g){
$selected=(isset($edit['BloodGroup']) && $edit['BloodGroup']==$g)?"selected":"";
echo "<option value='$g' $selected>$g</option>";
}
?>

</select>

<p>Quantity:</p>
<input type="number" name="quantity" value="<?= $edit['Quantity'] ?? '' ?>">

<p>Collection Date:</p>
<input type="date" name="collectiondate"
value="<?= $edit['CollectionDate'] ?? '' ?>">

<p>Expiry Date:</p>
<input type="date" name="expirydate"
value="<?= $edit['ExpiryDate'] ?? '' ?>">

<p>Status:</p>

<select name="status">
<option value="Available">Available</option>
<option value="Used">Used</option>
<option value="Expired">Expired</option>
</select>

<br><br>

<?php if($edit){ ?>
<button type="submit" name="update">Update Blood Unit</button>
<?php } else { ?>
<button type="submit" name="submit">Save Blood Unit</button>
<?php } ?>

</form>

<h2>Search Records</h2>

<form method="GET" action="bloodunit.php">

<input type="text" name="search" placeholder="Search Blood Group">

<select name="status">
<option value="">All Status</option>
<option value="Available">Available</option>
<option value="Used">Used</option>
<option value="Expired">Expired</option>
</select>

<button type="submit">Search</button>

</form>

<h2>Blood Unit Records</h2>

<?php

$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT * FROM BloodUnit WHERE 1=1";

if($search != ""){
$sql .= " AND BloodGroup LIKE '%$search%'";
}

if($status != ""){
$sql .= " AND Status='$status'";
}

$result = $conn->query($sql);

?>

<table border="1" width="100%">

<tr>
<th>ID</th>
<th>Donor ID</th>
<th>Bank ID</th>
<th>Blood Group</th>
<th>Quantity</th>
<th>Collection Date</th>
<th>Expiry Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?= $row['BloodUnitID']; ?></td>
<td><?= $row['DonorID']; ?></td>
<td><?= $row['BankID']; ?></td>
<td><?= $row['BloodGroup']; ?></td>
<td><?= $row['Quantity']; ?></td>
<td><?= $row['CollectionDate']; ?></td>
<td><?= $row['ExpiryDate']; ?></td>
<td><?= $row['Status']; ?></td>

<td>
<a href="bloodunit.php?edit=<?= $row['BloodUnitID']; ?>">Edit</a> |
<a href="bloodunit.php?delete=<?= $row['BloodUnitID']; ?>"
onclick="return confirm('Delete blood unit?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>