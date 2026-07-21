<?php
include("db/config.php");

/* DELETE */
if(isset($_GET['delete'])){
$id=$_GET['delete'];

$conn->query("DELETE FROM Staff WHERE StaffID='$id'");

header("Location: staff.php");
exit();
}

/* UPDATE */
if(isset($_POST['update'])){

$id=$_POST['id'];
$name=$_POST['name'];
$role=$_POST['role'];
$gender=$_POST['gender'];
$phone=$_POST['phone'];
$address=$_POST['address'];
$bankid=$_POST['bankid'];

$sql="UPDATE Staff SET
Name='$name',
Role='$role',
Gender='$gender',
Phone='$phone',
Address='$address',
BankID='$bankid'
WHERE StaffID='$id'";

$conn->query($sql);

header("Location: staff.php");
exit();
}

/* INSERT */
if(isset($_POST['submit'])){

$name=$_POST['name'];
$role=$_POST['role'];
$gender=$_POST['gender'];
$phone=$_POST['phone'];
$address=$_POST['address'];
$bankid=$_POST['bankid'];

$sql="INSERT INTO Staff
(Name,Role,Gender,Phone,Address,BankID)
VALUES
('$name','$role','$gender','$phone','$address','$bankid')";

$conn->query($sql);

header("Location: staff.php");
exit();
}

/* EDIT */
$edit=null;

if(isset($_GET['edit'])){
$id=$_GET['edit'];

$result=$conn->query("SELECT * FROM Staff WHERE StaffID='$id'");
$edit=$result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>

<head>
<title>Staff Management</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Staff Management</h1>

<a href="index.php">Back to Dashboard</a>

<form method="POST" action="staff.php">

<input type="hidden" name="id" value="<?= $edit['StaffID'] ?? '' ?>">

<p>Name:</p>
<input type="text" name="name" value="<?= $edit['Name'] ?? '' ?>">

<p>Role:</p>

<select name="role">
<option value="Doctor">Doctor</option>
<option value="Nurse">Nurse</option>
<option value="Lab Technician">Lab Technician</option>
<option value="Admin">Admin</option>
</select>

<p>Gender:</p>

<select name="gender">
<option value="Male">Male</option>
<option value="Female">Female</option>
</select>

<p>Phone:</p>
<input type="text" name="phone" value="<?= $edit['Phone'] ?? '' ?>">

<p>Address:</p>
<input type="text" name="address" value="<?= $edit['Address'] ?? '' ?>">

<p>Bank ID:</p>
<input type="number" name="bankid" value="<?= $edit['BankID'] ?? '' ?>">

<br><br>

<?php if($edit){ ?>
<button type="submit" name="update">Update Staff</button>
<?php } else { ?>
<button type="submit" name="submit">Save Staff</button>
<?php } ?>

</form>

<h2>Search Records</h2>

<form method="GET" action="staff.php">

<input type="text" name="search" placeholder="Search Staff Name">

<select name="role">
<option value="">All Roles</option>
<option value="Doctor">Doctor</option>
<option value="Nurse">Nurse</option>
<option value="Lab Technician">Lab Technician</option>
<option value="Admin">Admin</option>
</select>

<button type="submit">Search</button>

</form>

<?php

$search=isset($_GET['search']) ? $_GET['search'] : '';
$role=isset($_GET['role']) ? $_GET['role'] : '';

$sql="SELECT * FROM Staff WHERE 1=1";

if($search!=""){
$sql.=" AND Name LIKE '%$search%'";
}

if($role!=""){
$sql.=" AND Role='$role'";
}

$result=$conn->query($sql);

?>

<h2>Staff Records</h2>

<table border="1" width="100%">

<tr>
<th>ID</th>
<th>Name</th>
<th>Role</th>
<th>Gender</th>
<th>Phone</th>
<th>Address</th>
<th>Bank ID</th>
<th>Action</th>
</tr>

<?php while($row=$result->fetch_assoc()){ ?>

<tr>

<td><?= $row['StaffID']; ?></td>
<td><?= $row['Name']; ?></td>
<td><?= $row['Role']; ?></td>
<td><?= $row['Gender']; ?></td>
<td><?= $row['Phone']; ?></td>
<td><?= $row['Address']; ?></td>
<td><?= $row['BankID']; ?></td>

<td>
<a href="staff.php?edit=<?= $row['StaffID']; ?>">Edit</a> |
<a href="staff.php?delete=<?= $row['StaffID']; ?>"
onclick="return confirm('Delete staff?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>