<?php

// Username und Passwort kommen entweder vom Anmelden oder vom Ändern
$user = $_POST["username"];
$user_id = $_POST["user_id"];
$passwort = $_POST['passwort']; // wenn von Anmeldung: kein Hash; wenn von Änderung: gehashed
$conn = mysqli_connect(); // mit DB verbinden

// hatte Probleme beim reLoggen, also einfach überspringen wenn schon einmal angemeldet war
if (!isset($_POST["aenderung"])) {
    // schauen ob der User existiert
    $sql = "select * from accounts where username = '$user'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        // Username gibts nicht, also zurück zur Anmeldungs-Page
        header("Location: http://jan.ginf.ch/noten/index.php?redirect=falsch_u");
        exit();
    }
    // Wenn der Cookie nicht gesetzt wurde, Passwort überprüfen
    if (!isset($_COOKIE["passwort"])) {
        $sql = "select passwort from accounts where username = '$user';";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res); // fetched das Passwort aus der DB mit dem Username $user

        // wenn das eingegebene Passwort nicht mit dem Hash funktioniert, dann ist das Passwort falsch
        if (!password_verify($passwort, $row["passwort"])) {
            // Passwort Falsch
            header("Location: http://jan.ginf.ch/noten/index.php?redirect=falsch_p");
            exit();
        }
    } else { // also ein Cookie ist gesetzt
        $sql = "select passwort from accounts where username = '$user';";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res); // fetched das Passwort aus der DB mit dem Username $user

        if ($_COOKIE["passwort"] != $row["passwort"]) { // überprüft ob das Passwort im Cookie gleich dem in der DB ist
            // Passwort Falsch, also etwas falsch und abmelden (alle Cookies löschen)
            unset($_COOKIE["username"]);
            setcookie("username", "", 1);
            unset($_COOKIE["passwort"]);
            setcookie("passwort", "", 1);

            header("Location: http://jan.ginf.ch/noten/index.php?redirect=falsch_p");
            exit();
        }
    }
} else { // kommt von der Änderungs-Page
    // wenn man Cookies hat, sollen sie geändert werden
    if (isset($_COOKIE["passwort"])) {
        unset($_COOKIE["passwort"]);
        $zeit = time() + 60 * 60 * 24 * 7;
        unset($_COOKIE["username"]);
        setcookie("username", $user, $zeit);
        setcookie("passwort", $passwort, $zeit);
        // ändert beides, obwohl nur ein ersetzt wurde (weil einfacher)
    }
}
if (isset($_POST["cookie"])) { // wenn beim Anmelden "angemeldet bleiben" geklickt wurde, soll man einen Cookie bekommen
    $zeit = time() + (60 * 60 * 24 * 7);
    setcookie("username", $user, $zeit);
    setcookie("passwort", $row["passwort"], $zeit);
}


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Notenübersicht</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png"> <!-- Favicon ist das Icon oben im Tab -->
</head>
<body style="margin-bottom: 50px;">

<!-- TODO: sortieren nach: Datum, Prüfungstitel, Note, Gewichtung -->

<!-- TODO: Wunschnoten herausfinden -->



<!-- TODO: Pluspunkte berechnen -->

<!-- TODO: Gesamtschnitt berechnen -->

<?php
// Es nimmt alle Daten und zeigt das wichtige an (nichts welches zur Identifikation wichtig ist)
$sql = "select user_id, vorname from accounts where username = '$user'";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

$username = $user;
$vorname = $row["vorname"];
$nachname = $row["nachname"];
$email = $row["email"];
$user_id = $row["user_id"];

function color_farbe($note) {
    if ($note >= 5.75){
        return "blue";
    } elseif ($note >= 5.25) {
        return "cyan";
    } elseif ($note >= 4.75) {
        return "lime";
    } elseif ($note >= 4.25) {
        return "#ff7f7f";
    } else {
        return "red";
    }
}

echo "Hallo " . $row['vorname'] . "!<br>";

// Nimmt die Noten und die Gewichtung von allen Fächern
$sql_fach = "select fach, sum(gewichtung) as Gewichtung, sum(note) as Note from noten where user_id = '$user_id' group by fach";
$result_fach = $conn->query($sql_fach);
if ($result_fach->num_rows == 0) {
    echo "Du hast noch keine Prüfung eingetragen.";
} else {

// erstellt eine Tabelle
    echo "<table id='myTable'> <tbody><thead>
<tr>
<th>Fach</th><th>Prüfung</th><th>Datum</th><th>Gewichtung</th><th>Note</th><th style='background: white;'></th>
</tr>
</thead>";
    while ($row_fach = mysqli_fetch_assoc($result_fach)) {
        // Für jedes Fach wird ein Loop gemacht
        $fach = $row_fach["fach"];

        //
        // Gewichtung herausfinden
        $echt_schnitt_sql = "select fach, note, gewichtung from noten where user_id = '$user_id' and fach = '$fach'";
        $echt_schnitt_result = $conn->query($echt_schnitt_sql);
        $punkte = 0;
        $anzahl_gewicht = 0;
        while ($echt_schnitt_row = mysqli_fetch_assoc($echt_schnitt_result)){
            $punkte += $echt_schnitt_row["note"] * $echt_schnitt_row["gewichtung"];
            $anzahl_gewicht += $echt_schnitt_row["gewichtung"];
        }
        //
        $schnitt_unround = $punkte / $anzahl_gewicht;
        $schnitt = round($schnitt_unround, 3);
        echo "<tr style='background: " . color_farbe($schnitt) . "'>";
        echo "<td><strong>" . $fach . "</strong></td>";
        echo "<td></td> <td></td> <td></td>";
        echo "<td style='background: " . color_farbe($schnitt) . "'><strong><em>" . $schnitt . "</em></strong></td>";
        echo "<td style='background: white;'></td>";
        echo "</tr>";

        // Für jede Prüfung wird ein Eintrag erstellt
        $sql_all = "select * from noten where user_id = '$user_id' and fach = '$fach' order by datum ASC";
        $result_all = $conn->query($sql_all);
        while ($row_all = mysqli_fetch_assoc($result_all)) {
            echo "<tr>";
            echo "<td></td><td>" . $row_all["titel"] . "</td>";
            echo "<td>" . $row_all["datum"] . "</td>";
            echo "<td>" . $row_all["gewichtung"] . "</td>";
            echo "<td>" . $row_all["note"] . "</td>";
            echo "<td style='background: white;'>
            <form action='pruefung_aendern.php' method='post'>
            <button type='submit' name='note_aendern'>Ändern</button>
            <button type='submit' name='note_loeschen'>Löschen</button>
            <input type='hidden' name='user_id' id='user_id' value='" . $user_id . "'>
            <input type='hidden' name='username' id='username' value='" . $username . "'>
            <input type='hidden' name='passwort' id='passwort' value='" . $passwort . "'>
            <input type='hidden' name='noten_id' value='" . $row_all["noten_id"] . "'></form></td>";
            echo "</tr>";
        }
        echo "<tr> <td>‎</td> <td></td> <td></td> <td></td> <td></td> </tr>";

    }
    echo "</tbod></table>";
    echo "<script> document.getElementById('myTable').deleteRow(-1);</script>";
}
mysqli_close($conn)
?>
<!-- Forms für verschiedene Aktivitäten -->

<form action="note_erstellen.php" method="post">
    <button style="background: #0bd104;" type="submit" name="note_erstellen">Noten Erstellen</button>
    <input type="hidden" name="username" id="username" value="<?php echo $user; ?>">
    <input type="hidden" name="passwort" id="passwort" value="<?php echo $passwort; ?>">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
</form>

<form action="index.php" method="post">
    <button style="background: red;" type="submit" name="abmelden">Abmelden</button>
</form>

<table style="width: 33%">
    <tr>
        <td style="background: blue;">5.75+</td>
        <td style="background: cyan;">5.25+</td>
        <td style="background: lime;">4.75+</td>
        <td style="background: #ff7f7f;">4.25+</td>
        <td style="background: red;">-4.24</td>
    </tr>
</table>

<form method='post' action='wunschnote.php'>
    <button style="width: 33%; background: #04a0c4;" type='submit' value='" . $fach . "'>Wunschnote berechnen</button>
    <input type="hidden" name="user_id" value="<?php echo $user_id?>">
    <input type="hidden" name="username" value="<?php echo $username;?>">
    <input type="hidden" name="passwort" value="<?php echo $passwort;?>">
</form>

<!-- Buttons zum Daten ändern -->
<form action="aendern.php" method="post">
    <label>Stimmen die Angaben nicht? Ändere sie hier!</label><br>
    <button type="submit" name="Passwort">Passwort</button>
    <button type="submit" name="Name">Name</button>
    <button type="submit" name="Email">Email</button>
    <button type="submit" name="Username">Username</button>

    <input type="hidden" name="username" value="<?php echo $username;?>">
    <input type="hidden" name="passwort" value="<?php echo $passwort;?>"> <!-- Sends hashed password -->
    <input type="hidden" name="vorname" value="<?php echo $vorname;?>">
    <input type="hidden" name="nachname" value="<?php echo $nachname;?>">
    <input type="hidden" name="email" value="<?php echo $email;?>">
    <input type="hidden" name="user_id" value="<?php echo $user_id?>">

</form>
</body>
</html>