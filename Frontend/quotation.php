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

    /* Form Fields */
form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 14px;
    margin-bottom: 5px;
    text-align: left;
}

input, textarea {
    font-size: 16px;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
    min-height: 80px;
}

/* Button */
button {
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #45a049;
}
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row content">
  <div class="col-sm-3 sidenav hidden-xs">
       <h3>TechFix Supplier</h3>
      <ul class="nav nav-pills nav-stacked">
        <li><a href="supplier-dashboard.php">Dashboard</a></li>
        <li class="active"><a href="quotation.php">Quatation</a></li>
        <li><a href="SupInventory.php">Inventory</a></li>
      </ul><br>
    </div>
    <br>
    
    <div class="col-sm-9">
      <div class="well">
      <h4>Add Quotation</h4>

             <!-- Add Quotation Button -->
             <button class="btn btn-primary" data-toggle="modal" data-target="#addStaffModal">Add Quotation</button>
                <br/>
                <br/>
            <!-- Quotation Table -->
            <table class="table table-bordered table-striped" id="staffTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Quotation data will be loaded here via AJAX -->
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

<!-- Add Quotation Modal -->
<div id="addStaffModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Quotation</h4>
            </div>
            <div class="modal-body">
            <form id="quotationForm">
            <label for="supplierId">Supplier:</label>
            <select id="supplierId" name="supplierId" class="form-control" required>
                <!-- Options will be populated dynamically -->
            </select>
            <label for="companyName">Company Name:</label>
            <input type="text" id="companyName" name="companyName" class="form-control" placeholder="Enter company name" required>

            <label for="companyAddress">Company Address:</label>
            <textarea id="companyAddress" name="companyAddress" class="form-control" placeholder="Enter company address" required></textarea>

            <label for="phoneNumber">Phone Number:</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="Enter phone number" required>

            <label for="item">Item Name:</label>
            <input type="text" id="item" name="item" class="form-control" placeholder="Enter item name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control" placeholder="Enter description" required></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" placeholder="Enter price" required>
            <button type="submit" class="btn btn-success">Submit Quotation</button>
        </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Quotation Modal -->
<div id="editQuotationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Quotation</h4>
            </div>
            <div class="modal-body">
                <form id="editQuotationForm">
                    <input type="hidden" id="edit-id" name="id">

                    <label for="edit-supplierId">Supplier:</label>
                    <select id="edit-supplierId" name="supplierId" class="form-control" required>
                        <!-- Options will be populated dynamically -->
                    </select>

                    <label for="edit-companyName">Company Name:</label>
                    <input type="text" id="edit-companyName" name="companyName" class="form-control" required>

                    <label for="edit-companyAddress">Company Address:</label>
                    <textarea id="edit-companyAddress" name="companyAddress" class="form-control" required></textarea>

                    <label for="edit-phoneNumber">Phone Number:</label>
                    <input type="tel" id="edit-phoneNumber" name="phoneNumber" class="form-control" required>

                    <label for="edit-item">Item Name:</label>
                    <input type="text" id="edit-item" name="item" class="form-control" required>

                    <label for="edit-description">Description:</label>
                    <textarea id="edit-description" name="description" class="form-control" required></textarea>

                    <label for="edit-price">Price:</label>
                    <input type="number" id="edit-price" name="price" class="form-control" step="0.01" required>

                    <button type="submit" class="btn btn-success">Update Quotation</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function () {

    function populateSuppliers() {
        $.ajax({
            url: "https://localhost:7224/api/supplier", // Fetch suppliers
            method: "GET",
            success: function (response) {
                let supplierOptions = `<option value="" disabled selected>Select Supplier</option>`;
                response.forEach(supplier => {
                    supplierOptions += `<option value="${supplier.id}">${supplier.name} - ${supplier.company}</option>`;
                });
                $("#supplierId").html(supplierOptions);
            },
            error: function () {
                alert("Failed to fetch suppliers.");
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

  
    fetchQuotationRequests();


    populateSuppliers(); // Call this on page load

    function populateEditSuppliers(selectedSupplierId) {
    $.ajax({
        url: "https://localhost:7224/api/supplier", // Fetch suppliers
        method: "GET",
        success: function (response) {
            let supplierOptions = `<option value="" disabled>Select Supplier</option>`;
            response.forEach(supplier => {
                supplierOptions += `<option value="${supplier.id}" ${supplier.id === selectedSupplierId ? 'selected' : ''}>
                    ${supplier.name} - ${supplier.company}
                </option>`;
            });
            $("#edit-supplierId").html(supplierOptions);
        },
        error: function () {
            alert("Failed to fetch suppliers.");
        }
    });
    }

    populateEditSuppliers();

    // Fetch all quotations and populate the table
    function fetchQuotations() {
    $.ajax({
        url: "https://localhost:7224/api/quotation", // API endpoint
        method: "GET",
        success: function (response) {
            let quotationData = "";
            response.forEach(quotation => {
                quotationData += `
                    <tr>
                        <td>${quotation.companyName}</td>
                        <td>${quotation.companyAddress}</td>
                        <td>${quotation.itemName}</td>
                        <td>${quotation.price}</td>
                        <td>${quotation.phoneNumber}</td>
                        <td>
                            <button class="btn btn-warning btn-sm edit-btn" 
                                data-id="${quotation.id}" 
                                data-companyname="${quotation.companyName}" 
                                data-companyaddress="${quotation.companyAddress}" 
                                data-phonenumber="${quotation.phoneNumber}" 
                                data-item="${quotation.itemName}" 
                                data-description="${quotation.description}" 
                                data-price="${quotation.price}">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${quotation.id}">Delete</button>
                        </td>
                    </tr>
                `;
            });
            $("#staffTable tbody").html(quotationData);
        },
        error: function () {
            alert("Failed to fetch quotations.");
        }
    });
}

    // Load quotations on page load
    fetchQuotations();

    // Add Quotation
   // Add Quotation
    $("#quotationForm").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        const data = {
            supplierId: $("#supplierId").val(),
            companyName: $("#companyName").val(),
            companyAddress: $("#companyAddress").val(),
            phoneNumber: $("#phoneNumber").val(),
            itemName: $("#item").val(), // Match the DTO property
            description: $("#description").val(),
            price: $("#price").val()
        };

        $.ajax({
            url: "https://localhost:7224/api/quotation",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function () {
                alert("Quotation added successfully!");
                $("#addStaffModal").modal("hide");
                fetchQuotations(); // Refresh the table
            },
            error: function (xhr) {
                console.error(xhr.responseText); // Log the error for debugging
                alert("Failed to add quotation.");
            }
        });
    });

    // Delete Quotation
    $(document).on("click", ".delete-btn", function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to delete this quotation?")) {
            $.ajax({
                url: `https://localhost:7224/api/quotation/${id}`,
                method: "DELETE",
                success: function () {
                    alert("Quotation deleted successfully!");
                    fetchQuotations();
                },
                error: function () {
                    alert("Failed to delete quotation.");
                }
            });
        }
    });

    // Open Edit Modal and Populate Fields
    $(document).on("click", ".edit-btn", function () {
    const id = $(this).data("id");
    $("#edit-id").val(id);
    $("#edit-companyName").val($(this).data("companyname"));
    $("#edit-companyAddress").val($(this).data("companyaddress"));
    $("#edit-phoneNumber").val($(this).data("phonenumber"));
    $("#edit-item").val($(this).data("item"));
    $("#edit-description").val($(this).data("description"));
    $("#edit-price").val($(this).data("price"));

    const selectedSupplierId = $(this).data("supplierid");
    populateEditSuppliers(selectedSupplierId);

    $("#editQuotationModal").modal("show");

    });

  


    // Submit Edit Form
   // Submit Edit Quotation Form
    $("#editQuotationForm").on("submit", function (e) {
        e.preventDefault(); // Prevent default form submission

        const id = $("#edit-id").val(); // Quotation ID
        const data = {
            supplierId: $("#edit-supplierId").val(),
            companyName: $("#edit-companyName").val(),
            companyAddress: $("#edit-companyAddress").val(),
            phoneNumber: $("#edit-phoneNumber").val(),
            itemName: $("#edit-item").val(), // Match the DTO property
            description: $("#edit-description").val(),
            price: $("#edit-price").val()
        };

        $.ajax({
            url: `https://localhost:7224/api/quotation/${id}`,
            method: "PUT",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function () {
                alert("Quotation updated successfully!");
                $("#editQuotationModal").modal("hide");
                fetchQuotations(); // Refresh the table
            },
            error: function (xhr) {
                console.error(xhr.responseText); // Log the error for debugging
                alert("Failed to update quotation.");
            }
        });
    });

    });

</script>


</body>
</html>
