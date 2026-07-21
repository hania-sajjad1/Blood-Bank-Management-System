<?php
include("db/config.php");

/* DELETE */
if(isset($_GET['delete'])){

$id=$_GET['delete'];

/* Delete staff linked to blood bank */
$conn->query("DELETE FROM Staff WHERE BankID='$id'");

/* Delete blood units linked to blood bank */
$conn->query("DELETE FROM BloodUnit WHERE BankID='$id'");

/* Delete blood bank */
$conn->query("DELETE FROM BloodBank WHERE BankID='$id'");

header("Location: bloodbank.php");
exit();
}

/* UPDATE */
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];

    $sql = "UPDATE BloodBank SET
            Name='$name',
            Location='$location',
            Contact='$contact'
            WHERE BankID='$id'";

    $conn->query($sql);

    header("Location: bloodbank.php");
    exit();
}

/* INSERT */
if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $location = $_POST['location'];
    $contact = $_POST['contact'];

    $sql = "INSERT INTO BloodBank
            (Name, Location, Contact)
            VALUES
            ('$name','$location','$contact')";

    $conn->query($sql);

    header("Location: bloodbank.php");
    exit();
}

/* EDIT FETCH */
$edit = null;

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM BloodBank WHERE BankID='$id'");
    $edit = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>

<head>
<title>Blood Bank Management</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Blood Bank Management</h1>

<a href="index.php">Back to Dashboard</a>

<form method="POST" action="bloodbank.php">

<input type="hidden" name="id" value="<?= $edit['BankID'] ?? '' ?>">

<p>Blood Bank Name:</p>
<input type="text" name="name" value="<?= $edit['Name'] ?? '' ?>">

<p>Location:</p>
<input type="text" name="location" value="<?= $edit['Location'] ?? '' ?>">

<p>Contact:</p>
<input type="text" name="contact" value="<?= $edit['Contact'] ?? '' ?>">

<br><br>

<?php if($edit){ ?>
<button type="submit" name="update">Update Blood Bank</button>
<?php } else { ?>
<button type="submit" name="submit">Save Blood Bank</button>
<?php } ?>

</form>

<h2>Search Blood Bank</h2>

<form method="GET" action="bloodbank.php">

<input type="text" name="search" placeholder="Search Blood Bank Name">

<select name="location">
<option value="">All Locations</option>
<option value="Lahore">Lahore</option>
<option value="Islamabad">Islamabad</option>
<option value="Karachi">Karachi</option>
<option value="Faisalabad">Faisalabad</option>
</select>

<button class="search-btn" type="submit">Search</button>

</form>

<h2>Blood Bank Records</h2>

<?php

$search = isset($_GET['search']) ? $_GET['search'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';

$sql = "SELECT * FROM BloodBank WHERE 1=1";

if($search != ""){
$sql .= " AND Name LIKE '%$search%'";
}

if($location != ""){
$sql .= " AND Location='$location'";
}

$result = $conn->query($sql);

?>

<table border="1" width="100%">
<tr>
<th>ID</th>
<th>Name</th>
<th>Location</th>
<th>Contact</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?= $row['BankID']; ?></td>
<td><?= $row['Name']; ?></td>
<td><?= $row['Location']; ?></td>
<td><?= $row['Contact']; ?></td>

<td>
<a href="bloodbank.php?edit=<?= $row['BankID']; ?>">Edit</a> |
<a href="bloodbank.php?delete=<?= $row['BankID']; ?>"
onclick="return confirm('Delete blood bank?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>