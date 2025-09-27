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
  <title>TechFix</title>
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
      <h2>TechFix</h2>
      <ul class="nav nav-pills nav-stacked">
        <li ><a href="dashboard.php">Dashboard</a></li>
        <li class="active"><a href="Staff.php">Staff</a></li>
        <li><a href="supplier.php">Supplier</a></li>
        <li><a href="StaffInventory.php">Inventory</a></li>
        <li><a href="StaffQuotation.php">Quotation</a></li>
      </ul><br>
    </div>
    <br>
    
    <div class="col-sm-9">
      <div class="well">
      <h4>Staff Management</h4>

             <!-- Add Staff Button -->
             <button class="btn btn-primary" data-toggle="modal" data-target="#addStaffModal">Add Staff</button>
                <br/>
                <br/>
            <!-- Staff Table -->
            <table class="table table-bordered table-striped" id="staffTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Staff data will be loaded here via AJAX -->
                </tbody>
            </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Staff Modal -->
<div id="addStaffModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Staff</h4>
            </div>
            <div class="modal-body">
                <form id="addStaffForm">
                    <label>Name:</label>
                    <input type="text" name="name" class="form-control" required>
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                    <label>Phone:</label>
                    <input type="text" name="phone" class="form-control" required>
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                    <br>
                    <button type="submit" class="btn btn-success">Add Staff</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div id="editStaffModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Staff</h4>
            </div>
            <div class="modal-body">
                <form id="editStaffForm">
                    <input type="hidden" id="edit-id" name="id">
                    
                    <label>Name:</label>
                    <input type="text" id="edit-name" name="name" class="form-control" required>
                    
                    <label>Email:</label>
                    <input type="email" id="edit-email" name="email" class="form-control" required>
                    
                    <label>Phone:</label>
                    <input type="text" id="edit-phone" name="phone" class="form-control" required>
                    
                    <label>Password:</label>
                    <input type="password" id="edit-password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                    
                    <br>
                    <button type="submit" class="btn btn-success">Update Staff</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    // Fetch all staff and display in table
    function fetchStaff() {
        $.ajax({
            url: "https://localhost:7224/api/staff", // Backend API
            method: "GET",
            success: function (response) {
                let staffData = "";
                $.each(response, function (index, staff) {
                    staffData += `
                        <tr>
                            <td>${staff.name}</td>
                            <td>${staff.email}</td>
                            <td>${staff.phone}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${staff.id}" data-name="${staff.name}" data-email="${staff.email}" data-phone="${staff.phone}">Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${staff.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $("#staffTable tbody").html(staffData);
            },
            error: function () {
                alert("Failed to fetch staff data.");
            }
        });
    }

    fetchStaff();

    // Add Staff
    $("#addStaffForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "https://localhost:7224/api/staff",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                Name: $("input[name='name']").val(),
                Email: $("input[name='email']").val(),
                Phone: $("input[name='phone']").val(),
                Password: $("input[name='password']").val()
            }),
            success: function () {
                alert("Staff added successfully!");
                $("#addStaffModal").modal("hide");
                fetchStaff();
            },
            error: function () {
                alert("Failed to add staff.");
            }
        });
    });

    // Delete Staff
    $(document).on("click", ".delete-btn", function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to delete this staff member?")) {
            $.ajax({
                url: `https://localhost:7224/api/staff/${id}`,
                method: "DELETE",
                success: function () {
                    alert("Staff deleted successfully!");
                    fetchStaff();
                },
                error: function () {
                    alert("Failed to delete staff.");
                }
            });
        }
    });


    $(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");
    const name = $(this).data("name");
    const email = $(this).data("email");
    const phone = $(this).data("phone");

    // Populate the form fields
    $("#edit-id").val(id);
    $("#edit-name").val(name);
    $("#edit-email").val(email);
    $("#edit-phone").val(phone);
    $("#edit-password").val(""); // Clear the password field

    // Show the modal
    $("#editStaffModal").modal("show");
});


    // Edit Staff - Populate Data
    $("#editStaffForm").on("submit", function (e) {
        e.preventDefault(); // Prevent form default submission

        const id = $("#edit-id").val();
        const data = {
            name: $("#edit-name").val(),
            email: $("#edit-email").val(),
            phone: $("#edit-phone").val(),
            password: $("#edit-password").val() || null
        };

        // Send PUT request
        $.ajax({
            url: `https://localhost:7224/api/staff/${id}`,
            type: "PUT",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function (response) {
                alert("Staff updated successfully!");
                $("#editStaffModal").modal("hide");
                location.reload(); // Reload or update the UI dynamically
            },
            error: function (xhr, status, error) {
                alert("Error updating staff: " + xhr.responseText);
            }
        });
    });

    });
</script>

</body>
</html>
