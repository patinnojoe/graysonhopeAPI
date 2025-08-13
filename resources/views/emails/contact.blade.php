<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Message</title>
</head>

<body>

    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <h2 style="color: #2a9d8f;">New Contact Message</h2>

        <p>
            <strong style="display: inline-block; width: 80px;">Name:</strong>
            <span>{{ $data['first_name'] }} {{ $data['last_name'] }}</span>
        </p>

        <p>
            <strong style="display: inline-block; width: 80px;">Email:</strong>
            <span>{{ $data['email'] }}</span>
        </p>

        <p>
            <strong style="display: inline-block; width: 80px;">Message:</strong><br>
            <span style="display: block; margin-top: 5px;">{{ $data['message'] }}</span>
        </p>
    </div>

</body>

</html>
