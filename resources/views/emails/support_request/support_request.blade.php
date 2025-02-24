<!DOCTYPE html>
<html>
<head>
    <title>New Support Request</title>
</head>
<body>
<h2>New Support Request</h2>
<p><strong>Request Number:</strong> {{ $supportRequest->request_number }}</p>
<p><strong>From:</strong> {{ $supportRequest->fromUser->name }}</p>
{{--<p><strong>To:</strong> {{ $supportRequest->toUser->name }}</p>--}}
<p><strong>Support Name:</strong> {{ $supportRequest->support_name }}</p>
<p><strong>Description:</strong> {{ $supportRequest->description }}</p>
<p>Thank you.</p>
</body>
</html>
