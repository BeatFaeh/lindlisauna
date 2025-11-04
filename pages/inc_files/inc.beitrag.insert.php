<?php
// --- Einstellungen ---
$startjahr = 2025;
$aktuellesJahr = date("Y");
$jahr = isset($_POST['jahr']) ? (int)$_POST['jahr'] : $aktuellesJahr;

// --- Datenbankverbindung mit Fehlerprüfung ---
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    die("Verbindungsfehler: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// --- Wenn Formular abgeschickt wurde (Insert ausführen) ---
if (isset($_POST['insert'])) {

    // --- SQL zum Einfügen neuer Mitgliederbeiträge ---
    $sql_insert = "
        INSERT INTO tbl_mitgliederbeitrag (kontakt_id, jahr, betrag, bezahlt, bemerkung)
        SELECT k.kontakt_id, ?, 50, 'nein', NULL
        FROM tbl_kontakt k
        WHERE k.kontakt_grund = 'Antrag Mitgliedschaft'
        AND NOT EXISTS (
            SELECT 1
            FROM tbl_mitgliederbeitrag m
            WHERE m.kontakt_id = k.kontakt_id
            AND m.jahr = ?
        )
    ";

    $stmt = $mysqli->prepare($sql_insert);
    $stmt->bind_param("ii", $jahr, $jahr);
    if ($stmt->execute()) {
        echo "<p style='color:green;'>Neue Mitgliederbeiträge für das Jahr $jahr erfolgreich eingefügt!</p>";
    } else {
        echo "<p style='color:red;'>Fehler beim Einfügen: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

// --- SQL zur Anzeige der Anzahl Mitglieder im gewählten Jahr ---
$stmt_count = $mysqli->prepare("
    SELECT COUNT(*) AS anzahl
    FROM tbl_mitgliederbeitrag AS m
    INNER JOIN tbl_kontakt AS k ON k.kontakt_id = m.kontakt_id
    WHERE m.jahr = ?
");
$stmt_count->bind_param("i", $jahr);
$stmt_count->execute();
$result = $stmt_count->get_result();
$row = $result->fetch_assoc();
$anzahl = $row ? (int)$row['anzahl'] : 0;
$stmt_count->close();

// --- HTML-Ausgabe ---
echo "<form method='POST' action=''>";
echo "<label for='jahr'>Beitragsjahr wählen:</label> ";
echo "<br>";
echo "<select name='jahr' id='jahr' onchange='this.form.submit()' style='width:250px;'";
for ($y = $startjahr; $y <= $aktuellesJahr + 1; $y++) {
    $selected = ($y == $jahr) ? "selected" : "";
    echo "<option value='$y' $selected>$y</option>";
}
echo "</select>";

// Button für das Einfügen neuer Mitgliederbeiträge
echo "<p>&nbsp;</p>";
echo "<p><button type='submit' name='insert'>Fehlende Mitglieder einfügen</button></p>";
echo "</form>";

// Ergebnisanzeige
echo "<p>Anzahl Mitgliederbeiträge im Jahr $jahr: <strong>$anzahl</strong></p>";

// --- Verbindung schließen ---
$mysqli->close();
