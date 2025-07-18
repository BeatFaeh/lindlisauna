<?php

require(WB_PATH.'/include/tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('dejavusans', 'I', 8);
        $this->Cell(0, 10, 'Seite '.$this->getAliasNumPage().' von '.$this->getAliasNbPages(), 0, false, 'C');
    }
}

# Datenbankverbindung
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$action               = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$landing_page = "pages/TERMINE-INTERN/terminuebersicht.php";
$go_back   = "<html><head><title></title>
		<meta http-equiv='refresh' content='1; URL=".WB_URL."/".$landing_page."'></head>";

include WB_PATH . "/pages/inc_files/inc.tabellen.format.php";

echo $action;

echo "<div align='center'>";

$filter = $_POST['filter'];

if (isset($_POST['filter']))
{
    if ($_POST['filter'] <> "Bitte eine Auswahl treffen")
    {

        $jahr_monat_selektiert = $_POST['filter'];

        $sql = "SELECT * FROM `vw_buchung` WHERE jahr_monat = '" . $mysqli->real_escape_string($jahr_monat_selektiert) . "' ORDER BY start;";
        $result = $mysqli->query($sql);

        echo "Filter = " . htmlspecialchars($jahr_monat_selektiert);

        echo "<br><br><i>Für Sortierung bitte in die betreffende Kopfzeile klicken</i>";

        $html = '<table class="sortierbar">
        <tr>
            <td '.$buchungsdetails_rubrik_grid.'><b>ID</b></td>
            <td '.$buchungsdetails_rubrik_grid.'><b>Gebucht</b></td>
            <td '.$buchungsdetails_rubrik_grid.'><b>Adresse</b></td>
            <td '.$buchungsdetails_rubrik_grid.'><b>Massageort</b></td>
            <td '.$buchungsdetails_rubrik_grid.'><b>Kosten</b></td>
            <td '.$buchungsdetails_rubrik_grid.'><b>bezahlt</b></td>
            <td '.$buchungsdetails_rubrik_grid.'><b>Termin</b></td>
            <td '.$buchungsdetails_rubrik_grid.'><b>Angebot</b></td>
        </tr>';

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

            $html .= '<tr>
        <td '.$buchungsdetails_grid.' valign="top">' . $row['id_buchung'] . '</td>
        <td '.$buchungsdetails_grid.' valign="top">' . datumswandler_ger($row['erfassungsdatum']) . '</td>
        <td '.$buchungsdetails_grid.' valign="top">
            ' . $row['anrede'] . ' ' . $row['vname'] . ' ' . $row['nname'] . '<br>
            ' . $row['strasse'] . '<br>
            ' . $row['plz'] . ' ' . $row['ort'] . '<br>
            ' . $row['telefon'] . '<br>
            ' . $row['email'] . '
        </td>
        <td '.$buchungsdetails_grid.' valign="top">' . $row['massageort'] . '</td>
        <td '.$buchungsdetails_grid.'  valign="top">
            Kosten ' . number_format($row['kosten'], 2, '.', '') . '<br>
            Pauschale ' . number_format($fahrpauschale, 2, '.', '') . '<br>
            <b>total ' . number_format($totalkosten, 2, '.', '') . '</b>
        </td>
        <td '.$buchungsdetails_grid.' valign="top">' . $row['rechnung_bezahlt'] . '</td>
        <td '.$buchungsdetails_grid.' valign="top">
            ' . datumswandler_ger_zeit($row['start']) . '<br>
            ' . datumswandler_ger_zeit($row['end']) . '
        </td>
        <td '.$buchungsdetails_grid.' valign="top">
            ' . $row['bezeichnung'] . '<br>
            ' . $row['dauer_minuten'] . ' [min]
        </td>
    </tr>';
        }

        $html .= '</table>';

        echo '<p>&nbsp;</p>';

        echo $html;

# Erstellung des PDF Dokuments
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

# Dokumenteninformationen
        $dokument   = "terminplan.pdf";
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Ruggero Zehnder');
        $pdf->SetTitle('Terminplan');
        $pdf->SetSubject('Terminplan');

# Auswahl des Font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

# Auswahl der Margins
# Automatisches Autobreak der Seiten
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

# Image Scale
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

# Schriftart
        $pdf->SetFont('dejavusans', '', 10);


        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(true);

        $pdf->setFooterFont(['dejavusans', '', 8]);
        $pdf->SetFooterMargin(10);

# Neue Seite
        $pdf->AddPage('L', 'A4');

# Fügt den HTML Code in das PDF Dokument ein
        $pdf->writeHTML($html, true, false, true, false, '');

# Ausgabe der PDF
        $pfad = WB_PATH."/pages/export_csv/";
        $pdf->Output($pfad.$dokument, 'F');

# PDF ist fertig erstellt
        echo "<p>&nbsp;</p>
        <div align='center'>
        <table>
        <tr><td style='text-align: center;'>
		<a href=".WB_URL."/pages/export_csv/".$dokument." target='_blank' >
		<img src='".WB_URL."/pages/pdficon.png' border='0'>
		<br>PDF Terminplan</a></td></tr>
        </table></div>";
    }
    else
    {

        echo 'Bitte wählen Sie einen Monat aus!';
        echo $go_back;

    }
}
else
{
    # das Jahr-Monat ausgeben
    $sql = "SELECT jahr_monat FROM `vw_buchung` GROUP BY jahr_monat;";
    $query_fields = $database->query($sql);

    # Ausgabe der Combobox mit den ermittelten Datumsangaben
    echo $action;

    echo "Bitte wählen Sie einen Monat aus<br>
         <table>
		 <tr><td>
		 <select name='filter' class='input_select'>
         <option>Bitte eine Auswahl treffen</option>";

    while ($field = $query_fields->fetchRow(MYSQLI_ASSOC)) {
        if ($field['jahr_monat'] == $_POST['filter']) {
            $auswahl = " selected";
        } else {
            $auswahl = " ";
        }
        echo "<option value='" . $field['jahr_monat'] . "' " . $auswahl . ">" . $field['jahr_monat'] . "</option>\n";
    }
    echo "</select>
		<tr><td>&nbsp;<td></tr>	
          </td></tr>
          <tr><td style='text-align: center;'><input type='submit' name='submit_filter' value='Anzeigen'class='myButtonKlein'></td></tr>
          </table>";
}
echo "<p>&nbsp;</p>";