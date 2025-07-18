<?php

# Datenbank- und Tabelleneinstellungen

$exp_table = "vw_schueler"; 
	
// Zielverzeichnis und Dateiname
$directory = realpath(__DIR__ . '/../export_csv/') . '/'; // Zurück zum übergeordneten Verzeichnis

$filename = $exp_table.".csv";

$filePath = $directory . $filename;

// Web-Zugriffspfad für den Link
$fileUrl = WB_URL."/pages/export_csv/" . $filename;

// Datenbankverbindung herstellen
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$mysqli) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Verzeichnis erstellen, falls es nicht existiert
if (!file_exists($directory)) {
    mkdir($directory, 0777, true); // Verzeichnis mit Schreibrechten erstellen
}

// Datei im Schreibmodus öffnen
$file = fopen($filePath, 'w');
if (!$file) {
    die("ERROR: Unable to open file for writing: $filePath");
}

// Tabelle abrufen
$mysqli_result = mysqli_query($mysqli, "SELECT * FROM {$exp_table};");
if (!$mysqli_result) {
    die("ERROR: Query failed. " . $mysqli->error);
}

$row_cnt = $mysqli_result->num_rows;

// Spaltennamen abrufen und in CSV schreiben
$column_names = [];
while ($column = mysqli_fetch_field($mysqli_result)) {
    $column_names[] = $column->name;
}
if (!fputcsv($file, $column_names, ";")) {
    die("ERROR: Can't write column names to CSV file.");
}

// Datenzeilen abrufen und in CSV schreiben
while ($row = mysqli_fetch_row($mysqli_result)) {
    if (!fputcsv($file, $row, ";")) {
        die("ERROR: Can't write rows to CSV file.");
    }
}

// Datei schließen
fclose($file);

// HTML-Ausgabe mit Link zum Download der Datei
echo "<div style='text-align: center;'>
<table id='myTable'>
<tr>
<td style='text-align: center;'>
	Anzahl Datensätze = " . $row_cnt . "
	<br>
	<a href=\"$fileUrl\"><img src='".WB_URL."/pages/excel.png'>&nbsp;Download aller Daten in ein Excel File</a>
</td>
</tr>
</table>
</div>";

// Debug-Ausgabe des Dateipfads
/*
echo "Datei gespeichert unter: $filePath<br>";
echo "Datei aufrufbar unter: <a href=\"$fileUrl\">$fileUrl</a>";

*/
