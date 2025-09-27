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
    .card {
      border: 1px solid #ccc;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      background-color: #f9f9f9;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .card-title {
      font-size: 18px;
      font-weight: bold;
    }
    .btn-primary {
      background-color: #4CAF50;
      border: none;
    }
    .btn-primary:hover {
      background-color: #45a049;
    }
    .form-inline .form-control {
      margin-right: 10px;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav hidden-xs">
      <h2>TechFix</h2>
      <ul class="nav nav-pills nav-stacked">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="Staff.php">Staff</a></li>
        <li><a href="supplier.php">Supplier</a></li>
        <li><a href="StaffInventory.php">Inventory</a></li>
        <li  class="active"><a href="StaffQuotation.php">Quotation</a></li>
    </ul><br>
    </div>
    <br>
    
<div class="col-sm-9">
      <div class="well">
        <div class="container">
            <h3>Compare Quotations</h3>
            <div class="form-group">
                <label for="selectItem">Select Item:</label>
                <select id="selectItem" class="form-control">
                <option value="">-- Select Item --</option>
                <!-- Item names will be dynamically populated -->
                </select>
            </div>

            <div class="form-inline">
                <label for="minPrice">Min Price:</label>
                <input type="number" id="minPrice" class="form-control" placeholder="Min Price" step="0.01">

                <label for="maxPrice">Max Price:</label>
                <input type="number" id="maxPrice" class="form-control" placeholder="Max Price" step="0.01">

                <button id="applyFilters" class="btn btn-primary">Apply Filters</button>
            </div>

            <div id="quotationCards" class="row" style="margin-top: 20px;">
                <!-- Quotation cards will be dynamically generated here -->
            </div>

            <div id="orderModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Place Order</h4>
                        </div>
                        <div class="modal-body">
                            <form id="orderForm">
                                <input type="hidden" id="orderQuotationId">
                                <input type="hidden" id="orderSupplierId">
                                <input type="hidden" id="orderPrice">

                                <div class="form-group">
                                    <label for="orderItemName">Item Name:</label>
                                    <input type="text" id="orderItemName" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="orderDescription">Description:</label>
                                    <textarea id="orderDescription" class="form-control" readonly></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="orderQuantity">Quantity:</label>
                                    <input type="number" id="orderQuantity" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="orderTotalValue">Total Value:</label>
                                    <input type="text" id="orderTotalValue" class="form-control" readonly>
                                </div>
                                <button type="submit" class="btn btn-success">Place Order</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div class="well">
      </div>
    <h3>Order Details</h3>
    <table class="table table-bordered table-striped" id="orderTable">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
                <th>Price (Rs)</th>
                <th>Quantity</th>
                <th>Total Value (Rs)</th>
                <th>Order Date</th>
                <th>Supplier Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Order data will be dynamically populated here -->
        </tbody>
    </table>
</div>

  </div>
</div>

<script>
$(document).ready(function () {
    // Load unique item names into the dropdown
    function loadItemNames() {
        $.ajax({
            url: "https://localhost:7224/api/quotation", // API endpoint to fetch all quotations
            method: "GET",
            success: function (response) {
                const uniqueItems = [...new Set(response.map(q => q.itemName))]; // Get unique item names
                let options = `<option value="">-- Select Item --</option>`;
                uniqueItems.forEach(item => {
                    options += `<option value="${item}">${item}</option>`;
                });
                $("#selectItem").html(options);
            },
            error: function () {
                alert("Failed to load item names.");
            }
        });
    }

    loadItemNames(); // Call on page load

    // Fetch quotations based on the selected item and price range
    function fetchQuotationsForItem(itemName, minPrice, maxPrice) {
        if (!itemName) {
            $("#quotationCards").html("<p>Please select an item to view quotations.</p>");
            return;
        }

        $.ajax({
            url: `https://localhost:7224/api/Quotation/GetQuotationsByItem?itemName=${itemName}`, // API endpoint
            method: "GET",
            success: function (response) {
                if (response && response.length > 0) {
                    let filteredQuotations = response;

                    // Apply price range filters
                    if (minPrice) {
                        filteredQuotations = filteredQuotations.filter(q => q.price >= minPrice);
                    }
                    if (maxPrice) {
                        filteredQuotations = filteredQuotations.filter(q => q.price <= maxPrice);
                    }

                    if (filteredQuotations.length > 0) {
                        let cards = "";
                        filteredQuotations.forEach(quotation => {
                            cards += `
                                <div class="col-md-4">
                                    <div class="card">
                                        <h4 class="card-title">${quotation.companyName}</h4>
                                        <p><strong>Supplier:</strong> ${quotation.supplierName}</p>
                                        <p><strong>Email:</strong> ${quotation.supplierEmail}</p>
                                        <p><strong>Phone:</strong> ${quotation.supplierPhone}</p>
                                        <p><strong>Item:</strong> ${quotation.itemName}</p>
                                        <p><strong>Description:</strong> ${quotation.description || "N/A"}</p>
                                        <p><strong>Price:</strong> ${quotation.price} Rs</p>
                                        <button 
                                            class="btn btn-primary order-btn" 
                                            data-id="${quotation.id}" 
                                            data-supplierid="${quotation.supplierId}" 
                                            data-itemname="${quotation.itemName}" 
                                            data-description="${quotation.description}" 
                                            data-price="${quotation.price}">
                                            Order
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                        $("#quotationCards").html(cards);
                    } else {
                        $("#quotationCards").html("<p>No quotations found in the selected price range.</p>");
                    }
                } else {
                    $("#quotationCards").html("<p>No quotations found for the selected item.</p>");
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText); // Log error details
                $("#quotationCards").html("<p>Failed to fetch quotations for the selected item.</p>");
            }
        });
    }

    // Handle dropdown and filters
    $("#applyFilters").on("click", function () {
        const selectedItem = $("#selectItem").val();
        const minPrice = parseFloat($("#minPrice").val());
        const maxPrice = parseFloat($("#maxPrice").val());
        fetchQuotationsForItem(selectedItem, minPrice, maxPrice);
    });

    // Handle "Order" button click
    $(document).on("click", ".order-btn", function () {
    const quotationId = $(this).data("id");
    const supplierId = $(this).data("supplierid"); // Ensure this is correct
    const itemName = $(this).data("itemname");
    const description = $(this).data("description");
    const price = $(this).data("price");

    if (!supplierId) {
        alert("SupplierId is missing!");
        return;
    }

    $("#orderQuotationId").val(quotationId);
    $("#orderSupplierId").val(supplierId);
    $("#orderItemName").val(itemName);
    $("#orderDescription").val(description);
    $("#orderPrice").val(price);

    $("#orderQuantity").val("");
    $("#orderTotalValue").val("");

    $("#orderModal").modal("show");
});

    // Calculate total value dynamically based on quantity
    $("#orderQuantity").on("input", function () {
        const price = parseFloat($("#orderPrice").val());
        const quantity = parseInt($(this).val()) || 0;
        $("#orderTotalValue").val((price * quantity).toFixed(2));
    });

    // Handle form submission
    $("#orderForm").on("submit", function (e) {
    e.preventDefault();

    const data = {
        QuotationId: $("#orderQuotationId").val(),
        SupplierId: $("#orderSupplierId").val(),
        ItemName: $("#orderItemName").val(),
        Description: $("#orderDescription").val(),
        Price: parseFloat($("#orderPrice").val()),
        Quantity: parseInt($("#orderQuantity").val()),
        TotalValue: parseFloat($("#orderTotalValue").val())
    };

    console.log("Payload:", data); // Log payload

    $.ajax({
        url: "https://localhost:7224/api/order",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(data),
        success: function () {
            alert("Order placed successfully!");
            $("#orderModal").modal("hide");
        },
        error: function (xhr) {
            console.error(xhr.responseText); // Log the error response
            alert("Failed to place order.");
        }
    });
});

function fetchOrders() {
    $.ajax({
        url: "https://localhost:7224/api/order", // Replace with the API endpoint to fetch all orders
        method: "GET",
        success: function (response) {
            let orderData = "";
            response.forEach(order => {
                const orderDate = new Date(order.orderDate).toISOString().split('T')[0]; // Format date
                orderData += `
                    <tr>
                        <td>${order.itemName}</td>
                        <td>${order.description || "N/A"}</td>
                        <td>${order.price.toFixed(2)}</td>
                        <td>${order.quantity}</td>
                        <td>${order.totalValue.toFixed(2)}</td>
                        <td>${orderDate}</td>
                        <td>${order.supplierName || "N/A"}</td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-order-btn" data-id="${order.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $("#orderTable tbody").html(orderData);
        },
        error: function () {
            alert("Failed to fetch orders.");
        }
    });
}

// Call this function on page load
$(document).ready(function () {
    fetchOrders();
});


$(document).on("click", ".delete-order-btn", function () {
    const orderId = $(this).data("id");

    if (confirm("Are you sure you want to delete this order?")) {
        $.ajax({
            url: `https://localhost:7224/api/order/${orderId}`, // Replace with the API endpoint to delete an order
            method: "DELETE",
            success: function () {
                alert("Order deleted successfully!");
                fetchOrders(); // Refresh the order table
            },
            error: function () {
                alert("Failed to delete order.");
            }
        });
    }
});



});

</script>

</body>
</html>
