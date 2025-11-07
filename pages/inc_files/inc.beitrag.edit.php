<?php
/* ---------- UTF-8 strikt erzwingen (Browser) ---------- */
header('Content-Type: text/html; charset=UTF-8');

/* Optional, falls ein <head>-Bereich existiert */
echo "<meta charset='utf-8'>";

/* Externes Script (unverändert) */
echo "<script src='" . WB_URL . "/include/tablesort/tablesort.js' type='text/javascript'></script>";

/* Form-Aktionen (unverändert) */
$action  = "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
$go_back = "<html><head><meta charset='utf-8'><meta http-equiv='refresh' content='3; URL=" . WB_URL . "/pages/kontakt/beitrag.php'></head>";

/* ---------- DB-Verbindung UTF-8MB4 ---------- */
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_error) {
    die('DB-Fehler: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4'); // wichtig

/* ---------- Helper: Encoding & XSS ---------- */
/* Falls Altlasten latin1 → per Schalter konvertieren (sonst unverändert) */
const DB_IS_LATIN1 = false; // bei echten latin1-Altbeständen auf true setzen (temporäre Rettung) [Low certainty]
function u8($s) {
    if ($s === null) return '';
    if (DB_IS_LATIN1) {
        $s = iconv('ISO-8859-1', 'UTF-8//TRANSLIT', $s);
    }
    return $s;
}

/* Erst Entitäten decodieren (z. B. &uuml; → ü), dann sicher escapen */
function h_out($s) {
    $s = html_entity_decode((string)$s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* Bequemer Kombi-Wrapper: latin1-Rettung (u8) + sichere Ausgabe (h_out) */
function out($s) { return h_out(u8($s)); }

/* -------------------- EDIT -------------------- */
if (isset($_POST['submit_edit'])) {

    if (isset($_POST['submit_auswahl'])) {
        $id  = $_POST['submit_auswahl'];

        $sql = "SELECT
                m.`mitgliederbeitrag_id`,
                m.`kontakt_id`,
                k.`kontakt_vname`,
                k.`kontakt_nname`,
                k.`kontakt_email`,
                k.`kontakt_telefon`,
                m.`jahr`,
                m.`betrag`,
                m.`bezahlt`,
                m.`datum_bezahlt`,
                m.`erinnerung`,
                m.`bemerkung`
                FROM `tbl_mitgliederbeitrag` m
                JOIN `tbl_kontakt` k ON k.`kontakt_id` = m.`kontakt_id`
                WHERE m.`mitgliederbeitrag_id` = '" . $mysqli->real_escape_string($id) . "'";

        /* In manchen WB/WBCE-Setups existiert $database; sonst mysqli verwenden [Low certainty] */
        $query_fields = isset($database) ? $database->query($sql) : $mysqli->query($sql);

        if (!$query_fields) {
            echo "<div class='msg-error'>SQL-Fehler: " . out($mysqli->error) . "</div>";
        } else {
            if (isset($database)) {
                while ($field = $query_fields->fetchRow(MYSQLI_ASSOC)) {
                    $mitgliederbeitrag_id = $field['mitgliederbeitrag_id'];
                    $kontakt_id           = $field['kontakt_id'];
                    $kontakt_vname        = $field['kontakt_vname'];
                    $kontakt_nname        = $field['kontakt_nname'];
                    $kontakt_email        = $field['kontakt_email'];
                    $kontakt_telefon      = $field['kontakt_telefon'];
                    $betrag               = $field['betrag'];
                    $jahr                 = $field['jahr'];
                    $bezahlt              = $field['bezahlt'];
                    $datum_bezahlt        = $field['datum_bezahlt'];
                    $erinnerung           = $field['erinnerung'];
                    $bemerkung            = $field['bemerkung'];
                }
            } else {
                $field = $query_fields->fetch_assoc();
                if ($field) {
                    $mitgliederbeitrag_id = $field['mitgliederbeitrag_id'];
                    $kontakt_id           = $field['kontakt_id'];
                    $kontakt_vname        = $field['kontakt_vname'];
                    $kontakt_nname        = $field['kontakt_nname'];
                    $kontakt_email        = $field['kontakt_email'];
                    $kontakt_telefon      = $field['kontakt_telefon'];
                    $betrag               = $field['betrag'];
                    $jahr                 = $field['jahr'];
                    $bezahlt              = $field['bezahlt'];
                    $datum_bezahlt        = $field['datum_bezahlt'];
                    $erinnerung           = $field['erinnerung'];
                    $bemerkung            = $field['bemerkung'];
                }
            }

            echo $action;

            echo "<div class='table-scrollable'>
                  <table class='sortierbar' id='myTable'>
                    <tr>
                      <th>Mitgliederbeitrag-ID&nbsp;</th>
                      <td>" . out($mitgliederbeitrag_id ?? '') . "</td>
                      <input type='hidden' name='mitgliederbeitrag_id' value='" . out($mitgliederbeitrag_id ?? '') . "'>
                    </tr>
                    <tr>
                      <th>Mitglied-ID</th>
                      <td>" . out($kontakt_id ?? '') . "</td>
                    </tr>
                    <tr>
                      <th>Vornamen</th>
                      <td>" . out($kontakt_vname ?? '') . "</td>
                    </tr>
                    <tr>
                      <th>Nachnamen</th>
                      <td>" . out($kontakt_nname ?? '') . "</td>
                    </tr>
                    <tr>
                      <th>E-Mail</th>
                      <td>" . out($kontakt_email ?? '') . "</td>
                    </tr>
                    <tr>
                      <th>Telefon</th>
                      <td>" . out($kontakt_telefon ?? '') . "</td>
                    </tr>
                    <tr>
                      <th>Beitragsjahr</th>
                      <td>" . out($jahr ?? '') . "</td>
                    </tr>
                    <tr>
                      <th>Mitgliederbeitrag&nbsp;</th>
                      <td><input type='text' name='betrag' value='" . out($betrag ?? '') . "'></td>
                    </tr>";

            echo "<tr><th>bezahlt</th>";
            $selectedValue = $bezahlt ?? '';
            $myArray = array("ja","nein");

            echo "<td>
                    <select class='form_input' name='bezahlt' value='" . out($selectedValue) . "'>
                      <option value=''>Bitte wählen&nbsp;&nbsp;</option>";
            foreach($myArray as $element) {
                $isSelected = ($selectedValue === $element) ? " selected" : "";
                echo "<option value=\"" . out($element) . "\"$isSelected>" . out($element) . "</option>";
            }
            echo "  </select>
                  </td></tr>";

            echo "<tr>
                    <th>Datum bezahlt</th>
                    <td>";
            if (empty($datum_bezahlt)) {
                echo "<input type='date' id='datepicker' name='datum_bezahlt'>";
            } else {
                echo "<input style='width: 150px;' id='arrival' type='date' name='datum_bezahlt' value='" . out($datum_bezahlt) . "'>";
            }
            echo "    </td>
    
            <tr>
                    <th>Erinnerung</th>
                    <td>";
            if (empty($erinnerung)) {
                echo "<input type='date' id='datepicker' name='erinnerung'>";
            } else {
                echo "<input style='width: 150px;' id='arrival' type='date' name='erinnerung' value='" . out($erinnerung) . "'>";
            }
            echo "    </td>
    
    
                  </tr>
                  <tr>
                    <th>Bemerkung</th>
                    <td><textarea rows='9' name='bemerkung'>" . out($bemerkung ?? '') . "</textarea></td>
                  </tr>
                  <tr><td colspan='2'>&nbsp;</td></tr>
                  <tr>
                    <td colspan='2'>
                      <div class='button-group'>
                        <input type='submit' name='submit_update' value='UPDATE' class='myButtonGross'>
                        <input type='button' onClick=\"parent.location='" . WB_URL . "/pages/kontakt/beitrag.php'\" value='zurück zur Übersicht' class='myButtonGross'>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
              </form>
              <p>&nbsp;</p>";
        }

    } else {
        echo "<p>Bitte wählen Sie einen Datensatz mittels Radiobutton aus!</p>";
        echo $go_back;
    }

    /* -------------------- UPDATE -------------------- */
} elseif (isset($_POST['submit_update'])) {

    $mysqli->set_charset('utf8mb4');

    $mitgliederbeitrag_id = (int)$_POST['mitgliederbeitrag_id'];
    $betrag        = $_POST['betrag'] ?? null;
    $bezahlt       = $_POST['bezahlt'] ?? null;
    $datum_bezahlt = $_POST['datum_bezahlt'] ?? null;
    $erinnerung    = $_POST['erinnerung'] ?? null;
    $bemerkung     = $_POST['bemerkung'] ?? null;

    /* ---------- Datum prüfen ---------- */
    if (empty($datum_bezahlt)) {
        $datum = "NULL";
    } else {
        $datum = "'" . $mysqli->real_escape_string($datum_bezahlt) . "'";
    }

    if (empty($erinnerung)) {
        $erinnerung = "NULL";
    } else {
        $erinnerung = "'" . $mysqli->real_escape_string($erinnerung) . "'";
    }

    /* ---------- Query ---------- */
    $sql = "
    UPDATE `tbl_mitgliederbeitrag` SET
      `betrag`        = '" . $mysqli->real_escape_string($betrag) . "',
      `bezahlt`       = '" . $mysqli->real_escape_string($bezahlt) . "',
      `datum_bezahlt` =      $datum,
      `erinnerung`    =      $erinnerung,
      `bemerkung`     = '" . $mysqli->real_escape_string($bemerkung) . "'
    WHERE `mitgliederbeitrag_id` = $mitgliederbeitrag_id
    ";

    $mysqli->query($sql) or die("SQL-Fehler: " . $mysqli->error);

    echo $action;

    echo "
    <div class='table-scrollable'>
      <table id='myTable'>
        <tr>
          <td colspan='2'>
            <div class='msg-ok'>
              Der Datensatz<br><br><b>ID " . out($mitgliederbeitrag_id) . "</b><br><br>wurde erfolgreich angepasst!
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div style='margin-top:6px;'>
              zurück zum Datensatz<br>
              <input type='submit' name='submit_edit' value='" . out($mitgliederbeitrag_id) . "' class='myButtonKlein'>
              <input type='hidden' name='submit_auswahl' value='" . out($mitgliederbeitrag_id) . "'>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='2'>
            <div class='button-group'>
              <input type='button' onClick=\"parent.location='" . WB_URL . "/pages/kontakt/beitrag.php'\" value='zurück zur Übersicht' class='myButtonGross'>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <p>&nbsp;</p>
    </form>";

    /* -------------------- DELETE -------------------- */
} elseif (isset($_POST['submit_delete'])) {

    if (isset($_POST['submit_auswahl'])) {
        $kontakt_id = intval($_POST['submit_auswahl']);
        $sql = "DELETE FROM `tbl_mitgliederbeitrag` WHERE `kontakt_id` = '" . $mysqli->real_escape_string($kontakt_id) . "'";
        $mysqli->query($sql);
        echo $go_back;
        echo "<div class='msg-ok' style='color: red;'>Der Datensatz " . out($kontakt_id) . " wurde erfolgreich gelöscht.</div>";
    } else {
        echo "<p>Bitte wählen Sie einen Datensatz mittels Radiobutton aus!</p>";
        echo $go_back;
    }

    /* -------------------- LISTE & FILTER (mit Volltext-Suche) -------------------- */
} else {

    $jahr_default = date('Y');
    $jahr = isset($_GET['jahr']) && preg_match('/^\d{4}$/', $_GET['jahr']) ? $_GET['jahr'] : $jahr_default;

    $bezahlt = isset($_GET['bezahlt']) ? strtolower(trim($_GET['bezahlt'])) : 'alle';
    $bezahlt_allowed = ['alle','ja','nein'];
    if (!in_array($bezahlt, $bezahlt_allowed, true)) { $bezahlt = 'alle'; }

    /* ---- NEU: Volltext-Suche nach Vor-/Nachname (tokenbasierte LIKE-Suche) ---- */
    $q_raw = isset($_GET['q']) ? trim($_GET['q']) : '';
    // Begrenzen (DoS-Schutz) & Normalisieren
    if (mb_strlen($q_raw, 'UTF-8') > 80) {
        $q_raw = mb_substr($q_raw, 0, 80, 'UTF-8');
    }
    // Tokens (Wörter) extrahieren – mehrere Leerzeichen egal
    $tokens = array_values(array_filter(preg_split('/\s+/u', $q_raw)));

    /* --- WHERE dynamisch bauen --- */
    $where = [];
    $where[] = "m.`jahr` = '" . $mysqli->real_escape_string($jahr) . "'";
    if ($bezahlt !== 'alle') {
        $where[] = "m.`bezahlt` = '" . $mysqli->real_escape_string($bezahlt) . "'";
    }

    /* --- Token-basierte AND-Logik: jedes Suchwort muss in Vor- oder Nachname vorkommen --- */
    if (!empty($tokens)) {
        $tokenClauses = [];
        foreach ($tokens as $t) {
            $esc = $mysqli->real_escape_string($t);
            $like = "%" . $esc . "%";
            $tokenClauses[] =
                "(k.`kontakt_vname` LIKE '" . $like . "' OR " .
                " k.`kontakt_nname` LIKE '" . $like . "' OR " .
                " CONCAT(k.`kontakt_vname`, ' ', k.`kontakt_nname`) LIKE '" . $like . "')";
        }
        $where[] = "(" . implode(" AND ", $tokenClauses) . ")";
    }

    $where_sql = "WHERE " . implode(" AND ", $where);

    /* HINWEIS: Für große Datenmengen kann man zusätzlich FULLTEXT-Indizes nutzen:
       ALTER TABLE tbl_kontakt ADD FULLTEXT ft_name (kontakt_vname, kontakt_nname);
       Dann (optional) statt LIKE:
       MATCH(k.kontakt_vname,k.kontakt_nname) AGAINST ('+wort1 +wort2*' IN BOOLEAN MODE)
       -> nur einsetzen, wenn Index existiert. [Mid certainty]
    */

    $data_sql = "
SELECT
    m.`mitgliederbeitrag_id`,
    m.`kontakt_id`,
    k.`kontakt_vname`,
    k.`kontakt_nname`,
    k.`kontakt_email`,
    m.`jahr`,
    m.`betrag`,
    m.`bezahlt`,
    m.`datum_bezahlt`,
    m.`erinnerung`,
    m.`bemerkung`
FROM `tbl_mitgliederbeitrag` m
JOIN `tbl_kontakt` k ON k.`kontakt_id` = m.`kontakt_id`
{$where_sql}
ORDER BY k.`kontakt_nname`, k.`kontakt_vname`, m.`jahr` DESC
";

    $result = $mysqli->query($data_sql);

    if (!$result) {
        echo "<div class='msg-error'>SQL-Fehler: " . out($mysqli->error) . "</div>";
        exit;
    }
    $anzahl = $result->num_rows;

    echo "<form method='get' action='' class='filter-form'>
            <div class='filter-bar'>
                <label class='myLabel'>Jahr:
                    <input type='number' name='jahr' min='2000' max='2100' value='" . out($jahr) . "' required>
                </label>
                <label class='myLabel'>Bezahlt:
                    <select name='bezahlt'>
                        <option value='alle' " . ($bezahlt==='alle'?'selected':'') . ">alle</option>
                        <option value='ja' "   . ($bezahlt==='ja'  ?'selected':'') . ">ja</option>
                        <option value='nein' " . ($bezahlt==='nein'?'selected':'') . ">nein</option>
                    </select>
                </label>
                <!-- NEU: Volltext-Sucheingabe -->
                <label class='myLabel'>Suche (Name):
                    <input type='text' name='q' value='" . out($q_raw) . "' placeholder='z. B. brühl mann'>
                </label>
                <button type='submit'>Filtern</button>
                <span>Anzahl: " . out((string)$anzahl) . "</span>
            </div>
          </form>";

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
		.filter-form input[type=text], .filter-form select, .filter-form input[type=number] { padding: 5px; margin-right: 10px; }
		.myButtonKlein { padding: 5px 10px; }
		tbody tr:nth-child(odd) { background-color: #f9f9f9; }
		tbody tr:nth-child(even) { background-color: #ffffff; }
	</style>
	</head>
	<body>
    <br>
	<h2>Mitgliederbeiträge</h2>
    <form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
        <div class='table-scrollable'>
            <table class='sortierbar' id='myTableNormal'>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Mitglied-ID</th>
                    <th>Mitglied</th>
                    <th>Jahr</th>
                    <th>Betrag</th>
                    <th>bezahlt</th>
                    <th>Datum</th>
                    <th>Erinnerung</th>
                    <th>Auswahl</th>
                    <th>edit</th>
                    <th>delete</th>
                  </tr>
                </thead>
                <tbody>";

    if ($anzahl === 0) {
        echo "<tr><td colspan='10'><em>Keine Datensätze für die gewählten Filter gefunden.</em></td></tr>";
    } else {
        while ($row = $result->fetch_assoc()) {

            if (empty($row['datum_bezahlt']) || $row['datum_bezahlt'] === '0000-00-00') {
                $termin = '';
            } else {
                /* Annahme: datumswandler_ger liefert UTF-8; sonst out() verwenden [Low certainty] */
                $termin = datumswandler_ger($row['datum_bezahlt']);
            }

            if (empty($row['erinnerung']) || $row['erinnerung'] === '0000-00-00') {
                $erinnerung = '';
            } else {
                /* Annahme: datumswandler_ger liefert UTF-8; sonst out() verwenden [Low certainty] */
                $erinnerung = datumswandler_ger($row['erinnerung']);
            }

            echo "<tr>
                    <td>" . out($row['mitgliederbeitrag_id']) . "</td>
                    <td>" . out($row['kontakt_id']) . "</td>
                    <td>" . out($row['kontakt_vname']) . " " . out($row['kontakt_nname']) . "</td>
                    <td>" . out($row['jahr']) . "</td>
                    <td>" . out(number_format((float)$row['betrag'], 2, '.', '\'')) . "</td>
                    <td>" . out($row['bezahlt']) . "</td>
                    <td>" . out($termin) . "</td>
                     <td>" . out($erinnerung) . "</td>

                    <td>
                      <input type='radio' name='submit_auswahl' value='" . out($row['mitgliederbeitrag_id']) . "'>
                    </td>

                    <td>
                      <input type='submit' name='submit_edit' value='edit' class='myButtonKlein'>
                    </td>

                    <td>
                      <input type='submit' name='submit_delete' value='löschen' class='myButtonKlein'
                        onclick=\"return confirm('Wirklich löschen: Datensatz " . out($row['mitgliederbeitrag_id']) . "?')\">
                    </td>
                  </tr>";
        }
    }

    echo "      </tbody>
              </table>
            </div>
          </form>
          <p>&nbsp;</p>";
}
