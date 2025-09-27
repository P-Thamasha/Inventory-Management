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
    .row.content {height: 550px}
    .sidenav {
      background-color: #f1f1f1;
      height: 100%;
    }
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
        <li ><a href="Staff.php">Staff</a></li>
        <li><a href="supplier.php">Supplier</a></li>
        <li  class="active"><a href="StaffInventory.php">Inventory</a></li>
        <li><a href="StaffQuotation.php">Quotation</a></li>
      </ul><br>
    </div>
    <br>
    
    <div class="col-sm-9">
      <div class="well">
        <h4>Inventory Management</h4>

        <!-- Supplier Dropdown -->
        <label for="supplierFilter">Filter by Supplier:</label>
        <select id="supplierFilter" class="form-control">
          <option value="">-- Select Supplier --</option>
        </select>
        <br/>

        <!-- Inventory Table -->
        <table class="table table-bordered table-striped" id="inventoryTable">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Quantity</th>
              <th>One Item Price</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

      <div class="well">
        <h4>Quotation Requests</h4>
        <table class="table table-bordered table-striped" id="quotationRequestTable">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Description</th>
              <th>Date Needed</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Request Quotation Modal -->
<div id="requestQuotationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Request Quotation</h4>
      </div>
      <div class="modal-body">
        <form id="requestQuotationForm">
          <div class="form-group">
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control" placeholder="Enter description" required></textarea>
          </div>
          <div class="form-group">
            <label for="dateNeeded">Date Needed:</label>
            <input type="date" id="dateNeeded" name="dateNeeded" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success">Submit Request</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    // Fetch all suppliers and populate the dropdown
    function fetchSuppliers() {
        $.ajax({
            url: "https://localhost:7224/api/supplier",
            method: "GET",
            success: function (response) {
                let options = '<option value="">-- Select Supplier --</option>';
                response.forEach(supplier => {
                    options += `<option value="${supplier.id}">${supplier.name} (${supplier.company})</option>`;
                });
                $("#supplierFilter").html(options);
            },
            error: function () {
                alert("Failed to load suppliers.");
            }
        });
    }

    // Fetch inventory data based on the selected supplier
    function fetchInventoryBySupplier(supplierId) {
        const url = supplierId
            ? `https://localhost:7224/api/inventory/supplier/${supplierId}`
            : "https://localhost:7224/api/inventory";

        $.ajax({
            url: url,
            method: "GET",
            success: function (response) {
                let inventoryData = "";
                response.forEach(inventory => {
                    inventoryData += `
                        <tr>
                            <td>${inventory.itemName}</td>
                            <td>${inventory.quantity}</td>
                            <td>${inventory.price.toFixed(2)} Rs/=</td>
                            <td>
                                <button class="btn btn-primary btn-sm request-quote-btn" data-item="${inventory.itemName}">
                                  Request Quotation
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $("#inventoryTable tbody").html(inventoryData);
            },
            error: function () {
                alert("No inventory in this supplier.");
            }
        });
    }

    // Fetch quotation requests and populate the table
    function fetchQuotationRequests() {
        $.ajax({
            url: "https://localhost:7224/api/quotationrequest",
            method: "GET",
            success: function (response) {
                let requestData = "";
                response.forEach(request => {
                  const formattedDate = new Date(request.dateNeeded).toISOString().split('T')[0];
                    requestData += `
                        <tr>
                            <td>${request.itemName}</td>
                            <td>${request.description}</td>
                            <td>${formattedDate}</td>
                            <td>${request.quantity}</td>
                        </tr>
                    `;
                });
                $("#quotationRequestTable tbody").html(requestData);
            },
            error: function () {
                alert("Failed to fetch quotation requests.");
            }
        });
    }

    // Load suppliers and inventory on page load
    fetchSuppliers();
    fetchInventoryBySupplier(null);
    fetchQuotationRequests();

    // Handle supplier dropdown change
    $("#supplierFilter").on("change", function () {
        const supplierId = $(this).val();
        fetchInventoryBySupplier(supplierId);
    });

    // Open Request Quotation Modal
    $(document).on("click", ".request-quote-btn", function () {
        const itemName = $(this).data("item");
        $("#itemName").val(itemName);
        $("#requestQuotationModal").modal("show");
    });

    // Handle quotation request form submission
    $("#requestQuotationForm").on("submit", function (e) {
        e.preventDefault();

        const requestData = {
            itemName: $("#itemName").val(),
            description: $("#description").val(),
            dateNeeded: $("#dateNeeded").val(),
            quantity: $("#quantity").val()
        };

        $.ajax({
            url: "https://localhost:7224/api/quotationrequest",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(requestData),
            success: function () {
                alert("Quotation request submitted successfully!");
                $("#requestQuotationModal").modal("hide");
                fetchQuotationRequests(); // Refresh the requests table
            },
            error: function () {
                alert("Failed to submit quotation request.");
            }
        });
    });
});
</script>

</body>
</html>
