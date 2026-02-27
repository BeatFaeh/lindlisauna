<?php
echo "<script src='" . WB_URL . "/include/tablesort/tablesort.js' type='text/javascript'></script>";

// DB Verbindung
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_error) {
    die("DB-Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

$action  = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$go_back = "<html><head><meta http-equiv='refresh' content='1; URL=" . WB_URL . "/pages/kontakt/edit.php'></head>";

echo "<div align='center'>";

/**
 * PREPARE Helper: liefert mysqli_stmt oder bricht mit Klartext ab
 */
function must_prepare(mysqli $db, string $sql): mysqli_stmt {
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        // wichtig: SQL + Error ausgeben, sonst ist Debugging blind
        die(
            "<pre style='color:#b00;white-space:pre-wrap;'>".
            "SQL PREPARE FEHLGESCHLAGEN:\n".$sql."\n\n".
            "MYSQL ERROR: ".$db->error.
            "</pre>"
        );
    }
    return $stmt;
}

/**
 * LIKE Escape mit ESCAPE '!' (robust, unabhängig von Backslash-Settings)
 * Regel:
 *  - '!' selbst doppeln
 *  - '%' -> '!%'
 *  - '_' -> '!_'
 */
function like_pattern(string $input): string {
    $s = $input;
    $s = str_replace('!', '!!', $s);
    $s = str_replace('%', '!%', $s);
    $s = str_replace('_', '!_', $s);
    return "%".$s."%";
}

/**
 * NULL helper
 */
function null_if_empty($v) {
    if ($v === '' || $v === null) return null;
    return $v;
}

# EDIT
if (isset($_POST['submit_edit'])) {

    if (isset($_POST['submit_auswahl'])) {
        $id = (int)$_POST['submit_auswahl'];

        $stmt = must_prepare($mysqli, "SELECT * FROM `tbl_kontakt` WHERE `kontakt_id` = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $field = $res->fetch_assoc();
        $stmt->close();

        if (!$field) {
            echo "<p>Datensatz nicht gefunden.</p>";
            echo $go_back;
        } else {
            $kontakt_id         = $field['kontakt_id'];
            $kontakt_eintrag    = $field['kontakt_eintrag'];
            $kontakt_anrede     = $field['kontakt_anrede'];
            $kontakt_nname      = $field['kontakt_nname'];
            $kontakt_vname      = $field['kontakt_vname'];
            $kontakt_email      = $field['kontakt_email'];
            $kontakt_adresse    = $field['kontakt_adresse'];
            $kontakt_plz        = $field['kontakt_plz'];
            $kontakt_ort        = $field['kontakt_ort'];
            $kontakt_land       = $field['kontakt_land'];
            $kontakt_telefon    = $field['kontakt_telefon'];
            $kontakt_grund      = $field['kontakt_grund'];
            $kontakt_mitteilung = $field['kontakt_mitteilung'];
            $kontakt_bemerkung  = $field['kontakt_bemerkung'];
            $kontakt_erinnerung = $field['kontakt_erinnerung'];
            $kontakt_termin     = $field['kontakt_termin'];
            $ipadresse          = $field['ipadresse'];
            $rsvs_mitglied      = $field['rsvs_mitglied'];

            echo $action;
            echo "<table class='sortierbar' id='myTable'>
                <tr>
                    <th>Datensatz-ID</th>
                    <td>".$kontakt_id."</td>
                    <input type='hidden' name='kontakt_id' value='".$kontakt_id."'>
                </tr>			

                <tr>
                    <th>Erfassungsdatum</th>
                    <td>".datumswandler_ger($kontakt_eintrag)."</td>
                    <input type='hidden' name='kontakt_eintrag' value='".$kontakt_eintrag."'>
                </tr>

                <tr>
                    <th>Anrede</th>
                    <td>
                        <select class='form_input' name='kontakt_anrede'>
                            <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

            $selectedValue = $kontakt_anrede;
            $options = ["Frau", "Herr"];
            foreach ($options as $option) {
                $selected = ($selectedValue === $option) ? " selected" : "";
                echo "<option value=\"{$option}\"{$selected}>{$option}</option>";
            }

            echo "      </select>
                    </td>
                </tr>

                <tr>
                    <th>Vornamen</th>
                    <td><input type='text' name='kontakt_vname' value='".htmlspecialchars($kontakt_vname ?? '', ENT_QUOTES)."'></td>
                </tr>

                <tr>
                    <th>Nachnamen</th>
                    <td><input type='text' name='kontakt_nname' value='".htmlspecialchars($kontakt_nname ?? '', ENT_QUOTES)."'></td>
                </tr>

                <tr>
                    <th>E-Mail</th>
                    <td><input type='text' name='kontakt_email' value='".htmlspecialchars($kontakt_email ?? '', ENT_QUOTES)."'></td>
                </tr>

                <tr>
                    <th>Strasse | Hausnummer</th>
                    <td><input type='text' name='kontakt_adresse' value='".htmlspecialchars($kontakt_adresse ?? '', ENT_QUOTES)."'></td>
                </tr>

                <tr>
                    <th>PLZ</th>
                    <td><input type='text' name='kontakt_plz' value='".htmlspecialchars($kontakt_plz ?? '', ENT_QUOTES)."'></td>
                </tr>

                <tr>
                    <th>Ort</th>
                    <td><input type='text' name='kontakt_ort' value='".htmlspecialchars($kontakt_ort ?? '', ENT_QUOTES)."'></td>
                </tr>

                <tr><th>Land</th>";

            $selectedValue = $kontakt_land;
            $myArray = ["Schweiz", "Deutschland", "Österreich", "Italien", "Frankreich", "andere"];

            echo "<td>
                    <select class='form_input' name='kontakt_land'>
                        <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

            foreach ($myArray as $element) {
                $isSelected = ($selectedValue == $element) ? " selected" : "";
                echo "<option value=\"".htmlentities($element, ENT_QUOTES)."\"$isSelected>".htmlentities($element)."</option>\n";
            }
            echo "      </select>
                  </td>
                </tr>

                <tr>
                    <th>Telefon</th>
                    <td><input type='text' name='kontakt_telefon' value='".htmlspecialchars($kontakt_telefon ?? '', ENT_QUOTES)."'></td>
                </tr>

                <tr>
                    <th>Kontaktgrund</th>";

            $selectedValue = $kontakt_grund;
            $myArray = [
                "Antrag Mitgliedschaft",
                "allgemeine Frage",
                "Anmeldung Newsletter",
                "Kritik",
                "Mitarbeit",
                "Reservation Sauna",
                "Reservation Massageraum",
                "Reservation Seminarraum",
                "Nachfrage zu Preisen / Angeboten",
                "Sonstiges"
            ];

            echo "<td>
                    <select class='form_input' name='kontakt_grund'>
                        <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

            foreach ($myArray as $element) {
                $isSelected = ($selectedValue == $element) ? " selected" : "";
                echo "<option value=\"".htmlentities($element, ENT_QUOTES)."\"$isSelected>".htmlentities($element)."</option>\n";
            }
            echo "      </select>
                  </td>
                </tr>

                <tr>
                    <th>Mitglied RhySauna Verein</th>";

            $selectedValue = $rsvs_mitglied;
            $myArray = ["ja", "nein"];

            echo "<td>
                    <select class='form_input' name='rsvs_mitglied'>
                        <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

            foreach ($myArray as $element) {
                $isSelected = ($selectedValue == $element) ? " selected" : "";
                echo "<option value=\"".htmlentities($element, ENT_QUOTES)."\"$isSelected>".htmlentities($element)."</option>\n";
            }
            echo "      </select>
                  </td>
                </tr>

                <tr>
                    <th>Mitteilung</th>
                    <td><textarea rows='9' name='kontakt_mitteilung'>".htmlspecialchars($kontakt_mitteilung ?? '', ENT_QUOTES)."</textarea></td>
                </tr>

                <tr><td colspan='2' style='text-align: center;'>Einträge zur Erinnerung</td></tr>

                <tr>
                    <th>Bemerkung</th>
                    <td><textarea rows='9' name='kontakt_bemerkung'>".htmlspecialchars($kontakt_bemerkung ?? '', ENT_QUOTES)."</textarea></td>
                </tr>

                <tr>
                    <th>Erinnerung</th>
                    <td>
                        <select class='form_input' name='kontakt_erinnerung'>
                            <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

            $selectedValue = $kontakt_erinnerung;
            $options = ["ja", "nein"];
            foreach ($options as $option) {
                $selected = ($selectedValue === $option) ? " selected" : "";
                echo "<option value=\"{$option}\"{$selected}>{$option}</option>";
            }

            echo "      </select>
                    </td>
                </tr>

                <tr>
                    <th>Erinnerung Termin</th>
                    <td>";

            if (empty($kontakt_termin) || $kontakt_termin === '0000-00-00') {
                echo "<input style='width: 150px;' id='arrival' type='date' name='kontakt_termin' value=''>";
            } else {
                echo "<input style='width: 150px;' id='arrival' type='date' name='kontakt_termin' value='".htmlspecialchars($kontakt_termin, ENT_QUOTES)."'>";
            }

            echo "      </td>
                </tr>

                <tr>
                    <th>IP-Adresse</th>
                    <td>".htmlspecialchars($ipadresse ?? '', ENT_QUOTES)."</td>
                </tr>

                <tr>
                    <td colspan='2' style='text-align: center;'>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan='2' style='text-align: center;'>
                        <input type='submit' name='submit_update' value='UPDATE' class='myButtonGross'>
                        <br>
                        <input type='button' onClick=\"parent.location='".WB_URL."/pages/kontakt/edit.php'\" value='zurück zur Übersicht'  class='myButtonGross'>
                    </td>
                </tr>
            </table>
            </form>
            <p>&nbsp;</p>";
        }

    } else {
        echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!";
        echo $go_back;
    }
}

# UPDATE
elseif (isset($_POST['submit_update'])) {

    $kontakt_id         = (int)($_POST['kontakt_id'] ?? 0);

    $kontakt_anrede     = $_POST['kontakt_anrede'] ?? '';
    $kontakt_nname      = $_POST['kontakt_nname'] ?? '';
    $kontakt_vname      = $_POST['kontakt_vname'] ?? '';
    $kontakt_email      = $_POST['kontakt_email'] ?? '';
    $kontakt_adresse    = $_POST['kontakt_adresse'] ?? '';
    $kontakt_plz        = $_POST['kontakt_plz'] ?? '';
    $kontakt_ort        = $_POST['kontakt_ort'] ?? '';
    $kontakt_land       = $_POST['kontakt_land'] ?? '';
    $kontakt_telefon    = $_POST['kontakt_telefon'] ?? '';

    $kontakt_grund      = null_if_empty($_POST['kontakt_grund'] ?? null);
    $rsvs_mitglied      = null_if_empty($_POST['rsvs_mitglied'] ?? null);
    $kontakt_mitteilung = null_if_empty($_POST['kontakt_mitteilung'] ?? null);
    $kontakt_bemerkung  = null_if_empty($_POST['kontakt_bemerkung'] ?? null);
    $kontakt_erinnerung = null_if_empty($_POST['kontakt_erinnerung'] ?? null);
    $kontakt_termin     = null_if_empty($_POST['kontakt_termin'] ?? null);

    $sql = "
        UPDATE `tbl_kontakt` SET
          `kontakt_anrede`     = ?,
          `kontakt_nname`      = ?,
          `kontakt_vname`      = ?,
          `kontakt_email`      = ?,
          `kontakt_adresse`    = ?,
          `kontakt_plz`        = ?,
          `kontakt_ort`        = ?,
          `kontakt_land`       = ?,
          `kontakt_telefon`    = ?,
          `kontakt_grund`      = ?,
          `rsvs_mitglied`      = ?,
          `kontakt_mitteilung` = ?,
          `kontakt_bemerkung`  = ?,
          `kontakt_erinnerung` = ?,
          `kontakt_termin`     = ?
        WHERE `kontakt_id` = ?
    ";

    $stmt = must_prepare($mysqli, $sql);

    $stmt->bind_param(
        "sssssssssssssssi",
        $kontakt_anrede,
        $kontakt_nname,
        $kontakt_vname,
        $kontakt_email,
        $kontakt_adresse,
        $kontakt_plz,
        $kontakt_ort,
        $kontakt_land,
        $kontakt_telefon,
        $kontakt_grund,
        $rsvs_mitglied,
        $kontakt_mitteilung,
        $kontakt_bemerkung,
        $kontakt_erinnerung,
        $kontakt_termin,
        $kontakt_id
    );

    if (!$stmt->execute()) {
        die("SQL-Fehler (UPDATE): " . $stmt->error);
    }
    $stmt->close();

    echo $action;

    echo "<div align='center'>
        <table id='myTable'>
            <tr>
                <td colspan='2' style='text-align: center;'>
                    Der Datensatz<br><br><b>ID".$kontakt_id."
                    <br>".htmlspecialchars($kontakt_vname, ENT_QUOTES)." ".htmlspecialchars($kontakt_nname, ENT_QUOTES)."</b>
                    <br><br>wurde erfolgreich angepasst!<br>
                </td>
            </tr>

            <tr>
                <td style='text-align: center;'>zurück zum Datensatz<br> 
                    <input type ='submit' name ='submit_edit' value='".$kontakt_id."' class='myButtonKlein'>
                    <input type ='hidden' name ='submit_auswahl' value='".$kontakt_id."'>
                    <br>
                </td>
            </tr>

            <tr>
                <td colspan='2' style='text-align: center;'>
                    <input type='button' onClick=\"parent.location='".WB_URL."/pages/kontakt/edit.php'\" 
                           value='zurück zur Übersicht' class='myButtonGross'>
                </td>
            </tr>
        </table>
        <p>&nbsp;</p>
    </div>";
}

# DELETE
elseif (isset($_POST['submit_delete'])) {

    if (isset($_POST['submit_auswahl'])) {
        $kontakt_id = (int)$_POST['submit_auswahl'];

        $stmt = must_prepare($mysqli, "DELETE FROM `tbl_kontakt` WHERE `kontakt_id` = ?");
        $stmt->bind_param("i", $kontakt_id);
        $stmt->execute();
        $stmt->close();

        echo $go_back;
        echo "<div style='color: red; font-weight: bold;'>Der Datensatz ".htmlspecialchars((string)$kontakt_id, ENT_QUOTES)." wurde erfolgreich gelöscht.</div>";
    } else {
        echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!";
        echo $go_back;
    }
}

# LISTE + SUCHE + FILTER + PAGING
else {

    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $limitParam = $_GET['limit'] ?? '25';
    $limit = ($limitParam !== 'all') ? max(1, (int)$limitParam) : null;

    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($limit) ? ($page - 1) * $limit : 0;

    $grund = isset($_GET['kontakt_grund']) ? trim($_GET['kontakt_grund']) : 'Alle';

    // Dropdown-Optionen dynamisch
    $grund_options = [];
    $opt_sql = "SELECT DISTINCT kontakt_grund
                FROM tbl_kontakt
                WHERE kontakt_grund IS NOT NULL AND kontakt_grund <> ''
                ORDER BY kontakt_grund";
    if ($opt_res = $mysqli->query($opt_sql)) {
        while ($opt_row = $opt_res->fetch_assoc()) {
            $grund_options[] = $opt_row['kontakt_grund'];
        }
        $opt_res->free();
    }

    // WHERE dynamisch + Parameter sammeln
    $where = [];
    $types = "";
    $params = [];

    if ($search !== '') {
        $like = like_pattern($search);

        // 13 LIKEs, alle mit ESCAPE '!'
        $where[] = "(
            CAST(kontakt_id AS CHAR) LIKE ? ESCAPE '!' OR
            kontakt_anrede LIKE ? ESCAPE '!' OR
            kontakt_vname LIKE ? ESCAPE '!' OR
            kontakt_nname LIKE ? ESCAPE '!' OR
            kontakt_adresse LIKE ? ESCAPE '!' OR
            kontakt_plz LIKE ? ESCAPE '!' OR
            kontakt_ort LIKE ? ESCAPE '!' OR
            kontakt_land LIKE ? ESCAPE '!' OR
            kontakt_telefon LIKE ? ESCAPE '!' OR
            kontakt_email LIKE ? ESCAPE '!' OR
            rsvs_mitglied LIKE ? ESCAPE '!' OR
            kontakt_bemerkung LIKE ? ESCAPE '!' OR
            kontakt_mitteilung LIKE ? ESCAPE '!'
        )";

        $types .= str_repeat("s", 13);
        for ($i = 0; $i < 13; $i++) $params[] = $like;
    }

    if ($grund !== '' && strcasecmp($grund, 'Alle') !== 0) {
        $where[] = "kontakt_grund = ?";
        $types .= "s";
        $params[] = $grund;
    }

    $where_sql = $where ? (" WHERE " . implode(" AND ", $where)) : "";

    // COUNT
    $count_sql = "SELECT COUNT(*) AS total FROM tbl_kontakt" . $where_sql;
    $stmt = must_prepare($mysqli, $count_sql);

    if ($types !== "") {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $total_result = $stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_entries = (int)($total_row['total'] ?? 0);
    $stmt->close();

    $total_pages = ($limit) ? (int)ceil($total_entries / $limit) : 1;

    // DATA
    $data_sql = "SELECT * FROM tbl_kontakt" . $where_sql . " ORDER BY kontakt_id DESC";
    if ($limit) {
        $data_sql .= " LIMIT ? OFFSET ?";
    }

    $stmt = must_prepare($mysqli, $data_sql);

    if ($limit) {
        $types2 = $types . "ii";
        $params2 = $params;
        $params2[] = $limit;
        $params2[] = $offset;

        $stmt->bind_param($types2, ...$params2);
    } else {
        if ($types !== "") $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $shown  = $result ? $result->num_rows : 0;
    $from   = ($total_entries > 0) ? ( $limit ? ($offset + 1) : 1 ) : 0;
    $to     = ($limit ? ($offset + $shown) : $shown);

    // HTML
    echo "<!DOCTYPE html>
    <html lang='de'>
    <head>
        <meta charset='UTF-8'>
        <title>Kontaktliste</title>
        <style>
            body { font-family: Arial, sans-serif; background: #FFFFFF; }
            table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
            th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
            th { background-color: #c0c0c0; color: white; }
            .center { text-align: center; }
            .pagination a { margin: 0 5px; padding: 5px 10px; background: #ddd; color: #333; text-decoration: none; border-radius: 4px; }
            .pagination a.active { font-weight: bold; background: #475c6a; color: white; }
            .filter-form { background: white; padding: 15px; border: 1px solid #ccc; border-radius: 10px; }
            .filter-form input[type=text], .filter-form select { padding: 5px; margin-right: 10px; }
            .myButtonKlein { padding: 5px 10px; }
            tbody tr:nth-child(odd) { background-color: #f9f9f9; }
            tbody tr:nth-child(even) { background-color: #ffffff; }
        </style>
    </head>
    <body>

    <h2>Kontaktliste</h2>

    <form method='GET' class='filter-form'>
        <label class='myLabel'>Volltextsuche:
            <input type='text' name='search' value='" . htmlspecialchars($search, ENT_QUOTES) . "'>
        </label>
        <label class='myLabel'>Anzahl:
            <select name='limit'>
                <option value='25' " . ($limit === 25 ? "selected" : "") . ">25</option>
                <option value='50' " . ($limit === 50 ? "selected" : "") . ">50</option>
                <option value='100' " . ($limit === 100 ? "selected" : "") . ">100</option>
                <option value='all' " . (is_null($limit) ? "selected" : "") . ">Alle</option>
            </select>
        </label>

        <label class='myLabel'>Kontaktgrund:
            <select name='kontakt_grund' onchange='this.form.submit()'>
                <option value='Alle'".(strcasecmp($grund,'Alle')===0 ? " selected" : "").">Alle</option>";

    foreach ($grund_options as $opt) {
        $sel = ($grund === $opt) ? " selected" : "";
        echo "<option value='".htmlspecialchars($opt, ENT_QUOTES)."'$sel>".htmlspecialchars($opt)."</option>";
    }

    echo "  </select>
        </label>

        <input type='submit' value='Anzeige' class='myButtonKlein'>
    </form>";

    echo "<div style='margin:5px 0;color:#555;'>
            Angezeigt: <strong>$shown</strong>"
        . ($limit ? " (Datensätze $from–$to von <strong>$total_entries</strong>)" : " von <strong>$total_entries</strong> gesamt")
        . "</div>";

    if ($result && $result->num_rows > 0) {
        echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>
            <div class='table-scrollable'>
            <table class='sortierbar' id='myTableNormal'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Typ</th>
                        <th>Kontakt</th>
                        <th>Erinnerung</th>
                        <th>Termin</th>
                        <th>Auswahl</th>
                        <th>edit</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>";

        while ($row = $result->fetch_assoc()) {

            if (empty($row['kontakt_termin']) || $row['kontakt_termin'] === '0000-00-00') {
                $termin = '';
            } else {
                $termin = datumswandler_ger($row['kontakt_termin']);
            }

            $erinnerung = !empty($row['kontakt_erinnerung']) ? $row['kontakt_erinnerung'] : '';

            echo "<tr>
                <td>".htmlspecialchars($row['kontakt_id'], ENT_QUOTES)."</td>
                <td>".htmlspecialchars($row['kontakt_grund'] ?? '', ENT_QUOTES)."</td>
                <td>
                    ".htmlspecialchars($row['kontakt_anrede'] ?? '', ENT_QUOTES)."<br>
                    ".htmlspecialchars($row['kontakt_vname'] ?? '', ENT_QUOTES)." ".htmlspecialchars($row['kontakt_nname'] ?? '', ENT_QUOTES)."<br>
                    ".htmlspecialchars($row['kontakt_adresse'] ?? '', ENT_QUOTES)."<br>
                    ".htmlspecialchars($row['kontakt_plz'] ?? '', ENT_QUOTES)." ".htmlspecialchars($row['kontakt_ort'] ?? '', ENT_QUOTES)."<br>
                    ".htmlspecialchars($row['kontakt_land'] ?? '', ENT_QUOTES)."<br>
                    ".htmlspecialchars($row['kontakt_telefon'] ?? '', ENT_QUOTES)."<br>
                    ".htmlspecialchars($row['kontakt_email'] ?? '', ENT_QUOTES)."
                </td>

                <td>".htmlspecialchars($erinnerung, ENT_QUOTES)."</td>
                <td>".htmlspecialchars($termin, ENT_QUOTES)."</td>

                <td style='text-align: center;'>
                    <input type='radio' name='submit_auswahl' value='".htmlspecialchars($row['kontakt_id'], ENT_QUOTES)."'>
                </td>

                <td style='text-align: center;'>
                    <input type='submit' name='submit_edit' value='edit' class='myButtonKlein'>
                </td> 

                <td class='center'>
                    <input type='submit' name='submit_delete' value='löschen' class='myButtonKlein'
                    onclick=\"return confirm('Wirklich löschen: Datensatz ".htmlspecialchars($row['kontakt_id'], ENT_QUOTES)."?')\">
                </td>
            </tr>";
        }

        echo "</tbody>
            </table>
            </div>
            </form>";
    } else {
        echo "<p>Keine Datensätze gefunden.</p>";
    }

    // PAGINATION
    if ($limit) {
        echo "<div class='pagination center'>";

        $qsBase = "&limit=" . urlencode((string)$limitParam)
            . "&search=" . urlencode($search)
            . "&kontakt_grund=" . urlencode($grund);

        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . $qsBase . "'>&laquo; Zurück</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? "active" : "";
            echo "<a class='$active' href='?page=$i$qsBase'>$i</a>";
        }

        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . $qsBase . "'>Weiter &raquo;</a>";
        }

        echo "</div>";
    }

    echo "<p>&nbsp;</p></body></html>";

    $stmt->close();
}

echo "</div>";
?>