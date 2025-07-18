<?php

// Datenbankverbindung
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

echo "<html><head>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
</head>";

$action = "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
$go_back = "<html><head><meta http-equiv='refresh' content='5; URL=" . WB_URL . "/pages/buchen/edit.php'></head>";

echo "<div align='center'>";

# EDIT
if (isset($_POST['submit_edit'])) {
# Datensatz anzeigen
    if (isset($_POST['submit_auswahl'])) {
        $id = $_POST['submit_auswahl'];
        $sql = "SELECT * FROM `vw_buchung` WHERE `id_buchung` = '$id'";
        $query_fields = $database->query($sql);

        while ($field = $query_fields->fetchRow(MYSQLI_ASSOC)) {
            $id_buchung = $field['id_buchung'];
            $id_angebot = $field['id_angebot'];
            $id_ort = $field['id_ort'];
            $id_events = $field['id_events'];
            $erfassungsdatum = $field['erfassungsdatum'];
            $anrede = $field['anrede'];
            $vname = $field['vname'];
            $nname = $field['nname'];
            $email = $field['email'];
            $strasse = $field['strasse'];
            $plz = $field['plz'];
            $ort = $field['ort'];
            $telefon = $field['telefon'];
            $rechnung_bezahlt = $field['rechnung_bezahlt'];
            $bemerkung = $field['bemerkung'];
            $mitteilung = $field['mitteilung'];
            $datenspeicherung = $field['datenspeicherung'];
            $pdf_rechnung = $field['pdf_rechnung'];
            $ipadresse = $field['ipadresse'];
            $location = $field['location'];
            $start = $field['start'];
            $end = $field['end'];
            $dauer_minuten = $field['dauer_minuten'];
            $pdf_rechnung = $field['pdf_rechnung'];
            $bezeichnung = $field['bezeichnung'];
            $beschreibung = $field['beschreibung'];
            $kosten_ort = $field['kosten_ort'];
            $lageplan = $field['lageplan'];
            $kosten = $field['kosten'];
            $kosten_angebot  = $field['kosten_angebot'];
            $kosten_hausbesuch = $field['kosten_hausbesuch'];
            $kosten_total = $field['kosten_total'];
        }

echo $action;

# Buchung ----------------------------------------------------------------------------------------------------------
#
echo "<table id='myTableBuchenEdit'>

    <tr><td colspan='2' style='background-color: #efedf8;'><b>Buchungsdetails</b></td></tr>
    
    <tr>
        <th>Datensatz-ID</th>
        <td style='text-align: right;'>" . $id_buchung . "</td>
    </tr>			
    
    <tr>
        <th>Erfassungsdatum</th>
        <td style='text-align: right;'>
            " . datumswandler_ger($erfassungsdatum) . "
        </td>
    
    </tr>
    
    <tr>
    <th>Anrede</th>
    <td>
    <select class='form_input' name='anrede'>
    <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

        $selectedValue = $anrede;
        $options = ["Frau", "Herr"];
        foreach ($options as $option) {
            $selected = ($selectedValue === $option) ? " selected" : "";
            echo "<option value=\"{$option}\"{$selected}>{$option}</option>";
        }

        echo "</select>
    </td>
    </tr>
    
    <tr>
    <th>Vornamen</th>
    <td><input type='text' name='vname' value='" . $vname . "'></td>
    </tr>
    
    <tr>
    <th>Nachnamen</th>
    <td><input type='text' name='nname' value='" . $nname . "'></td>
    </tr>
    
    <tr>
    <th>E-Mail</th>
    <td><input type='text' name='email' value='" . $email . "'></td>
    </tr>
    
    <tr>
    <th>Strasse | Hausnummer</th>
    <td><input type='text' name='strasse' value='" . $strasse . "'></td>
    </tr>
    
    <tr>
    <th>PLZ</th>
    <td><input type='text' name='plz' value='" . $plz . "'></td>
    </tr>
    
    <tr>
    <th>Ort</th>
    <td><input type='text' name='ort' value='" . $ort . "'></td>
    </tr>
    
    <tr>
    <th>Telefon</th>
    <td><input type='text' name='telefon' value='" . $telefon . "'></td>
    </tr>
    
    <tr>
    <th>Mitteilung</th>
    <td><textarea rows='9' name='mitteilung'>" . $mitteilung . "</textarea></td>
    </tr>
    
    
    <tr>
    <th>Zusage Datenspeicherung</th>
    <td>" . $datenspeicherung . "</td>
    </tr>
    
    
    <tr>
    <th>IP-Adresse</th>
    <td style='text-align: right;'>" . $ipadresse . "</td>
    </tr>

    <tr>
    <th>Kosten Angebot</th>
    <td style='text-align: right;'>CHF " . number_format($kosten_angebot, 2, ".", "'") . "</td>
    </tr>

    <tr>
    <th>Kosten Hausbesuch</th>
    <td style='text-align: right;'>CHF " . number_format($kosten_hausbesuch, 2, ".", "'") . "</td>    
    </tr>

    <tr>
    <th>Kosten total</th>
    <td style='text-align: right;'><b>CHF " . number_format($kosten_total, 2, ".", "'") . "</b></td>    
    </tr>

    <tr>
    <th>Rechnung</th>
    <td><a href='" . WB_URL . "/media/rechnungen/" . $pdf_rechnung . "' target='_blank'>" . $pdf_rechnung . "</a></td>
    </tr>
    
    <tr>
    <td colspan='2' style='text-align: center;'>
    <input type='submit' name='submit_update_personalien' value='UPDATE' class='myButtonGross'>
    <br>
    <input type='hidden' name='id_buchung' value='" . $id_buchung . "'>
    <input type='hidden' name='id_events' value='" . $id_events . "'>
    <input type='button' 
    onClick=\"parent.location='" . WB_URL . "/pages/buchen/edit.php'\" value='zurück zur Übersicht'  class='myButtonGross'>
    </td>
    </tr>
    </table>

    <p>&nbsp;</p>";

# RECHNUNGSKONTROLLE ---------------------------------------------------------------------------------------------------

    echo "<table id='myTableBuchenEdit'>
    <tr><td colspan='2' style='background-color: #efedf8;'><b>Rechnungskontrolle</b></td></tr>
    <tr>
    <th>Rechnung bezahlt</th>
    <td>
    <select style='width: 200px;' name='rechnung_bezahlt'>
    <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

        $selectedValue = $rechnung_bezahlt;
        $options = ["ja", "nein"];
        foreach ($options as $option) {
            $selected = ($selectedValue === $option) ? " selected" : "";
            echo "<option value=\"{$option}\"{$selected}>{$option}</option>";
        }

        echo "</select>
    </td>
    </tr>
    
    <tr>
        <th>Bemerkung</th>
        <td><textarea rows='9' name='bemerkung'>" . $bemerkung . "</textarea></td>
        </tr>		
        <tr>
        <td colspan='2' style='text-align: center;'>
        <input type='submit' name='submit_update_rechnungskontrolle' value='UPDATE' class='myButtonGross'>
        <br>
        <input type='button' 
        onClick=\"parent.location='" . WB_URL . "/pages/buchen/edit.php'\" value='zurück zur Übersicht'  class='myButtonGross'>
        </td>
    </tr>
  </table>
<p>&nbsp;</p>";

# ANGEBOT --------------------------------------------------------------------------------------------------------------

    echo "<table id='myTableBuchenEdit'>
    <tr><td colspan='2' style='background-color: #efedf8;'><b>Angebot</b></td></tr>";

    $sql = "SELECT * FROM `tbl_angebot` ORDER BY `dauer_minuten`";
    $query_fields = $database->query($sql);

    // Die aktuell gewählte id_angebot (aus POST oder sonst leer)
    $selected_id = $id_angebot;

    echo "<tr>
          <th>Angebot</th>
          <td>
    <select style='width: 200px;' name='id_angebot'>";

        while ($field = $query_fields->fetchRow(MYSQLI_ASSOC)) {
            // Prüfen, ob diese Option die gewählte ist
            $is_selected = ($field['id_angebot'] == $selected_id) ? "selected='selected'" : "";

            echo "<option value='" . $field['id_angebot'] . "' $is_selected>" . $field['bezeichnung'] . "</option>";
        }

        echo "</select>
        <input type='hidden' name='id_angebot_alt' value='".$id_angebot."'>
    </td></tr>
    
    <tr>
    <th>Dauer [min.]</th>
    <td style='text-align: right;'>" . $dauer_minuten . "</td>
    </tr>
    
    <tr>
    <th>Bezeichnung</th>
    <td>" . $bezeichnung . "</td>
    </tr>
    
    <tr>
    <th>Beschreibung</th>
    <td>" . nl2br($beschreibung) . "</td>
    </tr>
    
    <tr>
        <th>Kosten</th>
        <td style='text-align: right;'>CHF " . number_format($kosten, 2, ".", "'") . "</td>
    </tr>";

# RESERVATION ----------------------------------------------------------------------------------------------------------

    echo "<tr><td colspan='2' style='background-color: #efedf8;'><b>Reservation</b></tr></td>";

    $sql = "SELECT * FROM `tbl_zeit` WHERE `aktiv` = 'ja' ORDER BY `zeit`";
    $query_fields = $database->query($sql);

    // Die aktuell gewählte id_angebot (aus POST oder sonst leer)
    $selected_id = date('H:i:s', strtotime($start));

    echo "<tr><th>Zeit</th>
    <td style='text-align: right;'>
    <select style='width: 150px;' name='zeit_von'>";

    while ($field = $query_fields->fetchRow(MYSQLI_ASSOC))
    {
    // Pruefen, ob diese Option die gewählte ist
    $is_selected = ($field['zeit'] == $selected_id) ? "selected='selected'" : "";

    echo "<option value='" . $field['zeit'] . "' $is_selected>" . $field['zeit'] . "</option>";

    }

    echo "</select>
    
    <input type='hidden' name='zeit_von_alt' value='".$selected_id."'>
    </td></tr>
    
    <th>Beginn</th>
    <td style='text-align: right;'>
    <input style='width: 150px;' id='arrival' type='date' name='start' value='" . date('Y-m-d', strtotime($start)) . "'>
    <input type='hidden' name='start_alt' value='".date('Y-m-d', strtotime($start))."'>
    </td>
    </tr>
    
    <tr>
    <th>Ende</th>
    <td style='text-align: right;'>" . datumswandler_ger_zeit($end) . "</td>
    </tr>
    <tr>
        <td colspan='2' style='text-align: right;'>
        <i>Das Ende wird aus dem Angebot und der Startzeit berechnet!</i>
        </td>
    </tr>";

# MASSAGEORT -----------------------------------------------------------------------------------------------------------

    echo "<tr><td colspan='2' style='background-color: #efedf8;'><b>Massageort</b></td></tr>";

        $sql = "SELECT * FROM `tbl_ort` ORDER BY `id_ort`";
        $query_fields = $database->query($sql);

        // Die aktuell gewählte id_angebot (aus POST oder sonst leer)
        $selected_id = $id_ort;

        echo "<tr><th>Massageort</th>
    <td>
    <select style='width: 200px;' name='id_ort'>";

        while ($field = $query_fields->fetchRow(MYSQLI_ASSOC)) {
            // Prüfen, ob diese Option die gewählte ist
            $is_selected = ($field['id_ort'] == $selected_id) ? "selected='selected'" : "";

            echo "<option value='" . $field['id_ort'] . "' $is_selected>" . $field['ort'] . "</option>";

        }

        echo "</select>
        <input type='hidden' name='id_ort_alt' value='".$id_ort."'>
    </td></tr>
    
    <tr>
    <th>Fahrpauschale</th>
    <td style='text-align: right;'>CHF " . number_format($kosten_ort, 2, ".", "'") . "</td>
    </tr>
    
    <tr>
    <th>Lageplan</th>
    <td><a href='" . WB_URL . "/media/lageplan/" . $lageplan . "' target='_blank'>" . $lageplan . "</a></td>
    </tr>

    <tr>
        <td colspan='2' style='text-align: center;'>
        <input type='submit' name='submit_update_buchung' value='UPDATE' class='myButtonGross'>
        <br>
        <input type='button' 
        onClick=\"parent.location='" . WB_URL . "/pages/buchen/edit.php'\" value='zurück zur Übersicht'  class='myButtonGross'>
        </td>
    </tr>
    
    </table>

    <p>&nbsp;</p>

 <!-- ABSCHLUSS -->
   
  </form>
  <p>&nbsp;</p>";

    } else {
        echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!";
        echo $go_back;
    }
}
# UPDATE PERSONALIEN
elseif (isset($_POST['submit_update_personalien'])) {
    include WB_PATH . "/pages/inc_files/inc.buchung.personalien.update.php";
}
# UPDATE BUCHUNG
elseif (isset($_POST['submit_update_buchung'])) {
    include WB_PATH . "/pages/inc_files/inc.buchung.update.php";
}
# UPDATE RECHNUNGSKONTROLLE
elseif (isset($_POST['submit_update_rechnungskontrolle'])) {
    include WB_PATH . "/pages/inc_files/inc.buchung.rechnungskontrolle.update.php";
} # Delete-Funktion
elseif (isset($_POST['submit_delete'])) {
    if (isset($_POST['submit_auswahl'])) {
        include WB_PATH . "/pages/inc_files/inc.buchung.delete.php";
    } else {
        echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!";
        echo $go_back;
    }
} else {
# Filter- & Pagingparameter holen
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $bezahlt_filter = isset($_GET['bezahlt']) ? $_GET['bezahlt'] : '';
    $limit = isset($_GET['limit']) && $_GET['limit'] !== 'all' ? intval($_GET['limit']) : 25;
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $offset = ($limit) ? ($page - 1) * $limit : 0;

# SQL für Zählen + Filter
    $filter_sql = " WHERE 1=1 ";
    if (!empty($search)) {
        $escaped = $mysqli->real_escape_string($search);
        $filter_sql .= " AND (
id_buchung LIKE '%$escaped%' OR
anrede LIKE '%$escaped%' OR
vname LIKE '%$escaped%' OR
nname LIKE '%$escaped%' OR
strasse LIKE '%$escaped%' OR
plz LIKE '%$escaped%' OR
ort LIKE '%$escaped%' OR
telefon LIKE '%$escaped%' OR
start LIKE '%$escaped%' OR
rechnung_bezahlt LIKE '%$escaped%' OR
end LIKE '%$escaped%' OR
email LIKE '%$escaped%')";
    }

    if ($bezahlt_filter === 'ja') {
        $filter_sql .= " AND rechnung_bezahlt = 'ja'";
    } elseif ($bezahlt_filter === 'nein') {
        $filter_sql .= " AND rechnung_bezahlt = 'nein'";
    }

    $count_sql = "SELECT COUNT(*) AS total FROM vw_buchung " . $filter_sql;
    $total_result = $mysqli->query($count_sql);
    $total_row = $total_result->fetch_assoc();
    $total_entries = $total_row['total'];
    $total_pages = ($limit) ? ceil($total_entries / $limit) : 1;

# Hauptabfrage
    $data_sql = "SELECT * FROM vw_buchung" . $filter_sql;
    $data_sql .= " ORDER BY id_buchung DESC";
    if ($limit) $data_sql .= " LIMIT $limit OFFSET $offset";

    $result = $mysqli->query($data_sql);

# HTML-Ausgabe Start

    echo "<!DOCTYPE html>
<html lang='de'>

<head>
<meta charset='UTF-8'>
<title>Erfasste Buchungen</title>
<style>
    body { background: #FFFFFF; }
    table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
    th { background-color: #c0c0c0; color: white; }
    .center { text-align: center; }
    .pagination a { margin: 0 5px; padding: 5px 10px; background: #ddd; color: #333; text-decoration: none; border-radius: 4px; }
    .pagination a.active { font-weight: bold; background: #475c6a; color: white; }
    .filter-form { background: white; padding: 15px; border: 1px solid #ccc; border-radius: 10px; }
    .filter-form input[type=text], .filter-form select { padding: 5px; margin-right: 10px; }
    tbody tr:nth-child(odd) { background-color: #f9f9f9; }
    tbody tr:nth-child(even) { background-color: #ffffff; }
</style>
</head>
<body>

<h2>Erfasste Buchungen</h2>

<form method='GET' class='filter-form'>
<label class='myLabel'>total Records = " . $total_entries . "&nbsp;
</label>
<label class='myLabel'>Volltextsuche:
<input type='text' name='search' value='" . htmlspecialchars($search, ENT_QUOTES) . "'>
</label>
<label class='myLabel'>Rechnung bezahlt:
<select name='bezahlt'>
<option value='' " . ($bezahlt_filter === '' ? "selected" : "") . ">alle</option>
<option value='ja' " . ($bezahlt_filter === 'ja' ? "selected" : "") . ">ja</option>
<option value='nein' " . ($bezahlt_filter === 'nein' ? "selected" : "") . ">nein</option>
</select>
</label>
<label class='myLabel'>Anzahl:
<select name='limit'>
<option value='25' " . ($limit == 25 ? "selected" : "") . ">25</option>
<option value='50' " . ($limit == 50 ? "selected" : "") . ">50</option>
<option value='100' " . ($limit == 100 ? "selected" : "") . ">100</option>
<option value='all' " . (is_null($limit) ? "selected" : "") . ">alle</option>
</select>
</label>
<input type='submit' value='wählen' class='myButtonKlein'>
<button type='button' class='myButtonKlein' onclick=\"window.location.href='" . $_SERVER['PHP_SELF'] . "'\">löschen</button>
<br><br>
<i>bezahlt: Volltextsuche mit ja oder nein!</i>
</form>";

    if ($result->num_rows > 0) {
        echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
<div class='table-scrollable'>

<table class='sortierbar' id='myTableNormal'>
<thead>
<tr>
<th>ID</th>
<th>Eintrag</th>
<th>Kontakt</th>
<th>Massageort</th>
<th>Kosten</th>
<th>Gebucht</th>
<th>Angebot Dauer</th>
<th>Auswahl</th>
<th>edit</th>
</tr>
</thead>
<tbody>";

    while ($row = $result->fetch_assoc())
    {
        if ($row['kosten_ort'] > 1)
        {
        $fahrpauschale = $row['kosten_ort'];
        $totalkosten = $row['kosten_ort'] + $row['kosten'];
    } else {
         $fahrpauschale = 0;
         $totalkosten = $row['kosten'];
    }

    switch ($row['rechnung_bezahlt'])
    {
        case "ja":
            $bezahlt = "bezahlt&nbsp;<img src=' ".WB_URL."/pages/ja.png' width='20px' height='20px'><br><p style='color: #ffffff;'>ja</p>";
            break;
        case "nein":
            $bezahlt = "bezahlt&nbsp;<img src=' ".WB_URL."/pages/nein.png' width='20px' height='20px'><br><p style='color: #ffffff;'>nein</p>";
            break;
    }

    switch ($row['massageort'])
    {
        case "Hausbesuch":
            $massageort = "<i style='font-size:24px' class='fa'>&#xf015;</i> ".$row['massageort'];
            break;
        default:
            $massageort = $row['massageort'];
            break;
    }

    echo "<tr>
    <td style='text-align: right;'>{$row['id_buchung']}</td>
    <td nowrap>" . datumswandler_ger($row['erfassungsdatum']) . "</td>
    <td nowrap>
    {$row['anrede']}<br>
    {$row['vname']} {$row['nname']}<br>
    {$row['strasse']}<br>
    {$row['plz']} {$row['ort']}<br>
    &#9990; {$row['telefon']}<br>
    &#9993; {$row['email']}
    </td>
    
    <td nowrap>".$massageort."</td>
    
    <td style='text-align: right;' nowrap>
        Kosten " . number_format($row['kosten'], 2, ".") . "
        <br>Pauschale " . number_format($fahrpauschale, 2, ".") . "
        <br><b>total " . number_format($totalkosten, 2, ".") . "</b>
        <br>".$bezahlt." 
        <div style='text-align: left;'>
            <a href='" . WB_URL . "/media/rechnungen/" . $row['pdf_rechnung'] . "' target='_blank'>Rechnung</a>
        </div>
    </td>
    
    <td nowrap style='text-align: center;'>
    " . datumswandler_ger_zeit($row['start']) . "
    <br>" . datumswandler_ger_zeit($row['end']) . "
    </br>
    
    <td style='text-align: left;' nowrap>
    " . $row['bezeichnung'] . "
    <br>" . $row['dauer_minuten'] . " [min]
    </td>
    <td style='text-align: center;'>
    <input type='radio' name='submit_auswahl' value='{$row['id_buchung']}'>
    </td>
    <td style='text-align: center;'>
    <input type='submit' name='submit_edit' value='edit' class='myButtonKlein'>
    <br><br>
    <input type='submit' name='submit_delete' value='löschen' class='myButtonKlein'
    onclick=\"return confirm('Wirklich löschen: Datensatz {$row['id_buchung']}?')\">
    </td>
    </tr>";
        }

        echo "</tbody>
</table>
</form>
</div>";
    } else {
        echo "<p>Keine Datensätze gefunden.</p>";
    }

# PAGINATION
    if ($limit) {
        echo "<div class='pagination center'>";
        $base_url = "?limit=$limit&search=" . urlencode($search) . "&bezahlt=" . urlencode($bezahlt_filter);
        if ($page > 1) {
            echo "<a href='{$base_url}&page=" . ($page - 1) . "'>&laquo; Zurück</a>";
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($i == $page) ? "active" : "";
            echo "<a class='$active' href='{$base_url}&page=$i'>$i</a>";
        }
        if ($page < $total_pages) {
            echo "<a href='{$base_url}&page=" . ($page + 1) . "'>Weiter &raquo;</a>";
        }
        echo "</div>";
    }

    echo "<p>&nbsp;</p></body></html>";
}
echo "</div><p>&nbsp;</p>";
