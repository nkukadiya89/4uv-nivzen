<!DOCTYPE html>
<html>
<head>
    <title>Account Created</title>
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
    <div class="header">Hello {{ $user['firstname'] }} {{ $user['lastname'] }}!</div>
    <div class="content">
        Congratulations! Your account has been created successfully. You can login now and start using our service.
        <br><br>
        <strong>Email:</strong> <a href="mailto:{{ $user['email'] }}" class="highlight">{{ $user['email'] }}</a><br>
        <strong>Password:</strong> <span class="highlight">{{ $password }}</span>
    </div>
    <div class="footer">
        Regards, <br> 4UV
    </div>
</div>
</body>
</html>
