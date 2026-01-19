<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PRINT QR CODE</title>
    <style>
        div.item {
            vertical-align: top;
            display: inline-block;
            text-align: center;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }
        .caption {
            display: block;
            font-weight: bold;
            font-size: 32px;
        }
    </style>
</head>
<body>
    <div class="item">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ route("wh.qr-view", $wh->id) }}" alt="">
        <span class="caption">{{ "LOC".sprintf("%06d", $wh->id) }}</span>
    </div>
</body>
</html>
