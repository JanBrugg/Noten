<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Account erstellen - Jan D. Login Projekt</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
    <style> input{color: black;} </style>
</head>
<body>


<!-- Form zum Account Erstellen -->
<?php
if (isset($_GET["nachricht"])){
    echo $_GET["nachricht"];
}
?>
<form action="erstellen_erfolgreich.php" method="post">
    <label for="username">Username: </label>
    <input type="text" id="username" name="username" required placeholder="bsp. MaxMuster04"><br>
    <label for="vorname">Dein Name: </label>
    <input type="text" id="vorname" name="vorname" required placeholder="bsp. Max"><br>
    <label for="nachname">Dein Nachname: </label>
    <input type="text" id="nachname" name="nachname" required placeholder="bsp. Muster"><br>
    <label for="email">Deine Email: </label>
    <input type="email" id="email" name="email" required placeholder="bsp. max@muster.de"><br>
    <label for="passwort">Dein Passwort: </label>
    <input type="password" id="passwort" name="passwort" required placeholder="sicheres Passwort"><br>

    <button type="submit" name="erstellen">Erstelle Account!</button>
</form>

</body>
</html>