<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Note Speichern</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
</head>
<body>

<?php
// User-Daten
$username = $_POST["username"];
$user_id = $_POST["user_id"];
$passwort = $_POST["passwort"];

// Note-Daten
$titel = $_POST["titel"];
$note_string = $_POST["note"]; // ist noch ein String
$note = floatval($note_string); // wenn Zahlen am Anfang sind ein Float, nimmt es die
$gewicht_string = $_POST["gewicht"]; // gleich hier machen
$gewicht = floatval($gewicht_string);
$datum = $_POST["datum"];
// Schauen ob neues oder bestehendes Fach benutzt wird
if ($_POST["fach_sel"] == "nothing") {
    $fach = $_POST["fach_neu"];
} else {
    $fach = $_POST["fach_sel"];
}

$conn = mysqli_connect(); // mit DB verbinden

function create_noten_id($conn)
{
    $characters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '_');
    $pos1 = rand(0, 63);
    $pos2 = rand(0, 63);
    $pos3 = rand(0, 63);
    $pos4 = rand(0, 63);
    $pos5 = rand(0, 63);
    $pos6 = rand(0, 63);
    $pos7 = rand(0, 63);
    $pos8 = rand(0, 63);
    $noten_id = $characters[$pos1] . $characters[$pos2] . $characters[$pos3] . $characters[$pos4];
    $noten_id = $noten_id . $characters[$pos5] . $characters[$pos6] . $characters[$pos7] . $characters[$pos8];

    $sql = "select noten_id from noten where noten_id = '$noten_id'";
    $result = mysqli_query($conn, $sql);
    if ($result == 0) {
        create_noten_id($conn);
    } else {
        return $noten_id;
    }
}

// prinzip bleibt gleich, einfach statt insert -> update
if (isset($_POST["speichern"])) {
    // muss nur speichern
    $noten_id = create_noten_id($conn);

    $sql = "insert into noten (user_id, fach, note, datum, gewichtung, titel, noten_id)
values ('$user_id', '$fach', $note, '$datum', $gewicht, '$titel', '$noten_id');";
    $res = mysqli_query($conn, $sql);

    if ($res == 1) {
        echo "<p>Erfolgreich Note gespeichert</p>";
    }
} elseif (isset($_POST["aendern"])) {
    // muss nur updaten
    $noten_id = $_POST["noten_id"];
    $sql = "update noten set fach = '$fach', titel = '$titel', datum = '$datum', gewichtung = $gewicht, note = $note where noten_id = '$noten_id'";
    $res = mysqli_query($conn, $sql);

    if ($res == 1) {
        echo "<p>Erfolgreich Note geändert</p>";
    }
}


mysqli_close($conn);
?>

<form action="uebersicht.php" method="post">
    <button name="aenderung">Zurück zur Mainpage</button>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="username" id="username" value="<?php echo $username; ?>">
    <input type="hidden" name="passwort" id="passwort" value="<?php echo $passwort; ?>">
</form>

<form action="note_erstellen.php" method="post">
    <button type="submit" name="note_erstellen">Noten Erstellen</button>
    <input type="hidden" name="username" id="username" value="<?php echo $username; ?>">
    <input type="hidden" name="passwort" id="passwort" value="<?php echo $passwort; ?>">
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
</form>

</body>
</html>