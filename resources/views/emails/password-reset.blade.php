<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 10px; }
        .button { display: inline-block; padding: 10px 20px; color: white; background-color: #28a745; text-decoration: none; border-radius: 5px; }
        .footer { margin-top: 20px; text-align: center; font-size: 12px; color: gray; }
    </style>
</head>
<body>
<div class="container">
    <h2>Password Reset Request</h2>
    <p>Hello,</p>
    <p>You requested a password reset. Click the button below to reset your password:</p>
    <p><a href="{{ $resetLink }}" class="button">Reset Password</a></p>
    <p>If you didn't request this, ignore this email.</p>
    <div class="footer">© {{ date('Y') }} YourApp. All rights reserved.</div>
</div>
</body>
</html>
