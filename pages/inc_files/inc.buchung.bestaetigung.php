<?php

# Ausgabe in E-Mail
$bestaetigung_e_mail_body = '<table'.$style_table.'>
    <tr><td colspan="2" style="padding: 10px; font-family: Arial, sans-serif;">
    Grüezi '.$anrede.' '.$vname.' '.$nname.'<br><br>
    Herzlichen Dank für Ihre Buchung!<br><br>
    Wir haben Ihnen eine Bestätigungs-E-Mail inklusive Einzahlungsschein zugestellt.<br><br>
    Mit freundlichen Grüssen<br>
    Ruggero Zehnder
    </td></tr>
    <tr><td colspan="2" style="height: 10px;"></td></tr>
    <tr><td'.$style_header.'>Buchungsnummer</td><td'.$style_data_right.'>'.$id_buchung.'</td></tr>
    <tr><td'.$style_header.'>Erfassungsdatum</td><td'.$style_data_right.'>'.datumswandler_ger($erfassungsdatum).'</td></tr>
    <tr><td'.$style_header.'>Buchung erfolgt von</td><td'.$style_data.'>'.$anrede.' '.$vname.' '.$nname.'</td></tr>
    <tr><td'.$style_header.'>Strasse</td><td'.$style_data.'>'.$strasse.'</td></tr>
    <tr><td'.$style_header.'>PLZ | Ort</td><td'.$style_data.'>'.$plz.' '.$ort.'</td></tr>
    <tr><td'.$style_header.'>E-Mail</td><td'.$style_data.'>'.$email.'</td></tr>
    <tr><td'.$style_header.'>Telefon</td><td'.$style_data.'>'.$telefon.'</td></tr>
    <tr><td'.$style_header.'>Einverständnis zur Datenspeicherung</td><td'.$style_data.'>'.$datenspeicherung.'</td></tr>
    <tr><td'.$style_header.'>Buchung Start</td><td'.$style_data_right.'>'.datumswandler_ger_zeit($start).'</td></tr>
    <tr><td'.$style_header.'>Buchung Ende</td><td'.$style_data_right.'>'.datumswandler_ger_zeit($end).'</td></tr>
    <tr><td'.$style_header.'>Dauer</td><td'.$style_data_right.'>'.$dauer_minuten.' Minuten</td></tr>
    <tr><td'.$style_header.'>Bezeichnung</td><td'.$style_data.'>'.$bezeichnung.'</td></tr>
    <tr><td'.$style_header.'>Massageort</td><td'.$style_data.'>'.$massageort.'</td></tr>
    <tr><td'.$style_header.'>Beschreibung</td><td'.$style_data.'>'.nl2br($beschreibung).'</td></tr>
    
    <tr><td'.$style_footer.'>Pauschale bei Hausbesuch</td><td'.$style_data_right.'>CHF '.number_format($kosten_hausbesuch, 2, ".", "'").'</td></tr>
    <tr><td'.$style_footer.'>Kosten</td><td'.$style_data_right.'>CHF '.number_format($kosten, 2, ".", "'").'</td></tr>
    <tr><td'.$style_footer_abschluss.'>Kosten total</td><td'.$style_data_right.'><b>CHF '.number_format($kosten_total, 2, ".", "'").'</b></td></tr>
    </table>';

# Ausgabe in Browser
$bestaetigung_ausgabe_browser = ' 
	<div align="center">	
	<table>
	<tr><td colspan="2">
	Grüezi '.$anrede.' '.$vname.' '.$nname.'
	<br>
	<br>
	Herzlichsten Dank für Ihre Buchung!
	<br>
	<br>
	Wir haben Ihnen ein E-Mail mit der Bestätigung und dem Einzahlungsschein zugestellt.
	<br>
	<br>
	Mit freundlichen Grüssen
	<br>
	Ruggero Zehnder
	</td>
	</tr>
<tr><td colspan="2">&nbsp;</td></tr>	
<tr><td colspan="2">&nbsp;</td></tr>	
<tr><td '.$buchungsdetails_rubrik.'>Buchungsnummer</td><td '.$buchungsdetails_zahl_oben.'>&nbsp;'.$id_buchung.'&nbsp;&nbsp;</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Erfassungsdatum</td><td '.$buchungsdetails_zahl.'>&nbsp;'.datumswandler_ger($erfassungsdatum).'&nbsp;&nbsp;</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Buchung erfolgt von</td><td '.$buchungsdetails.'>&nbsp;'.$anrede.' '.$vname.' '.$nname.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Strasse</td><td '.$buchungsdetails.'>&nbsp;'.$strasse.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>PLZ | Ort</td><td '.$buchungsdetails.'>&nbsp;'.$plz.' '.$ort.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>E-Mail</td><td '.$buchungsdetails.'>&nbsp;'.$email.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Telefon</td><td '.$buchungsdetails.'>&nbsp;'.$telefon.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Einverständnis zur Datenspeicherung</td><td '.$buchungsdetails.'>&nbsp;'.$datenspeicherung.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Buchung Start</td><td '.$buchungsdetails_zahl.'>'.datumswandler_ger_zeit($start).'&nbsp;&nbsp;</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Buchung Ende</td><td '.$buchungsdetails_zahl.'>'.datumswandler_ger_zeit($end).'&nbsp;&nbsp;</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Dauer</td><td '.$buchungsdetails_zahl.'>'.$dauer_minuten.' Minuten&nbsp;&nbsp;</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Bezeichnung</td><td '.$buchungsdetails.'>&nbsp;'.$bezeichnung.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Massageort</td><td '.$buchungsdetails.'>&nbsp;'.$massageort.'</td></tr>
<tr><td '.$buchungsdetails_rubrik.'>Beschreibung</td><td '.$buchungsdetails.'>&nbsp;'.nl2br($beschreibung).'</td></tr>

<tr>
<td '.$buchungsdetails_rubrik_abschluss.'>Pauschale bei Hausbesuch</td>
<td '.$buchungsdetails_zahl.'>CHF '.number_format($kosten_hausbesuch, 2, ".", "'").'&nbsp;&nbsp;</td>
</tr>

<tr>
<td '.$buchungsdetails_rubrik_abschluss.'>Kosten</td>
<td '.$buchungsdetails_zahl.'>CHF '.number_format($kosten, 2, ".", "'").'&nbsp;&nbsp;</td>
</tr>

<tr>
<td '.$buchungsdetails_rubrik_abschluss.'>Kosten total</td>
<td '.$buchungsdetails_zahl_abschluss.'><b>CHF '.number_format($kosten_total, 2, ".", "'").'</b>&nbsp;&nbsp;</td>
</tr>

</table>
</div>';


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
    <td colspan="2" '.$schrift_body.'>Bitte überweisen Sie den offenen Betrag von <b>CHF '.number_format($kosten, 2, ".", "'").'</b> mit dem QRC-Einzahlungsschein, siehe unten.</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<tr><td colspan="2" '.$schrift_body.'>Mit freundlichen Grüssen<br>Ruggero Zehnder</td></tr>
</table>';