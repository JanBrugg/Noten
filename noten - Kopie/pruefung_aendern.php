<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prüfung Ändern</title>
    <link rel="stylesheet" href="aussehen.css">
    <link rel="icon" type="image/png" href="http://jan.ginf.ch/noten/favicon.png">
</head>
<body>

<?php
$noten_id = $_POST["noten_id"];
$conn = mysqli_connect(); // mit DB verbinde
$username = $_POST["username"];
$user_id = $_POST["user_id"];
$passwort = $_POST["passwort"];

if (isset($_POST["note_aendern"])){
    // Form anzeigen um Daten zu ändern
    $sql_all = "select * from noten where noten_id = '$noten_id'";
    $result_all = $conn->query($sql_all);
    $row_all = mysqli_fetch_assoc($result_all);
    $fach = $row_all["fach"];
    ?>
<form method="post" action="note_speichern.php">
    <label id="fach">Fach: (Auswählen, oder neues Fach erstellen)</label><br>
    <select name="fach_sel" style="width: 49%">
        <option name="<?php echo $fach;?>"><?php echo $fach;?></option>
        <option value="nothing">-- Fach Auswählen --</option>
        <?php
        $sql = "select distinct fach from noten where noten_id = '$noten_id' and fach != '$fach' order by fach ASC ";
        $result = $conn->query($sql);

        while ($row = mysqli_fetch_assoc($result)){
            echo "<option value='" . $row['fach'] . "'>" . $row['fach'] . "</option>";
        }
        ?>
    </select>
    <input type="text" name="fach_neu" id="fach_neu" placeholder="neues Fach" style="width: 49%"><br>
    <label for="titel" id="titel">Prüfungstitel: </label>
    <input type="text" name="titel" id="titel" required value="<?php echo $row_all["titel"];?>"><br>
    <label for="note" id="note">Note: </label>
    <input type="number" name="note" id="note" required value="<?php echo $row_all["note"];?>" step=".01"><br>
    <label for="gewicht" id="gewicht">Gewichtung: </label>
    <input type="number" name="gewicht" id="gewicht" value="<?php echo $row_all["gewichtung"];?>" step=".01"><br> <!-- placeholder macht es so, dass man immer 1 drin steht -->
    <label for="datum" id="datum">Datum: </label>
    <input type="date" name="datum" id="datum" value="<?php echo $row_all["datum"];?>"><br>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
    <input type="hidden" name="username" id="username" value="<?php echo $username;?>">
    <input type="hidden" name="passwort" id="passwort" value="<?php echo $passwort;?>">
    <input type="hidden" name="noten_id" id="noten_id" value="<?php echo $noten_id;?>">
    <button type="submit" name="aendern">Speichern</button>
</form>
<?php
} elseif (isset($_POST["note_loeschen"])){
    // Nur Note löschen und erfolgreich anzeigen
    $sql = "delete from noten where noten_id = '$noten_id'";
    $result = $conn->query($sql);
    if ($result == 1){
        echo "<p> Erfolgreich gelöscht </p>";
        ?>
<form action="uebersicht.php" method="post">
    <button name="aenderung">Zurück zur Mainpage</button>
    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
    <input type="hidden" name="username" id="username" value="<?php echo $username;?>">
    <input type="hidden" name="passwort" id="passwort" value="<?php echo $passwort;?>">
</form>
<?php
    }
}

mysqli_close($conn);
?>

</body>
</html>