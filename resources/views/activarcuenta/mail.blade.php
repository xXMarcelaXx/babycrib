<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Bienvenido {{ $name }}</h1>
    <h3>Da click en el enlase para mandar codigo de verificacion al numero <b>{{ $tel }}</b></h3>
    <a href="{{ $url }}" type="button">Verificacion</a>
</body>
</html>