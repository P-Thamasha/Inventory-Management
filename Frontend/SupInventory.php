<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['supplierId'])) {
    header("Location: supplierlogin.php");
    exit();
}

// Retrieve session data
$supplierId = $_SESSION['supplierId'];
$supplierName = $_SESSION['name'];
$supplierEmail = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>TechFix - Inventory Management</title>
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
        <li><a href="supplier-dashboard.php">Dashboard</a></li>
        <li><a href="quotation.php">Quotation</a></li>
        <li class="active"><a href="SupInventory.php">Inventory</a></li>
      </ul><br>
    </div>
    <br>
    <div class="col-sm-9">
      <div class="well">
        <h4>Inventory Management</h4>

        <!-- Add Inventory Button -->
        <button class="btn btn-primary" data-toggle="modal" data-target="#addInventoryModal">Add Inventory</button>
        <br/><br/>

        <!-- Inventory Table -->
        <table class="table table-bordered table-striped" id="inventoryTable">
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Inventory data will be loaded here via AJAX -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Add Inventory Modal -->
<div id="addInventoryModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Inventory</h4>
      </div>
      <div class="modal-body">
        <form id="addInventoryForm">
          <label>Item Name:</label>
          <input type="text" name="itemName" class="form-control" required>
          <label>Quantity:</label>
          <input type="number" name="quantity" class="form-control" required>
          <label>Price:</label>
          <input type="number" step="0.01" name="price" class="form-control" required>
          <br>
          <button type="submit" class="btn btn-success">Add Inventory</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Inventory Modal -->
<div id="editInventoryModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Inventory</h4>
      </div>
      <div class="modal-body">
        <form id="editInventoryForm">
          <input type="hidden" id="edit-id" name="id"> <!-- Hidden field for Inventory ID -->
          
          <label>Item Name:</label>
          <input type="text" id="edit-itemName" name="itemName" class="form-control" required>
          
          <label>Quantity:</label>
          <input type="number" id="edit-quantity" name="quantity" class="form-control" required>
          
          <label>Price:</label>
          <input type="number" step="0.01" id="edit-price" name="price" class="form-control" required>
          <br>
          
          <button type="submit" class="btn btn-success">Update Inventory</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function () {
  // Fetch inventory data
  function fetchInventory() {
    $.ajax({
      url: "https://localhost:7224/api/inventory", // Backend API endpoint
      method: "GET",
      success: function (response) {
        let inventoryData = "";
        $.each(response, function (index, inventory) {
          inventoryData += `
            <tr>
              <td>${inventory.itemName}</td>
              <td>${inventory.quantity}</td>
              <td>${inventory.price.toFixed(2)} Rs/= </td>
              <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="${inventory.id}" data-itemname="${inventory.itemName}" data-quantity="${inventory.quantity}" data-price="${inventory.price}">Edit</button>
                <button class="btn btn-danger btn-sm delete-btn" data-id="${inventory.id}">Delete</button>
              </td>
            </tr>
          `;
        });
        $("#inventoryTable tbody").html(inventoryData);
      },
      error: function () {
        alert("Failed to fetch inventory data.");
      }
    });
  }

 
    fetchInventory();

  // Add Inventory
  $("#addInventoryForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
      url: "https://localhost:7224/api/inventory",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({
        SupplierId: "<?php echo $supplierId; ?>",
        ItemName: $("input[name='itemName']").val(),
        Quantity: $("input[name='quantity']").val(),
        Price: $("input[name='price']").val()
      }),
      success: function () {
        alert("Inventory added successfully!");
        $("#addInventoryModal").modal("hide");
        fetchInventory();
      },
      error: function () {
        alert("Failed to add inventory.");
      }
    });
  });

  // Delete Inventory
  $(document).on("click", ".delete-btn", function () {
    const id = $(this).data("id");
    if (confirm("Are you sure you want to delete this inventory item?")) {
      $.ajax({
        url: `https://localhost:7224/api/inventory/${id}`,
        method: "DELETE",
        success: function () {
          alert("Inventory deleted successfully!");
          fetchInventory();
        },
        error: function () {
          alert("Failed to delete inventory.");
        }
      });
    }
  });

  // Open Edit Modal and Populate Fields
$(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");
    const itemName = $(this).data("itemname");
    const quantity = $(this).data("quantity");
    const price = $(this).data("price");

    // Populate the form fields
    $("#edit-id").val(id); // Hidden field for ID
    $("#edit-itemName").val(itemName);
    $("#edit-quantity").val(quantity);
    $("#edit-price").val(price);

    // Show the modal
    $("#editInventoryModal").modal("show");
});

// Submit Edit Form
$("#editInventoryForm").on("submit", function (e) {
    e.preventDefault(); // Prevent default form submission

    const id = $("#edit-id").val(); // Get the Inventory ID from hidden field
    const data = {
        ItemName: $("#edit-itemName").val(),
        Quantity: $("#edit-quantity").val(),
        Price: $("#edit-price").val()
    };

    // Send Update Request to API
    $.ajax({
        url: `https://localhost:7224/api/Inventory/${id}`, // Pass ID as URL parameter
        method: "PUT", // Use PUT method
        contentType: "application/json",
        data: JSON.stringify(data), // Send updated data
        success: function () {
            alert("Inventory updated successfully!");
            $("#editInventoryModal").modal("hide");
            fetchInventory(); // Reload inventory data
        },
        error: function () {
            alert("Failed to update inventory.");
        }
    });
});



});


</script>
</body>
</html>
