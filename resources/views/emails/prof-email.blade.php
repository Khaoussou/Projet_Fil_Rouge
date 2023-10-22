<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <h1>Demande d'annulation de session</h1>
    <h2>Bonjour {{ $user->name }} , vous avez reçu une demande d'annulation de session de la part de Mr {{ $prof->name }}
    </h2>
    <h2>Voici la demande : {{ $content }}</h2>

    <h2>Merci et bonne journée !</h2>

</body>

</html>
