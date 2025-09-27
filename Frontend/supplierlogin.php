<?php
session_start(); // Start the session

$error = ""; // Initialize error variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input data from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Backend API endpoint
    $apiUrl = "https://localhost:7224/api/Supplier/login";

    // Prepare JSON data to send to the API
    $postData = json_encode([
        "Email" => $email,
        "Password" => $password
    ]);

    // Initialize cURL
    $ch = curl_init($apiUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    // Disable SSL verification for localhost testing
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check for cURL errors
    if (curl_errno($ch)) {
        $error = "Error: " . curl_error($ch);
    } else {
        $result = json_decode($response, true);

        // Check API response
        if ($httpCode == 200 && isset($result['supplierId'])) {
            // Successful login: Store session variables
            $_SESSION['supplierId'] = $result['supplierId'];
            $_SESSION['name'] = $result['name'];
            $_SESSION['email'] = $result['email'];

            // Redirect to the dashboard
            header("Location: supplier-dashboard.php");
            exit();
        } else {
            // Login failed: Display error message
            $error = $result['message'] ?? "Invalid email or password.";
        }
    }

    // Close cURL session
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f4f4f9;
    }

    form {
      border: 3px solid #f1f1f1;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    input[type=email],
    input[type=password] {
      width: 100%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #04AA6D;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 100%;
      border-radius: 4px;
    }

    button:hover {
      opacity: 0.8;
    }

    .cancelbtn {
      width: auto;
      padding: 10px 18px;
      background-color: rgb(54, 197, 244);
      border-radius: 4px;
    }

    .imgcontainer {
      text-align: center;
      margin: 24px 0 12px 0;
    }

    img.avatar {
      width: 100px;
      border-radius: 50%;
    }

    .container {
      padding: 16px;
    }

    span.psw {
      float: right;
      padding-top: 16px;
    }

    @media screen and (max-width: 300px) {
      span.psw {
        display: block;
        float: none;
      }

      .cancelbtn {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <h2 style="text-align: center;">TechFix Supplier Login</h2>

    <?php if (!empty($error)): ?>
      <div style="color: red; text-align: center; margin-bottom: 16px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="container" style="background-color:#f1f1f1; text-align: right;">
      <button type="button" class="cancelbtn">
        <a href="index.php" style="color:black; text-decoration:none;">Staff Login</a>
      </button>
    </div>

    <form method="POST" action="">
      <div class="imgcontainer">
        <img src="img/profile.png" alt="Avatar" class="avatar">
      </div>

      <div class="container">
        <label for="email"><b>Email</b></label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="password"><b>Password</b></label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
        <label>
          <input type="checkbox" checked="checked" name="remember"> Remember me
        </label>
      </div>
    </form>
  </div>
</body>

</html>
