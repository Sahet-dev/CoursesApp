<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: gray;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Verify Your Email Address</h2>
    <p>Hi,</p>
    <p>Here is your verification code: <h2><strong>{{ $code }}</strong></h2>
    </p>
    <p>This code will expire in 30 minutes.</p>


    <p>If you didn't request this, please ignore this email.</p>
    <div class="footer">© {{ date('Y') }} YourApp. All rights reserved.</div>
</div>
</body>
</html>
