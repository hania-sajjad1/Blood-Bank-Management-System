<?php
include("db/config.php");

/* DELETE */
if(isset($_GET['delete'])){

    $id=$_GET['delete'];

    $conn->query("DELETE FROM Request WHERE PatientID='$id'");

    $conn->query("DELETE FROM Patient WHERE PatientID='$id'");

    header("Location: patient.php");
    exit();
}

/* UPDATE */
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $bloodgroup = $_POST['bloodgroup'];
    $disease = $_POST['disease'];
    $phone = $_POST['phone'];

    $sql = "UPDATE Patient SET
            Name='$name',
            Gender='$gender',
            Age='$age',
            BloodGroup='$bloodgroup',
            Disease='$disease',
            Phone='$phone'
            WHERE PatientID='$id'";

    $conn->query($sql);

    header("Location: patient.php");
    exit();
}

/* INSERT */
if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $bloodgroup = $_POST['bloodgroup'];
    $disease = $_POST['disease'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO Patient
            (Name, Gender, Age, BloodGroup, Disease, Phone)
            VALUES
            ('$name','$gender','$age','$bloodgroup','$disease','$phone')";

    $conn->query($sql);

    header("Location: patient.php");
    exit();
}

/* EDIT FETCH */
$edit = null;

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM Patient WHERE PatientID='$id'");
    $edit = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Management</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Patient Registration</h1>

<a href="index.php">Back to Dashboard</a>

<form method="POST" action="patient.php">

<input type="hidden" name="id" value="<?= $edit['PatientID'] ?? '' ?>">

<p>Name:</p>
<input type="text" name="name" value="<?= $edit['Name'] ?? '' ?>">

<p>Gender:</p>
<select name="gender">
<option value="Male" <?= isset($edit['Gender']) && $edit['Gender']=="Male"?"selected":"" ?>>Male</option>
<option value="Female" <?= isset($edit['Gender']) && $edit['Gender']=="Female"?"selected":"" ?>>Female</option>
</select>

<p>Age:</p>
<input type="number" name="age" value="<?= $edit['Age'] ?? '' ?>">

<p>Blood Group</p>
<select name="bloodgroup">

<?php
$groups=['A+','A-','B+','B-','O+','O-','AB+','AB-'];

foreach($groups as $g){
$selected=(isset($edit['BloodGroup']) && $edit['BloodGroup']==$g)?"selected":"";
echo "<option value='$g' $selected>$g</option>";
}
?>

</select>

<p>Disease:</p>
<input type="text" name="disease" value="<?= $edit['Disease'] ?? '' ?>">

<p>Phone:</p>
<input type="text" name="phone" value="<?= $edit['Phone'] ?? '' ?>">

<br><br>

<?php if($edit){ ?>
<button type="submit" name="update">Update Patient</button>
<?php } else { ?>
<button type="submit" name="submit">Save Patient</button>
<?php } ?>

</form>

<h2>Search Records</h2>

<form method="GET" action="patient.php">

<input type="text" name="search" placeholder="Search Patient Name">

<select name="gender">
<option value="">All Patients</option>
<option value="Male">Male</option>
<option value="Female">Female</option>
</select>

<button type="submit">Search</button>

</form>

<h2>Patient Records</h2>

<?php

$search = isset($_GET['search']) ? $_GET['search'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';

$sql = "SELECT * FROM Patient WHERE 1=1";

if($search != ""){
$sql .= " AND Name LIKE '%$search%'";
}

if($gender != ""){
$sql .= " AND Gender='$gender'";
}

$result = $conn->query($sql);

?>

<table border="1" width="100%">
<tr>
<th>ID</th>
<th>Name</th>
<th>Gender</th>
<th>Age</th>
<th>Disease</th>
<th>Blood Group</th>
<th>Phone</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?= $row['PatientID']; ?></td>
<td><?= $row['Name']; ?></td>
<td><?= $row['Gender']; ?></td>
<td><?= $row['Age']; ?></td>
<td><?= $row['Disease']; ?></td>
<td><?= $row['BloodGroup']; ?></td>
<td><?= $row['Phone']; ?></td>

<td>
<a href="patient.php?edit=<?= $row['PatientID']; ?>">Edit</a> |
<a href="patient.php?delete=<?= $row['PatientID']; ?>"
onclick="return confirm('Delete patient?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>