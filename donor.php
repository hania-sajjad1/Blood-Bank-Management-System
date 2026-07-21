<?php
include("db/config.php");

/* DELETE */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $sql = "DELETE FROM Donor WHERE DonorID='$id'";

    if($conn->query($sql)){
        echo "<script>alert('Deleted'); window.location='donor.php';</script>";
    } else {
        die($conn->error);
    }

    exit();
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = $_POST['id'];

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $bloodgroup = $_POST['bloodgroup'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $lastdonationdate = $_POST['lastdonationdate'];

    $sql = "UPDATE Donor SET
            Name='$name',
            Gender='$gender',
            Age='$age',
            BloodGroup='$bloodgroup',
            Phone='$phone',
            Address='$address',
            LastDonationDate='$lastdonationdate'
            WHERE DonorID='$id'";

    $conn->query($sql);

    header("Location: donor.php");
    exit();
}

/* INSERT */
if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $bloodgroup = $_POST['bloodgroup'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $lastdonationdate = $_POST['lastdonationdate'];

    $sql = "INSERT INTO Donor
            (Name, Gender, Age, BloodGroup, Phone, Address, LastDonationDate)
            VALUES
            ('$name','$gender','$age','$bloodgroup','$phone','$address','$lastdonationdate')";

    $conn->query($sql);

    header("Location: donor.php");
    exit();
}

/* EDIT FETCH */
$edit = null;

if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM Donor WHERE DonorID='$id'");
    $edit = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Donor Management</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Donor Registration</h1>

<a href="index.php">← Back to Dashboard</a>

<form method="POST" action="donor.php">

<input type="hidden" name="id" value="<?= $edit['DonorID'] ?? '' ?>">

<p>Name:</p>
<input type="text" name="name" value="<?= $edit['Name'] ?? '' ?>">

<p>Gender:</p>
<select name="gender">
<option value="Male" <?= isset($edit['Gender']) && $edit['Gender']=="Male"?"selected":"" ?>>Male</option>
<option value="Female" <?= isset($edit['Gender']) && $edit['Gender']=="Female"?"selected":"" ?>>Female</option>
</select>

<p>Age:</p>
<input type="number" name="age" value="<?= $edit['Age'] ?? '' ?>">

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

<p>Phone:</p>
<input type="text" name="phone" value="<?= $edit['Phone'] ?? '' ?>">

<p>Address:</p>
<input type="text" name="address" value="<?= $edit['Address'] ?? '' ?>">

<p>Last Donation Date:</p>
<input type="date" name="lastdonationdate"
value="<?= $edit['LastDonationDate'] ?? '' ?>">

<br><br>

<?php if($edit){ ?>
<button type="submit" name="update">Update Donor</button>
<?php } else { ?>
<button type="submit" name="submit">Save Donor</button>
<?php } ?>

</form>

<h2>Search Donor</h2>

<form method="GET" action="donor.php">

<input type="text" name="search" placeholder="Search Donor Name">

<select name="bloodgroup">
<option value="">All Blood Groups</option>
<option value="A+">A+</option>
<option value="A-">A-</option>
<option value="B+">B+</option>
<option value="B-">B-</option>
<option value="O+">O+</option>
<option value="O-">O-</option>
<option value="AB+">AB+</option>
<option value="AB-">AB-</option>
</select>

<button type="submit">Search</button>

</form>

<h2>Donor List</h2>

<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
$bloodgroup = isset($_GET['bloodgroup']) ? $_GET['bloodgroup'] : '';

$sql = "SELECT * FROM Donor WHERE 1=1";

if($search != ""){
$sql .= " AND Name LIKE '%$search%'";
}

if($bloodgroup != ""){
$sql .= " AND BloodGroup='$bloodgroup'";
}

$result = $conn->query($sql);
?>

<table border="1" width="100%">
<tr>
<th>ID</th>
<th>Name</th>
<th>Gender</th>
<th>Age</th>
<th>Blood Group</th>
<th>Phone</th>
<th>Address</th>
<th>Donation Date</th>
<th>Eligibility</th>
<th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ 

$eligible = (
$row['LastDonationDate'] &&
strtotime($row['LastDonationDate']) <= strtotime("-90 days")
) ? "Eligible" : "Not Eligible";

?>

<tr>
<td><?= $row['DonorID']; ?></td>
<td><?= $row['Name']; ?></td>
<td><?= $row['Gender']; ?></td>
<td><?= $row['Age']; ?></td>
<td><?= $row['BloodGroup']; ?></td>
<td><?= $row['Phone']; ?></td>
<td><?= $row['Address']; ?></td>
<td><?= $row['LastDonationDate']; ?></td>
<td><?= $eligible; ?></td>

<td>
<a href="donor.php?edit=<?= $row['DonorID']; ?>">Edit</a> |
<a href="donor.php?delete=<?= $row['DonorID']; ?>"
onclick="return confirm('Delete donor?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>