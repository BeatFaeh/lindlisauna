<?php

require_once __DIR__ . '/inc.export.helpers.php';

// ========= Einstellungen =========
$exp_table = "tbl_kontakt";

// Verzeichnis bestimmen (ohne realpath, damit mkdir sicher klappt)
$directory = dirname(__DIR__) . '/export_csv/';
$filename  = $exp_table . ".csv";
$filePath  = $directory . $filename;
$fileUrl   = WB_URL . "/pages/export_csv/" . $filename;

// ========= DB-Verbindung =========
mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = @new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    die("ERROR: Could not connect. (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}
if (!$mysqli->set_charset('utf8mb4')) {
    $mysqli->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
}

// ========= Verzeichnis anlegen =========
if (!is_dir($directory)) {
    if (!mkdir($directory, 0775, true) && !is_dir($directory)) {
        die("ERROR: Could not create export directory: " . htmlspecialchars($directory));
    }
}

// ========= Datei öffnen =========
$file = fopen($filePath, 'wb'); // binary write
if (!$file) {
    die("ERROR: Unable to open file for writing: $filePath");
}
// UTF-8 BOM für Excel
fwrite($file, "\xEF\xBB\xBF");

// ========= Helper: Deep-Decode von HTML-Entities =========
/**
 * Decodiert HTML-Entities mehrfach, falls z. B. &amp;ouml; vorliegt (zweifach encodiert).
 * Wandelt <br> in Zeilenumbrüche und entfernt HTML-Tags.
 */
if (!function_exists('normalize_csv_cell')) {
    function normalize_csv_cell($value) {
        if ($value === null) {
            return '';
        }

        if (!is_string($value)) {
            return $value;
        }

        $value = preg_replace('/<\s*br\s*\/?>/i', "\n", $value);

        $prev = null;
        $i = 0;
        while ($prev !== $value && $i < 3) {
            $prev  = $value;
            $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $i++;
        }

        $value = strip_tags($value);
        $value = trim($value);
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        $value = str_replace('[at]', '@', $value);

        return $value;
    }
}

// ========= Daten abfragen =========
$sql = "SELECT * FROM {$exp_table}";
$mysqli_result = $mysqli->query($sql);
if (!$mysqli_result) {
    fclose($file);
    die("ERROR: Query failed. " . $mysqli->error);
}
$row_cnt = $mysqli_result->num_rows;

// ========= Spaltennamen schreiben =========
$column_names = [];
while ($field = $mysqli_result->fetch_field()) {
    $column_names[] = $field->name;
}
if (fputcsv($file, $column_names, ";") === false) {
    fclose($file);
    die("ERROR: Can't write column names to CSV file.");
}

// ========= Datenzeilen schreiben =========
while ($row = $mysqli_result->fetch_row()) {
    foreach ($row as $k => $val) {
        $row[$k] = normalize_csv_cell($val);
    }
    if (fputcsv($file, $row, ";") === false) {
        fclose($file);
        die("ERROR: Can't write rows to CSV file.");
    }
}

// ========= Aufräumen =========
fclose($file);
$mysqli_result->free();
$mysqli->close();

// ========= HTML-Ausgabe =========
echo "<div align='center'>
<table id='myTable'>
<tr>
<td style='text-align: center;'>
    Anzahl Datensätze = " . (int)$row_cnt . "
    <br>
    <a href=\"" . htmlspecialchars($fileUrl, ENT_QUOTES, 'UTF-8') . "\">
        <img src='" . WB_URL . "/pages/excel.png' alt='Excel'>&nbsp;Download aller Daten in ein Excel-File
    </a>
</td>
</tr>
</table>
</div>";
