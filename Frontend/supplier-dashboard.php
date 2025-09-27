<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['supplierId'])) {
    header("Location: supplierlogin.php");
    exit();
}

// Retrieve session data
$supplierName = $_SESSION['name'];
$supplierEmail = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><h2>TechFix Supplier</h2></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 550px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
        
    /* On small screens, set height to 'auto' for the grid */
    @media screen and (max-width: 767px) {
      .row.content {height: auto;} 
    }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav hidden-xs">
      <h3>TechFix Supplier</h3>
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="supplier-dashboard.php">Dashboard</a></li>
        <li><a href="quotation.php">Quatation</a></li>
        <li><a href="SupInventory.php">Inventory</a></li>
      </ul><br>
    </div>
    <br>
    <div class="col-sm-9">
      <div class="well">
        <h4>Dashboard</h4>
        <h1>Welcome to TechFix Supplier Dashboard</h1>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($supplierName); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($supplierEmail); ?></p>
      <form method="POST" action="logout.php">
          <button type="submit">Logout</button>
      </form>
      </div>
     
    </div>
  </div>
</div>
</body>
</html>
