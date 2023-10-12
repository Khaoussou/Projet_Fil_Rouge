<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>

<body>

    <h1>Notification de mot de passe</h1>
    <h2>Bonjour {{ $user->name }} , voici votre mot de passe : {{ $content }}</h2>
    <p style="font-size: 2em">Si vous voulez le modifier veuillez cliquer sur le lien ci dessous:</p>

    <a href="Http://localhost:4200/updatePassWord" style="font-size: 2em">
        <p>Modifier votre mot de passe !</p>
    </a>

    <h2>Merci et bonne journée !</h2>

</body>

</html>
