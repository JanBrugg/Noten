<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Erfolgreiche Datenänderung - Jan D. Login Projekt</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
</head>
<body>


<?php
// was wird geändert
$type = $_POST["type"];

// standard bevor geändert wurde
$email = $_POST["email"];
$passwort = $_POST["passwort"]; // hashed password
$username = $_POST["username"];
$vorname = $_POST["vorname"];
$nachname = $_POST["nachname"];
$user_id = $_POST["user_id"];


// zum nochmals Senden (hidden Variables)
$form = '
<input type="hidden" name="email" id="email" value="' . $email . '">
<input type="hidden" name="passwort" id="passwort" value="' . $passwort . '"> <!-- sends hashed password -->
<input type="hidden" name="username" id="username" value="' . $username . '">
<input type="hidden" name="vorname" id="vorname" value="' . $vorname . '">
<input type="hidden" name="nachname" id="nachname" value="' . $nachname . '">
<input type="hidden" name="vorname" id="vorname" value="">
</form>
<script type="text/javascript">
    document.getElementById("myForm").submit();
</script>';

$conn = mysqli_connect();

// Was ändern?
// Passwort
if ($type == "passwort") {
    // Daten vom User werden gespeichert
    $alt_check = $_POST["alt"]; // unhashed user input old
    $neu = $_POST["neu"]; // new pass
    $neu_check = $_POST["neu_wiederholt"]; // new pass verify

    // Sucht die Daten die mit dem User verknüpft sind
    $sql = "SELECT * FROM accounts WHERE user_id = '$user_id'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    // nimmt das Hash-Passwort aus der DB
    $hash_alt_check = $row["passwort"];

    // Passwort wird überprüft
    if (password_verify($alt_check, $hash_alt_check)) { // Wird überprüft ob das eingegebene Passwort mit dem  in der DB stimmt
        // Übereinstimmung des neuen Passworts wird überprüft
        if ($neu == $neu_check) {

            // if true, update hashed password
            $hash_neu = password_hash($neu, PASSWORD_DEFAULT);
            $sql = "UPDATE accounts SET passwort = '$hash_neu' WHERE user_id = '$user_id';";
            $result = $conn->query($sql);

            $res = mysqli_query($conn, $sql);
            if ($res == 1) {
                echo "<p>";
                echo "Passwort erfolgreich geändert";
                echo "</p>";
            }
            mysqli_close($conn);
            $passwort = $hash_neu;
        } else { // Entweder alt != neu oder neu != neu_bes, also Fehler anzeigen
            echo '<form id="myForm" action="aendern.php" method="post">
<input type="hidden" name="nachricht" id="nachricht" value="Passwörter stimmen nicht überein.">
<input type="hidden" name="redirect_type" id="redirect_type" value="passwort">' . $form;
        }
    } else {
        echo '<form id="myForm" action="aendern.php" method="post">
<input type="hidden" name="nachricht" id="nachricht" value="Altes Passwort stimmt nicht.">
<input type="hidden" name="redirect_type" id="redirect_type" value="passwort">' . $form;
    }

}
// Vor- und Nachname
elseif ($type == "name") {
    // Userinput wird gespeichert
    $neu_vorname = $_POST["neu_vorname"];
    $neu_nachname = $_POST["neu_nachname"];

    // Vorname wird geändert
    $sql = "UPDATE accounts SET vorname = '$neu_vorname' WHERE user_id = '$user_id';";
    $result = $conn->query($sql);

    $result = mysqli_query($conn, $sql);
    if ($result == 1) {
        echo "<p>";
        echo "Vorname erfolgreich geändert";
        echo "</p>";
    }

    // Nachname wird geändert
    $sql = "UPDATE accounts SET nachname = '$neu_nachname' WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    $result = mysqli_query($conn, $sql);
    if ($result == 1) {
        echo "<p>";
        echo "Nachname erfolgreich geändert";
        echo "</p>";
    }
}
// Email
elseif ($type == "email") {
    // Userinput wird gespeichert
    $neu_email = $_POST["neu_email"];
    $neu_email_bes = $_POST["email_bes"];

    // wird geschaut, ob Emails gleich sind (Fehldaten vermeiden)
    if ($neu_email == $neu_email_bes) {
        $sql = "UPDATE accounts SET email = '$neu_email' WHERE user_id = '$user_id';";
        $result = $conn->query($sql);

        $result = mysqli_query($conn, $sql);
        if ($result == 1) {
            echo "<p>";
            echo "Email erfolgreich geändert";
            echo "</p>";
        }
    } else { // Emails sind nicht gleich, also Fehlermeldung
        echo '<form id="myForm" action="aendern.php" method="post">
    <input type="hidden" name="nachricht" id="nachricht" value="Emails stimmen nicht überein.">
    <input type="hidden" name="redirect_type" id="redirect_type" value="email">' . $form;
    }
}
// Username
elseif ($type == "username") {
    // Userinput wird gespeichert
    $neu_username = $_POST["neu_username"];
    $neu_username_bes = $_POST["neu_username_bes"];

    // Überprüft ob Usernamen übereinstimmen, um Fehler zu vermeiden
    if ($neu_username == $neu_username_bes) {
        // Wenn gleich, update
        $sql = "UPDATE accounts SET username = '$neu_username' WHERE user_id = '$user_id';";
        $result = $conn->query($sql);

        $result = mysqli_query($conn, $sql);
        if ($result == 1) {
            echo "<p>";
            echo "Username erfolgreich geändert";
            echo "</p>";
        }

        // hier wird der Username mit dem neuen ersetzt
        $username = $neu_username;
    } else { // Bei Fehler Fehlermelding anzeigen
        echo '<form id="myForm" action="aendern.php" method="post">
    <input type="hidden" name="nachricht" id="nachricht" value="Usernamen stimmen nicht überein.">
    <input type="hidden" name="redirect_type" id="redirect_type" value="username">' . $form;
    }
}

mysqli_close($conn)

?>

<form action="uebersicht.php" method="post">
    <input type="hidden" name="username" id="username" value="<?php echo $username; ?>">
    <input type="hidden" name="passwort" id="passwort" value="<?php echo $passwort;?>">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
    <button type="submit" name="aenderung" id="aenderung">Startseite</button>
</form>


</body>
</html>