<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        .highlight {
            font-weight: bold;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">Hello {{ $userArray['firstname'] }} {{ $userArray['lastname'] }}!</div>
    <div class="content">
        You requested a password reset. Click the button below to reset your password.
        <br><br>
         <a style="text-decoration: underline;display: inline-block;background-color: #007bff;color: #ffffff;text-decoration: none;padding: 10px 20px;border-radius: 5px;font-size: 16px;" href="{{ $data['link'] }}" class="button">Reset Password</a><br>
        If you did not request a password reset, please ignore this email.
    </div>
    <div class="footer">
        Regards, <br> 4UV
    </div>
</div>
</body>
</html>
