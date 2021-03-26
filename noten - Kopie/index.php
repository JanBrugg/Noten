<?php
// Wenn der User sich abmeldet, alle Cookies löschen
if (isset($_POST["abmelden"])) {
    unset($_COOKIE["username"]);
    setcookie("username", "", 1);

    unset($_COOKIE["passwort"]);
    setcookie("passwort", "", 1);

    echo "Erfolgreich Abgemeldet!";
}

// Wenn man auf die Seite geht und die Cookies hat, sofort anmelden
if ((isset($_COOKIE["username"])) and $_COOKIE["username"] != "") {
    $user = $_COOKIE["username"];
    $passwort = $_COOKIE["passwort"];

    echo '
    <form id="myForm" action="uebersicht.php" method="post">
<input type="hidden" name="username" id="username" value="' . $user . '">
<input type="hidden" name="passwort" id="passwort" value="' . $passwort . '">
<input type="hidden" name="aenderung" id="aenderung" value="aenderung">
</form>
<script type="text/javascript">
    document.getElementById("myForm").submit();
</script>
    '; // habe ich vom Internet "geklaut"

}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login Page - Jan D. Login Projekt</title>
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
    <style>

        body {
            margin: 10px;
        }

    </style>
</head>
<body>
<?php

// Wenn man einen falschen Usernamen oder Passwort eingegeben hat, soll es auch angezeigt werden
$redirect = $_GET["redirect"];
if ($redirect == "falsch_p") {
    echo "Passwort falsch";
    echo $_GET["pass"];
} elseif ($redirect == "falsch_u") {
    echo "Username gibts nicht";
}
?>

<!-- Dies ist das Form mit dem man sich anmeldet -->
<form action="uebersicht.php" method="post">
    <label for="username">Dein Username:</label>
    <input type="text" id="username" name="username">
    <br>
    <label for="passwort">Dein Passwort</label>
    <input type="password" id="passwort" name="passwort">
    <br>
    <input type="checkbox" name="cookie" id="cookie" value="cookie">
    <label for="cookie">Angemeldet bleiben</label> <br>
    <button name="anmelden" type="submit">Schauen</button>
</form>
<p>
    Noch keinen Account? <a style="padding: 5px; border-radius: 4px; background: white" href="erstellen.php"><strong>Jetzt erstellen!</strong></a>
</p>
</body>
</html>