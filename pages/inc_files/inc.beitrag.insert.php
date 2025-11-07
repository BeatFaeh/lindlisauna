<?php
// --- Feste Jahrgangs-Spanne ---
$jahr_min = 2025;
$jahr_max = 2035;

// --- Eingabewert lesen & validieren ---
$aktuellesJahr = (int)date("Y"); // nur für Anzeige nützlich, nicht zur Range-Bildung
$jahr = isset($_POST['jahr']) ? (int)$_POST['jahr'] : max($jahr_min, min($aktuellesJahr, $jahr_max));

// Clamp in erlaubte Range, falls manuell manipuliert
if ($jahr < $jahr_min) $jahr = $jahr_min;
if ($jahr > $jahr_max) $jahr = $jahr_max;

// --- DB-Verbindung ---
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    die("Verbindungsfehler: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// --- Insert ausführen, wenn Button gedrückt ---
if (isset($_POST['insert'])) {
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
    if (!$stmt) {
        echo "<p style='color:red;'>Prepare-Fehler: " . htmlspecialchars($mysqli->error) . "</p>";
    } else {
        $stmt->bind_param("ii", $jahr, $jahr);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Neue Mitgliederbeiträge für das Jahr " . (int)$jahr . " erfolgreich eingefügt!</p>";
        } else {
            echo "<p style='color:red;'>Fehler beim Einfügen: " . htmlspecialchars($stmt->error) . "</p>";
        }
        $stmt->close();
    }
}

// --- Anzahl bestehender Beiträge im gewählten Jahr anzeigen ---
$stmt_count = $mysqli->prepare("
    SELECT COUNT(*) AS anzahl
    FROM tbl_mitgliederbeitrag AS m
    INNER JOIN tbl_kontakt AS k ON k.kontakt_id = m.kontakt_id
    WHERE m.jahr = ?
");
if ($stmt_count) {
    $stmt_count->bind_param("i", $jahr);
    $stmt_count->execute();
    $result = $stmt_count->get_result();
    $row = $result->fetch_assoc();
    $anzahl = $row ? (int)$row['anzahl'] : 0;
    $stmt_count->close();
} else {
    $anzahl = 0;
    echo "<p style='color:red;'>Prepare-Fehler (Count): " . htmlspecialchars($mysqli->error) . "</p>";
}

// --- HTML-Ausgabe ---
echo "<form method='POST' action=''>";
echo "<label for='jahr'>Beitragsjahr wählen (".$jahr_min."–".$jahr_max."):</label><br>";
echo "<select name='jahr' id='jahr' onchange='this.form.submit()' style='width:250px;'>";
for ($y = $jahr_min; $y <= $jahr_max; $y++) {
    $selected = ($y === (int)$jahr) ? "selected" : "";
    echo "<option value='".(int)$y."' $selected>".(int)$y."</option>";
}
echo "</select>";

echo "<p>&nbsp;</p>";
echo "<p><button type='submit' name='insert'>Fehlende Mitglieder einfügen</button></p>";
echo "</form>";

echo "<p>Anzahl Mitgliederbeiträge im Jahr ".(int)$jahr.": <strong>".(int)$anzahl."</strong></p>";

// --- Verbindung schließen ---
$mysqli->close();
