<?php
include("db/config.php");

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $conn->query("DELETE FROM Request WHERE RequestID='$id'");

    header("Location: request.php");
    exit();
}

/* UPDATE */
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $patientid = $_POST['patientid'];
    $bloodunitid = $_POST['bloodunitid'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $sql = "UPDATE Request SET
            PatientID='$patientid',
            BloodUnitID='$bloodunitid',
            Date='$date',
            Quantity='$quantity',
            Status='$status'
            WHERE RequestID='$id'";

    $conn->query($sql);

    if($status=="Approved"){
        $conn->query("UPDATE BloodUnit
                      SET Status='Used'
                      WHERE BloodUnitID='$bloodunitid'");
    }

    header("Location: request.php");
    exit();
}

/* INSERT */
if(isset($_POST['submit'])){

    $patientid = $_POST['patientid'];
    $bloodunitid = $_POST['bloodunitid'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO Request
            (PatientID, BloodUnitID, Date, Quantity, Status)
            VALUES
            ('$patientid','$bloodunitid','$date','$quantity','$status')";

    $conn->query($sql);

    if($status=="Approved"){
        $conn->query("UPDATE BloodUnit
                      SET Status='Used'
                      WHERE BloodUnitID='$bloodunitid'");
    }

    header("Location: request.php");
    exit();
}

/* EDIT FETCH */
$edit = null;

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM Request WHERE RequestID='$id'");
    $edit = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Blood Request Management</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Blood Request Management</h1>

<a href="index.php">Back to Dashboard</a>

<form method="POST" action="request.php">

<input type="hidden" name="id" value="<?= $edit['RequestID'] ?? '' ?>">

<p>Patient:</p>
<?php $patients = $conn->query("SELECT PatientID, Name FROM Patient"); ?>
<select name="patientid" required>
<option value="">Select Patient</option>

<?php while($p=$patients->fetch_assoc()){ ?>
<option value="<?= $p['PatientID']; ?>"
<?= isset($edit['PatientID']) && $edit['PatientID']==$p['PatientID']?"selected":"" ?>>
<?= $p['Name']; ?>
</option>
<?php } ?>

</select>

<p>Blood Unit:</p>
<?php $units=$conn->query("SELECT BloodUnitID, BloodGroup FROM BloodUnit"); ?>
<select name="bloodunitid" required>

<option value="">Select Blood Unit</option>

<?php while($b=$units->fetch_assoc()){ ?>
<option value="<?= $b['BloodUnitID']; ?>"
<?= isset($edit['BloodUnitID']) && $edit['BloodUnitID']==$b['BloodUnitID']?"selected":"" ?>>
<?= $b['BloodGroup']; ?>
</option>
<?php } ?>

</select>

<p>Quantity:</p>
<input type="number" name="quantity"
value="<?= $edit['Quantity'] ?? '' ?>" required>

<p>Date:</p>
<input type="date" name="date"
value="<?= $edit['Date'] ?? '' ?>" required>

<p>Status:</p>
<select name="status">

<option value="Pending">Pending</option>
<option value="Approved">Approved</option>
<option value="Rejected">Rejected</option>

</select>

<br><br>

<?php if($edit){ ?>
<button type="submit" name="update">Update Request</button>
<?php } else { ?>
<button type="submit" name="submit">Submit Request</button>
<?php } ?>

</form>

<hr>

<h2>Search Records</h2>

<form method="GET" action="request.php">

<input type="text" name="search" placeholder="Search Request ID">

<select name="status">
<option value="">All</option>
<option value="Pending">Pending</option>
<option value="Approved">Approved</option>
<option value="Rejected">Rejected</option>
</select>

<button type="submit">Search</button>

</form>

<hr>

<h2>Request Records</h2>

<?php

$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT *
FROM Request
WHERE 1=1";

if($search!=""){
$sql .= " AND r.RequestID LIKE '%$search%'";
}

if($status!=""){
$sql .= " AND r.Status='$status'";
}

$result=$conn->query($sql);

?>

<table border="1" width="100%">

<tr>
<th>ID</th>
<th>Patient ID</th>
<th>Blood Unit ID</th>
<th>Quantity</th>
<th>Date</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=$result->fetch_assoc()){ ?>

<tr>

<td><?= $row['RequestID']; ?></td>
<td><?= $row['PatientID']; ?></td>
<td><?= $row['BloodUnitID']; ?></td>
<td><?= $row['Quantity']; ?></td>
<td><?= $row['Date']; ?></td>
<td><?= $row['Status']; ?></td>

<td>
<a href="request.php?edit=<?= $row['RequestID']; ?>">Edit</a> |
<a href="request.php?delete=<?= $row['RequestID']; ?>"
onclick="return confirm('Delete request?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>