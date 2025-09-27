<?php
session_start(); // Start the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input data from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Backend API endpoint
    $apiUrl = "https://localhost:7224/api/staff/login";

    // Prepare data to send as JSON
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
        if ($httpCode == 200 && isset($result['staffId'])) {
            // Successful login: Store session variables
            $_SESSION['staffId'] = $result['staffId'];
            $_SESSION['name'] = $result['name'];
            $_SESSION['email'] = $result['email'];

            // Redirect to the dashboard
            header("Location: dashboard.php");
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
