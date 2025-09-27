<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['staffId'])) {
    header("Location: login.php");
    exit();
}

// Retrieve session data
$staffName = $_SESSION['name'];
$staffEmail = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>TechFix - Supplier Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    .row.content {height: 550px;}
    .sidenav {background-color: #f1f1f1; height: 100%;}
    @media screen and (max-width: 767px) {.row.content {height: auto;}}
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav hidden-xs">
      <h2>TechFix</h2>
      <ul class="nav nav-pills nav-stacked">
        <li ><a href="dashboard.php">Dashboard</a></li>
        <li ><a href="Staff.php">Staff</a></li>
        <li class="active"><a href="supplier.php">Supplier</a></li>
        <li><a href="StaffInventory.php">Inventory</a></li>
        <li><a href="StaffQuotation.php">Quotation</a></li>
      </ul><br>
    </div>
    <br>
    
    <div class="col-sm-9">
      <div class="well">
      <h4>Supplier Management</h4>

             <!-- Add Supplier Button -->
             <button class="btn btn-primary" data-toggle="modal" data-target="#addSupplierModal">Add Supplier</button>
                <br/>
                <br/>
            <!-- Supplier Table -->
            <table class="table table-bordered table-striped" id="supplierTable">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Supplier data will be loaded here via AJAX -->
                </tbody>
            </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Supplier Modal -->
<div id="addSupplierModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Supplier</h4>
            </div>
            <div class="modal-body">
                <form id="addSupplierForm">
                    <label>Company:</label>
                    <input type="text" name="company" class="form-control" required>
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" required>
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                    <label>Location:</label>
                    <input type="text" name="location" class="form-control" required>
                    <label>Phone:</label>
                    <input type="text" name="phone" class="form-control" required>
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                    <br>
                    <button type="submit" class="btn btn-success">Add Supplier</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // Fetch all suppliers and display in table
    function fetchSuppliers() {
        $.ajax({
            url: "https://localhost:7224/api/supplier", // Backend API
            method: "GET",
            success: function (response) {
                let supplierData = "";
                $.each(response, function (index, supplier) {
                    supplierData += `
                        <tr>
                            <td>${supplier.company}</td>
                            <td>${supplier.name}</td>
                            <td>${supplier.email}</td>
                            <td>${supplier.location}</td>
                            <td>${supplier.phone}</td>
                            <td> 
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${supplier.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $("#supplierTable tbody").html(supplierData);
            },
            error: function () {
                alert("Failed to fetch supplier data.");
            }
        });
    }

    fetchSuppliers();

    // Add Supplier
    $("#addSupplierForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "https://localhost:7224/api/supplier",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                Company: $("input[name='company']").val(),
                Name: $("input[name='name']").val(),
                Email: $("input[name='email']").val(),
                Location: $("input[name='location']").val(),
                Phone: $("input[name='phone']").val(),
                Password: $("input[name='password']").val()
            }),
            success: function () {
                alert("Supplier added successfully!");
                $("#addSupplierModal").modal("hide");
                fetchSuppliers();
            },
            error: function () {
                alert("Failed to add supplier.");
            }
        });
    });

    // Delete Supplier
    $(document).on("click", ".delete-btn", function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to delete this supplier?")) {
            $.ajax({
                url: `https://localhost:7224/api/supplier/${id}`,
                method: "DELETE",
                success: function () {
                    alert("Supplier deleted successfully!");
                    fetchSuppliers();
                },
                error: function () {
                    alert("Failed to delete supplier.");
                }
            });
        }
    });

    // Edit functionality can be added similarly (like your original example)

});
</script>

</body>
</html>
