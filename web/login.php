<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Serenitea</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f9f4f0;
            display: flex;
            height: 100vh;
        }
        .left-section, .right-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .left-section {
            background-color: #fce9e3;
        }
        .left-section img {
            max-width: 190%;
            height: auto;
            border-radius: 10px;
        }
        .right-section {
            background-color: #fff;
            padding: 40px;
            position: relative;
        }
        .card {
            background-color: #F2E9E6;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .card h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #6b4226;
        }
        form {
            text-align: left;
        }
        form label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #6b4226;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        form input.error {
            border-color: #ff4d4d;
            box-shadow: 0 0 8px rgba(255, 77, 77, 0.6);
        }
        .error-message {
            color: #ff4d4d;
            font-size: 14px;
            margin-bottom: 15px;
            display: none;
        }
        .error-message.visible {
            display: block;
        }
        form button {
            background-color: #6b4226;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        form button:hover {
            background-color: #8a5634;
        }
        .info-message {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #6b4226;
        }
    </style>
</head>
<body>
    <div class="left-section">
        <img src="uploads/logo.png" alt="Serenitea Logo">
    </div>
    <div class="right-section">
        <div class="card">
            <h2>Login</h2>
            <form action="login_process.php" method="POST">
                <div class="error-message <?php if(isset($_GET['error'])) echo 'visible'; ?>">
                    Incorrect Email or Password
                </div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" class="<?php if(isset($_GET['error'])) echo 'error'; ?>" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" class="<?php if(isset($_GET['error'])) echo 'error'; ?>" required>
                
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
