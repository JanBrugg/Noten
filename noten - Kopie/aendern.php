<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Daten ändern - Jan D. Login Projekt</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
    <style> input{color: black;} </style>
</head>
<body>

<?php
// nimmt alle Daten auf
$email = $_POST["email"];
$altes_passwort = $_POST["passwort"]; // hashed password
$username = $_POST["username"];
$vorname = $_POST["vorname"];
$nachname = $_POST["nachname"];
$type = $_POST["redirect_type"];
$user_id = $_POST["user_id"];

// Alle Forms sollen diese hidden Variables haben
$text = '<form action="aenderung_erfolgreich.php" method="post">
<input type="hidden" value="' . $email . '" name="email" id="email">
<input type="hidden" value="' . $altes_passwort . '" name="passwort" id="passwort"> <!-- sends hashed password -->
<input type="hidden" value="' . $username . '" name="username" id="username">
<input type="hidden" value="' . $vorname . '" name="vorname" id="vorname">
<input type="hidden" value="' . $nachname . '" name="nachname" id="nachname">
<input type="hidden" value="' . $user_id . '" name="user_id" id="uesr_id"';

// jetzt schauen welche Änderung gewollt ist
// Passwort
if ((isset($_POST["Passwort"])) or ($type == "passwort")) {
    echo $text . '<label for="alt">Altes Passwort: </label>
    <input id="alt" name="alt" type="password"><br>
    <label for="neu" >Neues Passwort: </label>
    <input id="neu" name="neu" type="password"><br>
    <label for="neu_wiederholt">Neues Passwort wiederholen: </label>
    <input id="neu_wiederholt" name="neu wiederholt" type="password">
    <input type="hidden" value="passwort" id="type" name="type">
    <button type="submit">Ändern!</button></form>';
}
// Vor- und Nachname
elseif (isset($_POST["Name"])) {
    echo $text . '<label for="neu_vorname" >Neuer Vorname: </label>
    <input id="neu_vorname" name="neu_vorname" type="text"><br>
    <label for="neu_nachname">Neuer Nachname: </label>
    <input id="neu_nachname" name="neu_nachname" type="text">
    <input type="hidden" value="name" id="type" name="type">
    <button type="submit">Ändern!</button></form>';
}
// Email
elseif ((isset($_POST["Email"])) or ($type == "email")) {
    echo $text . '<label for="neu_email" >Neue Email: </label>
    <input id="neu_email" name="neu_email" type="text"><br>
    <label for="email_bes">Neue Email bestätigen: </label>
    <input id="email_bes" name="email_bes" type="text">
    <input type="hidden" value="email" id="type" name="type">
    <button type="submit">Ändern!</button></form>';
}
// Username
elseif ((isset($_POST["Username"])) or $type == "username") {
    echo $text . '<label for="neu_username" >Neuer Username: </label>
    <input id="neu_username" name="neu_username" type="text"><br>
    <label for="neu_username_bes">Neuer Username bestätigen: </label>
    <input id="neu_username_bes" name="neu_username_bes" type="text">
    <input type="hidden" value="username" id="type" name="type">
    <button type="submit">Ändern!</button></form>';
}

// Falls Falschinformationen eingegeben wurden, wird es hier erwähnt
if (isset($_POST["nachricht"])) {
    echo $_POST["nachricht"];
}
?>

</body>
</html>