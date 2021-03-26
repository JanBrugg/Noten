<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Note Erstellen</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
</head>
<body>

<form method="post" action="note_speichern.php">
    <label id="fach">Fach: (Auswählen, oder neues Fach erstellen)</label><br>
    <select name="fach_sel" style="width: 49%">
        <option value="nothing">-- Fach Auswählen --</option>
        <?php
        $user_id = $_POST["user_id"];
        $username = $_POST["username"];
        $passwort = $_POST["passwort"];
        $conn = mysqli_connect(); // mit DB verbinden
        $sql = "select distinct fach from noten where user_id = '$user_id' order by fach ASC ";
        $result = $conn->query($sql);

        while ($row = mysqli_fetch_assoc($result)){
            echo "<option value='" . $row['fach'] . "'>" . $row['fach'] . "</option>";
        }
        ?>
    </select>
    <input type="text" name="fach_neu" id="fach_neu" placeholder="neues Fach" style="width: 49%"><br>
    <label for="titel" id="titel">Prüfungstitel: </label>
    <input type="text" name="titel" id="titel" required placeholder="bsp. Lineare Funktionen"><br>
    <label for="note" id="note">Note: </label>
    <input type="number" name="note" id="note" required placeholder="bsp. 5.5" step=".01"><br>
    <label for="gewicht" id="gewicht">Gewichtung: </label>
    <input type="number" name="gewicht" id="gewicht" placeholder="1" value="1" step=".01"><br> <!-- placeholder macht es so, dass man immer 1 drin steht -->
    <label for="datum" id="datum">Datum: </label>
    <input type="date" name="datum" id="datum" value="<?php
    // Der Heutige Tag wird als Standard alsgesehen, also wird es es ausfüllen
    $tag = date("d");
    $monat = date("m");
    $jahr = date("Y");
    $today = $jahr . "-" . $monat . "-" . $tag;
    echo $today;
    ?>"><br>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
    <input type="hidden" name="username" id="username" value="<?php echo $username;?>">
    <input type="hidden" name="passwort" id="passwort" value="<?php echo $passwort;?>">
    <button type="submit" name="speichern">Speichern</button>
</form>
</body>
</html>