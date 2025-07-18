<?php

require(WB_PATH.'/include/tcpdf/tcpdf.php');

# DB-Verbindung
// $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

# PDF-Klasse erweitern
class MYPDF extends TCPDF {
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('dejavusans', 'I', 8);
        $this->Cell(0, 10, 'Seite '.$this->getAliasNumPage().' von '.$this->getAliasNbPages(), 0, false, 'C');
    }
}

# Variablen
$logo = '<img src="'.WB_URL.'/pages/logo_schwarz_100px.jpg">';
$qrc = '<img src="'.WB_URL.'/pages/qrc.jpg" width="100%">';
$qrc_path = WB_PATH.'/media/qrc/qrc.jpg';

$adresse = 'Ruggero Zehnder<br>Fusszonenreflexmassage<br>Akazienweg 24<br>8500 Frauenfeld';

include WB_PATH . "/pages/inc_files/inc.tabellen.format.php";

# SQL ausführen
$sql_vw_buchung = "SELECT * FROM vw_buchung ORDER BY id_buchung DESC LIMIT 1;";
$result = $mysqli->query($sql_vw_buchung);

if (!$result || $result->num_rows == 0) 
{
    die("Keine Buchungen gefunden.");
}

# Nur eine Buchung ausgeben
$field = $result->fetch_assoc(); // nur die erste Zeile verwenden

# Daten extrahieren
extract($field);

# Funktion prüfen
if (!function_exists('datumswandler_ger')) 
{
    function datumswandler_ger($datum) 
	{
        return date("d.m.Y", strtotime($datum));
    }
}
if (!function_exists('datumswandler_ger_zeit'))
{
    function datumswandler_ger_zeit($datum)
	{
        return date("d.m.Y H:i", strtotime($datum));
    }
}

# Kosten berechnen, wenn Hausbesuch selektiert wurde

if($id_ort == 1)
{	
	$kosten_total = $kosten + $kosten_hausbesuch;
}
else
{
	$kosten_hausbesuch = 0;
	$kosten_total = $kosten;
}	

# HTML-Body Ausgabe PDF
$bestaetigung_ausgabe_pdf = '
<table>
<tr>
    <td style="text-align: left;vertical-align: text-top;">'.$adresse.'</td>
    <td style="text-align: right;vertical-align: text-top;">'.$logo.'</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td  style="text-align: left;vertical-align: text-top;"><b>'.$anrede.' '.$vname.' '.$nname.'
    <br>'.$strasse.'
    <br>
    <br>'.$plz.' '.$ort.'</b>
    </td>
    <td style="text-align: right;vertical-align: text-top;">Erfassungsdatum '.datumswandler_ger($erfassungsdatum).'</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2" ><b>Fusszonenreflexmassage Buchungsnummer '.$id_buchung.'</b></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr>
    <td colspan="2">Grüezi '.$anrede.' '.$vname.' '.$nname.'<br><br>Herzlichsten Dank für Ihre Buchung!<br><br>Nachfolgend finden Sie die Details dazu.</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2">
<table>
<tr>
    <td '.$buchungsdetails_rubrik.'>Buchungsnummer</td>
    <td '.$buchungsdetails_zahl_oben.'>&nbsp;'.$id_buchung.'&nbsp;&nbsp;</td>
</tr>
<tr>
    <td '.$buchungsdetails_rubrik.'>Erfassungsdatum</td>
    <td '.$buchungsdetails_zahl.'>&nbsp;'.datumswandler_ger($erfassungsdatum).'&nbsp;&nbsp;</td>
</tr>
<tr>
    <td '.$buchungsdetails_rubrik.'>Buchung erfolgt von</td>
    <td '.$buchungsdetails.'>&nbsp;'.$anrede.' '.$vname.' '.$nname.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Strasse</td>
    <td '.$buchungsdetails.'>&nbsp;'.$strasse.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>PLZ | Ort</td>
    <td '.$buchungsdetails.'>&nbsp;'.$plz.' '.$ort.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>E-Mail</td>
    <td '.$buchungsdetails.'>&nbsp;'.$email.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Telefon</td>
    <td '.$buchungsdetails.'>&nbsp;'.$telefon.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Einverständnis zur Datenspeicherung</td>
    <td '.$buchungsdetails.'>&nbsp;'.$datenspeicherung.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Buchung Start</td>
    <td '.$buchungsdetails_zahl.'>'.datumswandler_ger_zeit($start).'&nbsp;&nbsp;</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Buchung Ende</td>
    <td '.$buchungsdetails_zahl.'>'.datumswandler_ger_zeit($end).'&nbsp;&nbsp;</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Dauer</td>
    <td '.$buchungsdetails_zahl.'>'.$dauer_minuten.' Minuten&nbsp;&nbsp;</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Bezeichnung</td>
    <td '.$buchungsdetails.'>&nbsp;'.$bezeichnung.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Beschreibung</td>
    <td '.$buchungsdetails.'>&nbsp;'.nl2br($beschreibung).'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Massageort</td>
    <td '.$buchungsdetails.'>&nbsp;'.$massageort.'</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Pauschale bei Hausbesuch</td>
    <td '.$buchungsdetails_zahl.'>CHF '.number_format($kosten_hausbesuch, 2, ".", "'").'&nbsp;&nbsp;</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik.'>Kosten</td>
    <td '.$buchungsdetails_zahl.'>CHF '.number_format($kosten, 2, ".", "'").'&nbsp;&nbsp;</td>
</tr>

<tr>
    <td '.$buchungsdetails_rubrik_abschluss.'>Kosten total</td>
    <td '.$buchungsdetails_zahl.'><b>CHF '.number_format($kosten_total, 2, ".", "'").'</b>&nbsp;&nbsp;</td>
</tr>

</table>
</td></tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr>
    <td colspan="2" '.$schrift_body.'>Bitte überweisen Sie den offenen Betrag von <b>CHF '.number_format($kosten_total, 2, ".", "'").'</b> mit dem QRC-Einzahlungsschein, siehe unten.</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2" '.$schrift_body.'>Mit freundlichen Grüssen<br>Ruggero Zehnder</td></tr>
</table>';

# PDF erzeugen

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($header_txt);
$pdf->SetTitle('Ruggero Zehnder | Fusszonenreflexmassage');
$pdf->SetSubject("Rechnungs-Nr. ".$id_buchung);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('dejavusans', '', $fontsize);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(true);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage('P', 'A4');
$pdf->setFooterFont(['dejavusans', '', 8]);
$pdf->SetFooterMargin(10);
$pdf->writeHTML($bestaetigung_ausgabe_pdf, true, true, false, false, '');

# neue Seite anlegen mit QRC EZ

$pdf->AddPage();
$bildhoehe = 190;
$pdf->SetY($bildhoehe); // relativer Abstand zum unteren Rand
$pdf->Image($qrc_path, '', '', 180, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);

# PDF speichern
$pfad = WB_PATH."/media/rechnungen/";
$jahr_monat = date('Ymd', strtotime($start));
$dokument = $jahr_monat."_rechnung_buchungsid_".$id_buchung.".pdf";
$pdf->Output($pfad.$dokument, 'F');

# PDF Nmaen in tbl_buchung einztragen
$sql = "UPDATE `tbl_buchung` SET `pdf_rechnung` = '$dokument' WHERE `id_buchung`='$id_buchung';";
$database->query($sql);	


# PDF ist fertig erstellt
echo "<div align='center'>
<table cellpadding='5' border='0'>
<tr>
	<td style='text-align: center;'>
	<br>
	<a href=".WB_URL."/media/rechnungen/".$dokument." target='_blank' >
	<img src='".WB_URL."/pages/pdficon.png' border='0'>
	<br>PDF Rechnung öffnen</a>
	</td>
</tr>
</table>
</div>
<p>&nbsp;</p>";


// https://buche-deine-ruhe.ch/var/www/vhosts/buche-deine-ruhe.ch/httpdocs/media/rechnungen/rechnung_buchungsid_14.pdf

?>
