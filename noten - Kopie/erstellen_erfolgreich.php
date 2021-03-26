<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Account erfolgreich erstellt - Jan D. Login Projekt</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
</head>
<body>



<?php

$username = $_POST["username"];
$vorname = $_POST["vorname"];
$nachname = $_POST["nachname"];
$email = $_POST["email"];
$passwort = $_POST["passwort"];


$conn = mysqli_connect();

// Schaut ob es einen Eintrag mit dem Username schon gibt
$sql = "select username from accounts where username = '$username'";
$result = $conn->query($sql);

// Wenn kein Eintrag existiert, schaue ob es einen Eintrag mit der Email gibt
if ($result->num_rows == 0) {
    $sql = "select username from accounts where email = '$email'";
    $result = $conn->query($sql);
    // Wenn kein Eintrag existiert, kann man sich den Account erstellen
    // Nur Email und Username sollen unique sein, der Rest ist ok

    // Passwort hashen
    $hash_passwort = password_hash($passwort, PASSWORD_DEFAULT);

    // Zufällige User_id erstellen
    // Funktion falls zwei mal passiert
    function create_user_id($conn)
    {
        // Liste mit 64 Zeichen -> base64
        $characters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '_');
        $rand_num1 = rand(0, 63);
        $rand_num2 = rand(0, 63);
        $rand_num3 = rand(0, 63);
        $rand_num4 = rand(0, 63); // das sind 16'777'216 user_ids (ein Zeichen mehr wäre über eine Milliarde)
        $user_id = $characters[$rand_num1] . $characters[$rand_num2];
        $user_id = $user_id . $characters[$rand_num3] . $characters[$rand_num4];

        // überprüfen ob es die User_id schon gibt

        $sql = "select user_id from accounts where user_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if ($result == 0) { // also es gibt bereits mit der gleichen user_id
            // nochmals machen - falls nein, wird nicht nochmals aufgeruft
            create_user_id($conn);
        } else {
            return $user_id;
        }
    }

    $user_id = create_user_id($conn);

    if ($result->num_rows == 0) { // Daten in die DB speichern
        $sql = "insert into accounts 
        values ('$username', '$vorname', '$nachname', '$email', '$hash_passwort', '$user_id')";
    } else {
        $nachricht = "Email bereits vergeben"; // Email bereits vergeben
        header("https://jan.ginf.ch/noten/erstellen.php?nachricht=" . $nachricht);
        exit();
    }
} else {
    $nachricht = "Username bereits vergeben"; // Username bereits vergeben
    header("https://jan.ginf.ch/noten/erstellen.php?nachricht=" . $nachricht);
    exit();
}


$res = mysqli_query($conn, $sql); // erfolgreiches Account-Erstellen
if ($res == 1) {
    echo "<p>";
    echo "Dein Account wurde erfolgreich erstellt!";
    echo "</p>";
}
mysqli_close($conn);
?>

<form></form>

</body>
</html>