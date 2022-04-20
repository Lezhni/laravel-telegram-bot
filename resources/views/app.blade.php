<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Telegram Bot</title>
    <style>
        /*body {*/
        /*    overflow-y: scroll;*/
        /*}*/
    </style>
</head>
<body>
<div id="app"></div>

<script src="{{ mix('js/main.js') }}"></script>
</body>
</html>
