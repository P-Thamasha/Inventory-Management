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
      background-color:rgb(54, 197, 244);
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
    <h2 style="text-align: center;">TechFix Staff Login</h2>
    <?php if (!empty($error)): ?>
      <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="container" style="background-color:#f1f1f1; text-align: right;">
        <button type="button" class="cancelbtn">
          <a href="supplierlogin.php" style="color:black">Supplier Login</a>
        </button>
      </div>

    <form method="POST" action="login.php">
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
