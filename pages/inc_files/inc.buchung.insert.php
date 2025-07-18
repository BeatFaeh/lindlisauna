<?php

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

# Varialblen Deklaration
$action     = "<form action='" .htmlspecialchars($_SERVER['inc.buchung.erfassen.php'])."' method='POST'>";
$mussfeld   = "<font color='#FF0000'>(&#8727;)</font>";
$logo       = '<img src="'.WB_URL.'/pages/logo.jpg">';
$qrc        = '<img src="'.WB_URL.'/pages/qrc.jpg">';

include WB_PATH . "/pages/inc_files/inc.tabellen.format.php";

$max_laenge       = 30;
$zeichen_suchen   = "[at]";
$zeichen_ersetzen = "@";
$tagesdatum       = date("d.m.Y");

/*
Regex
https://www.php-einfach.de/experte/php-sicherheit/daten-validieren/
https://www.php-einfach.de/php-tutorial/regulaere-ausdruecke/
*/

$zahlenmuster    = "/^['0-9 ]*$/";
$textmuster     = "/^['-.äöüÄÖÜéèêa-zA-Z ]*$/";
$textzahlmuster = "/^['-.äöüÄÖÜéèêa-zA-Z0-9 ]*$/";

$errorFelder = array();
$error = null;
$felder = array("captcha_code");

# Formular Eingabedaten pruefen
if(isset($_POST['submit_insert']))
{
	# 2 Pruefen der korrekten Eingaben > keine Leer- oder Falschwerte
	# Pruefen, ob das Buchungsdatum frei ist, keine Buchungskollision
	# wenn nein zurueck zum eForm und entsprechende Meldung ausgeben

	$error = false;

	foreach($felder as $feld)
	{
		if(empty($_POST[$feld]))
		{
			$error = true;
			$errorFelder[$feld] = true;
		}
	}

	# Captcha pruefen

	if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0)
	{
        $captachcheck = "<div style='margin-top: 4px;color: #FF0000;'>Der Captacha Code stimmt nicht oder wurde nicht eingetragen</div>";
	}	
	
	# anrede

	$anrede_err = NULL;
	if(strlen($_POST['anrede']) == 0)
	{
		$error = true;
		$anrede_err = "<div style='margin-top: 4px;color: #FF0000;'>Bitte bestimmen Sie die Anrede</div>";
	}

	# vname

	$vname_err = NULL;
	if (!preg_match($textmuster,$_POST['vname']) OR empty($_POST['vname']))		
	{
		$error = true;
		$vname_err = "<div style='margin-top: 4px;color: #FF0000;'>Der Vornamen ist zu kurz oder beinhaltet Sonderzeichen</div>";
	}

	# nname

	$nname_err = NULL;
	if (!preg_match($textmuster,$_POST['nname']) OR strlen($_POST['nname']) < 2)
	{
		$error = true;
		$nname_err = "<div style='margin-top: 4px;color: #FF0000;'>Der Nachnamen ist zu kurz oder beinhaltet Sonderzeichen</div>";
	}

	# email

	$email = str_replace($zeichen_suchen,$zeichen_ersetzen,$_POST['email']);
	$email_err = NULL;
	if(empty($email) OR !filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$error = true;
		$email_err = "<div style='margin-top: 4px;color: #FF0000;'>Bitte die E-Mail Adresse korrekt eingeben</div>";
	}

	# strasse

	$strasse_err = NULL;
	if (!preg_match($textzahlmuster,$_POST['strasse']) OR empty($_POST['strasse']))
	{
		$error = true;
		$strasse_err = "<div style='margin-top: 4px;color: #FF0000;'>Die Adresse ist zu kurz oder beinhaltet Sonderzeichen</div>";
	}		

	# plz

	$plz_err = NULL;
	if (!preg_match($zahlenmuster,$_POST['plz']) OR empty($_POST['plz']))
	{
		$error = true;
		$plz_err = "<div style='margin-top: 4px;'><font color='#FF0000'>Die Postleitzahl ist zu kurz oder beinhaltet Sonderzeichen</font></div>";
	}		

	# ort

	$ort_err = NULL;
	if (!preg_match($textmuster,$_POST['ort']) OR empty($_POST['ort']))
	{
		$error = true;
		$ort_err = "<div style='margin-top: 4px;color: #FF0000;'>Der Ort ist zu kurz oder beinhaltet Sonderzeichen</div>";
	}

	# telefon

	$telefon_err = NULL;
	if (!preg_match("/^\+?([0-9\/ -]+)$/",$_POST['telefon']) OR empty($_POST['telefon']))
	{
		$error = true;
		$telefon_err = "<div style='margin-top: 4px;color: #FF0000;'>Die Telefonnummer ist zu kurz oder beinhaltet Sonderzeichen</div>";
	}	

	# id_angebot

	$id_angebot_err = NULL;
	if(empty($_POST['id_angebot'])) 
	{
    	$error = true;
    	$id_angebot_err = "<div style='margin-top: 4px;color: #FF0000;'>Bitte wählen Sie ein Angebot aus</div>";
	}

	# id_ort

	$id_massageort_err = NULL;
	if(empty($_POST['id_ort'])) 
	{
    	$error = true;
    	$id_massageort_err = "<div style='margin-top: 4px;color: #FF0000;'>Bitte wählen Sie den Durchführungsort der Massage</div>";
	}
	
	# bemerkung	

	$bemerkung_err = NULL;
	if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['bemerkung']))
	{
		$error = true;
		$bemerkung_err = "<div style='margin-top: 4px;color: #FF0000;'>Bei der Bemerkung bitte keine Internetadresse eintragen</div>";
	}
	
	# zeit_von
	
	$zeit_von_err = NULL;
	if(strlen($_POST['zeit_von']) == 0)
	{
		$error = true;
		$zeit_von_err = "<div style='margin-top: 4px;color: #FF0000;'>Bitte tragen Sie die Uhrzeit ein</div>";
	}
	
	# buchungsdatum
	
	$buchungsdatum_err = NULL;
	if(empty($_POST['buchungsdatum']))
	{
		$error = true;
		$buchungsdatum_err = "<div style='margin-top: 4px;color: #FF0000;'>Bitte tragen Sie das Buchungsdatum ein</div>";
	}
    # Termin Ueberschneidung pruefen
	
	$buchung_ueberschneidung_err = NULL;
	$id_angebot                  = $_POST['id_angebot'];
	$sql_angebot_dauer           = "SELECT dauer_minuten FROM tbl_angebot WHERE id_angebot = '$id_angebot';";
	$result                      = $database->query($sql_angebot_dauer)->fetchArray();
	$angebot_dauer               = $result['dauer_minuten'];

	$buchungsdatum               = $_POST["buchungsdatum"];
	$zeit_von                    = $_POST["zeit_von"];

	# $buchungsdatum und $zeit_von und $angebot_dauer duerfen nicht leer sein

	$buchungsdatum_err = NULL;
	if (!empty($buchungsdatum) && !empty($zeit_von) && !empty($angebot_dauer)) 
	{
		// ["zeit_von"]=> string(8) "09:00:00"

        $start = $buchungsdatum." ".$zeit_von;
		$datetime = new DateTime($start);
		$start = $datetime->format('Y-m-d H:i:s');
		$datetime->modify("+{$angebot_dauer} minutes");
		$end = $datetime->format('Y-m-d H:i:s');

        $datetime = new DateTime($end);
        $datetime->modify("+15 minutes");
        $end_pause = $datetime->format('Y-m-d H:i:s');
	} 
	else 
	{
		$buchungs_err = "<div style='margin-top: 4px;color: #FF0000;'>Buchungsdatum, Uhrzeit und Angebot dürfen nicht leer sein!</div>";
	}
	
	# AND id_buchung <> 0 bedeutet, dass an Feitertagen gebucht werden kann
    $sql_ueberschneidung = "SELECT * FROM events WHERE (end > '$start' AND start < '$end_pause') AND id_buchung <> 0;";
    $query_fields = $database->query($sql_ueberschneidung );	
	
	if($query_fields->numRows() > 0)
    {
		$error = true;
		$buchung_ueberschneidung_err = "<div style='margin-top: 4px;color: #FF0000;'>Dieser Zeitraum ist bereits gebucht</div>";
    }

	# datenspeicherung
	
	$datenspeicherung_err = NULL;
	if(strlen($_POST['datenspeicherung']) == 0)
	{
		$error = true;
		$datenspeicherung_err = "<div style='margin-top: 4px;color: #FF0000;'>Bitte bestätigen Sie das Speichern Ihrer Daten</div>";
	}
	elseif($_POST['datenspeicherung'] == "nein")
	{
		$error = true;
		$datenspeicherung_err = "<div style='margin-top: 4px;color: #FF0000;'>Ohne Ihre Zusage zur Datenspeicherung können wir Ihre Buchung
		<br>nicht elektronisch verarbeiten</div>";
	}	
	

    $error === true;
}
if($error === false) 
{
	# 4. Alle daten sind korrekt

	$id_angebot       = $_POST['id_angebot'];
	$erfassungsdatum  = $_POST['erfassungsdatum'];
	$anrede           = $_POST['anrede'];
	$nname            = $_POST['nname'];
	$vname            = $_POST['vname'];
	$strasse          = $_POST['strasse'];
	$plz              = $_POST['plz'];
	$ort              = $_POST['ort'];
	$email            = $_POST['email'];
	$telefon          = $_POST['telefon'];
	$mitteilung       = $_POST['mitteilung'];
	$datenspeicherung = $_POST['datenspeicherung'];
	
	#Durchfuehrungsort der Massage
	$id_ort           = $_POST['id_ort'];
	
	$buchungsdatum    = $_POST['buchungsdatum'];
	$zeit_von         = $_POST['zeit_von'];

    # Dauer und Kosten des Angebots
	$sql_angebot_dauer = "SELECT * FROM tbl_angebot WHERE id_angebot = '$id_angebot';";
    $result = $database->query($sql_angebot_dauer)->fetchArray();
    $angebot_dauer        = $result['dauer_minuten'];
    $angebot_kosten       = $result['kosten'];
    $angebot_bezeichnung  = $result['bezeichnung'];
    $angebot_beschreibung = $result['beschreibung'];

    # Hausbesuch
    $sql_hausbesuch_kosten = "SELECT kosten FROM tbl_ort WHERE id_ort = '$id_ort';";
    $result = $database->query($sql_hausbesuch_kosten)->fetchArray();
    $hausbesuch_kosten = $result['kosten'];

    if(empty($hausbesuch_kosten))
    {
        $hausbesuch_kosten = 0;
    }

    $total_kosten = $angebot_kosten + $hausbesuch_kosten;

	$start = $buchungsdatum." ".$zeit_von;
	$datetime = new DateTime($start);
	$datetime->modify("+{$angebot_dauer} minutes");
	$end = $datetime->format('Y-m-d H:i:s');

    # der Termin steht, nun muessen noch die 15' Pause in den Kalender berechnet werden

    $datetime = new DateTime($end);
    $datetime->modify("+15 minutes");
    $end_pause = $datetime->format('Y-m-d H:i:s');

	# uebrige Werte ermitteln
    $erfassungsdatum  = date("Y-m-d");
	$rechnung_bezahlt = "nein";
	$ipadresse        = $_SERVER['REMOTE_ADDR'];
	$rechnung_bezahlt = "nein";

	# 4.1 Personendaten und Kosten in Tabelle tbl_buchung eintragen

	$sql_buchung = "INSERT INTO `tbl_buchung`
	(	
	`id_buchung`,
	`id_angebot`,
	`erfassungsdatum`,
	`rechnung_bezahlt`,
	`anrede`,
	`nname`,
	`vname`,
	`strasse`,
	`plz`,
	`ort`,
	`email`,
	`telefon`,
	`mitteilung`,
	`datenspeicherung`,
	`kosten_angebot`,
	`kosten_hausbesuch`,
	`kosten_total`,
	`ipadresse`
	)
	VALUES
	(
	NULL,
	'$id_angebot',
	'$erfassungsdatum',
	'$rechnung_bezahlt',
	'$anrede',
	'$nname',
	'$vname',
	'$strasse',
	'$plz',
	'$ort',
	'$email',
	'$telefon',
	'$mitteilung',
	'$datenspeicherung',
	'$angebot_kosten',
	'$hausbesuch_kosten',
	'$total_kosten',
	'$ipadresse'
	)";

   // echo "<pre>".$sql_buchung."</pre>";
	$mysqli->query($sql_buchung);
	
	# 4.2 max(id_buchung) ermitteln

	$sql_max_id_buchung = "SELECT MAX(id_buchung) AS max_id_buchung FROM `tbl_buchung`;";
    $result = $database->query($sql_max_id_buchung)->fetchArray();
    $max_id_buchung = $result['max_id_buchung'];

	# 4.4 den Ort bestimmen
	$sql = "SELECT * FROM `tbl_ort` WHERE `id_ort` = '$id_ort '";
	$query_fields = $database->query($sql);

	while($field = $query_fields->fetchRow(MYSQLI_ASSOC)) 
	{
		$lageplan          = $field['lageplan'];
		$massageort        = $field['ort'];
		$kosten_hausbesuch = $field['kosten'];
		$color_code        = $field['color_code'];
	}		

	$title = "Buchungsnummer = ".$max_id_buchung;
	$image = NULL;
	$description = 'Buchung';
	$url  = NULL;

    # 4.3 max(id_buchung), sowie start und end in die Tabelle events eintragen

    $sql_events = "INSERT INTO `events`
	(	
	`id`,
	`id_buchung`,
	`title`,
	`image`,
	`description`,
	`location`,
	`start`,
	`end`,
	`url`,
	`color`,
	`id_ort`
	)
	VALUES
	(
	NULL,
	'$max_id_buchung',
	'$title',
	'$image',
	'$description',
	'$massageort',
	'$start',
	'$end',
	'$url',
	'$color_code',
	'$id_ort'
	)";
	$mysqli->query($sql_events);
	// echo "<pre>".$sql_events."</pre>";

    # 4.4 max(id_buchung), sowie  start und end der Pause in die Tabelle events eintragen

	$sql_events = "INSERT INTO `events`
	(	
	`id`,
	`id_buchung`,
	 `pause`,
	`title`,
	`image`,
	`description`,
	`location`,
	`start`,
	`end`,
	`url`,
	`color`,
	`id_ort`
	)
	VALUES
	(
	NULL,
	0,
	'$max_id_buchung', 
	'Pause',
	'$image',
	'Pause',
	'Pause',
	'$end',
	'$end_pause',
	'$url',
	'#c0c0c0',
	0
	)";
	$mysqli->query($sql_events);

	# 5. Rechnung erstellen
    $rechung_ersetzen = "NEIN";
	include WB_PATH."/pages/inc_files/inc.rechnung.erstellen.php";
	
	# 6. ICS erstellen	
	
	$frequenz = "DAILY";
	
    # URL
    $url = "https://www.whbs-reservation.ch";
    
	$ics_titel = $title." ".$anrede." ".$nname." ".$vname;
	
	$start_zeit = datumswandler_ger_zeit($start);
	$end_zeit = datumswandler_ger_zeit($end);
	
	$felder = [
		"Massageort" => $massageort,
		"Reservationsnummer" => $title,
		"Startdatum" => $start_zeit,
		"Enddatum" => $end_zeit,
		"Anrede" => $anrede,
		"Nachname" => $nname,
		"Vorname" => $vname,
		"Strasse" => $strasse,
		"PLZ" => $plz,
		"Ort" => $ort,
		"E-Mail" => $email,
		"Telefon" => $telefon,
	];

	# E-Mail Body normal aufbauen
	$mail_body = '';
	foreach ($felder as $key => $value) {
		$mail_body .= "$key = $value\r\n"; // Hier echte Zeilenumbrüche
	}

	# ICS-Description quoted-printable codiert aufbauen
	$description = '';
	foreach ($felder as $key => $value) {
		$line = "$key = $value";
		$description .= quoted_printable_encode($line) . "=0D=0A"; // Für ICS nötig
	}

    $start_ics       = icsformat($start);
    $end_ics         = icsformat($end);
	
    $kb_title        = html_entity_decode($ics_titel, ENT_COMPAT, 'UTF-8');
    $kb_location     = html_entity_decode($massageort, ENT_COMPAT, 'UTF-8');
    $kb_description  = html_entity_decode($description, ENT_COMPAT, 'UTF-8');
    $kb_url          = 'https://buche-deine-ruhe.ch/';
    $kb_file_name    = 'buchung.ics';

    $kb_ical = fopen($kb_file_name, 'w') or die('Datei kann nicht gespeichert werden!');

    $eol = "\r\n";
    $kb_ics_content =
        'BEGIN:VCALENDAR'.$eol.
        'VERSION:2.0'.$eol.
        'PRODID:-//A&B SOLUTIONS//absolutions.ch//CH'.$eol.
        'CALSCALE:GREGORIAN'.$eol.
        'BEGIN:VEVENT'.$eol.
        'DTSTART:'.$start_ics.$eol.
        'DTEND:'.$end_ics.$eol.
        'LOCATION:'.$kb_location.$eol.
        'SUMMARY:'.$kb_title.$eol.
        'URL;VALUE=URI:'.$kb_url.$eol.
        'DESCRIPTION;ENCODING=QUOTED-PRINTABLE:'.$kb_description.$eol.
        'END:VEVENT'.$eol.
        'END:VCALENDAR';
    fwrite($kb_ical, $kb_ics_content);
    fclose($kb_ical);

    # Ausgabe in E-Mail
    $bestaetigung_e_mail_body = '
    <table'.$style_table.' width="50%">

    <tr><td colspan="2" style="height: 10px;">&nbsp;</td></tr>
    
    <tr>
        <td style="text-align: left;vertical-align: text-top;">'.$adresse.'</td>
        <td style="text-align: right;vertical-align: text-top;">'.$logo.'</td>
    </tr>

    <tr><td colspan="2" style="height: 50px;">&nbsp;</td></tr>

    <tr>
        <td  style="text-align: left;vertical-align: text-top;"><b>'.$anrede.' '.$vname.' '.$nname.'
        <br>'.$strasse.'
        <br>'.$plz.' '.$ort.'</b>
        </td>
        <td style="text-align: right;vertical-align: text-top;">Erfassungsdatum '.datumswandler_ger($erfassungsdatum).'</td>
    </tr>
    
    <tr><td colspan="2" style="height: 20px;">&nbsp;</td></tr>
    
    <tr><td colspan="2" ><b>Fusszonenreflexmassage Buchungsnummer '.$id_buchung.'</b></td></tr>

     <tr>
        <td colspan="2">&nbsp;</td>
     </tr>
    
     <tr>
        <td colspan="2">Grüezi '.$anrede.' '.$vname.' '.$nname.'<br><br>Herzlichen Dank für Ihre Buchung!</td><tr>
     </tr>
     
     <tr>
        <td colspan="2" '.$schrift_body.'>Bitte überweisen Sie den offenen Betrag von <b>CHF '.number_format($kosten, 2, ".", "'").'</b> mit dem QRC-Einzahlungsschein, siehe Anhang.</td>
    </tr>        
    
    <tr><td>Mit freundlichen Grüssen<br><br>Ruggero Zehnder</td></tr>
    
    
    <tr><td colspan="2" style="height: 20px;"></td></tr>
    
    <tr><td'.$style_header_oben.'>Buchungsnummer</td><td'.$style_data_right_oben.'>'.$id_buchung.'</td></tr>
    <tr><td'.$style_header.'>Erfassungsdatum</td><td'.$style_data_right.'>'.datumswandler_ger($erfassungsdatum).'</td></tr>
    <tr><td'.$style_header.'>Buchung erfolgt von</td><td'.$style_data.'>'.$anrede.' '.$vname.' '.$nname.'</td></tr>
    <tr><td'.$style_header.'>Strasse</td><td'.$style_data.'>'.$strasse.'</td></tr>
    <tr><td'.$style_header.'>PLZ | Ort</td><td'.$style_data.'>'.$plz.' '.$ort.'</td></tr>
    <tr><td'.$style_header.'>E-Mail</td><td'.$style_data.'>'.$email.'</td></tr>
    <tr><td'.$style_header.'>Telefon</td><td'.$style_data.'>'.$telefon.'</td></tr>
    <tr><td'.$style_header.'>Einverständnis zur Datenspeicherung</td><td'.$style_data.'>'.$datenspeicherung.'</td></tr>
    
    <tr><td'.$style_header.'>Buchung Start</td><td'.$style_data_right.'>'.datumswandler_ger_zeit($start).'</td></tr>
    <tr><td'.$style_header.'>Buchung Ende</td><td'.$style_data_right.'>'.datumswandler_ger_zeit($end).'</td></tr>
    
    <tr><td'.$style_header.'>Dauer</td><td'.$style_data_right.'>'.$angebot_dauer.' Minuten</td></tr>
    <tr><td'.$style_header.'>Bezeichnung</td><td'.$style_data.'>'.$angebot_bezeichnung.'</td></tr>
    <tr><td'.$style_header.'>Massageort</td><td'.$style_data.'>'.$massageort.'</td></tr>
    <tr><td'.$style_header.'>Beschreibung</td><td'.$style_data.'>'.nl2br($angebot_beschreibung).'</td></tr>
    
    <tr><td'.$style_footer.'>Pauschale bei Hausbesuch</td><td'.$style_data_right.'>CHF '.number_format($hausbesuch_kosten, 2, ".", "'").'</td></tr>
    <tr><td'.$style_footer.'>Kosten</td><td'.$style_data_right.'>CHF '.number_format($angebot_kosten, 2, ".", "'").'</td></tr>
    <tr><td'.$style_footer.'>Kosten total</td><td'.$style_data_right.'><b>CHF '.number_format($total_kosten, 2, ".", "'").'</b></td></tr>
    </table>';

    # 7. E-Mail Versand an Buchungspoerson und Ruggero senden

	$bcc_empfang = "beat@faeh.sh";

	$body = str_replace($zeichen_suchen,$zeichen_ersetzen,$bestaetigung_e_mail_body);
	$buchungsperson = str_replace($zeichen_suchen,$zeichen_ersetzen,$email);

	# Betreff
	$betreff = "Kontaktformular www.buche-deine-ruhe.ch";;

	# Absender-E-Mail (Kunde)
	$absenderEmail = $buchungsperson;
	# Name des Absenders (Kunde)
	$absenderName  = $vname." ".$nname;

	# Empfaenger
	$empfaengerEmail = 'info@buche-deine-ruhe.ch';	
	$empfaengerNamen = 'Termin Buchung - www.buche-deine-ruhe.ch';

	$bcc_empfang   = "beat@faeh.sh";

	# E-Mail wird versendet
	$mail  = new wbmailer();

	$mail->isSMTP();                              # Set mailer to use SMTP

    $mail->Host       = WBMAILER_SMTP_HOST;       # Specify main and backup SMTP servers
    $mail->SMTPAuth   = WBMAILER_SMTP_AUTH;       # Enable SMTP authentication
    $mail->Username   = WBMAILER_SMTP_USERNAME;   # SMTP username
    $mail->Password   = WBMAILER_SMTP_PASSWORD;   # SMTP password
    $mail->SMTPSecure = WBMAILER_SMTP_SECURE;     # Enable TLS encryption, `ssl` also accepted
    $mail->Port       = WBMAILER_SMTP_PORT;       # TCP port to connect to

	$mail->SetLanguage ("de");
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';

	# Absender (Kunde)
	$mail->setFrom($absenderEmail, html_entity_decode($absenderName, ENT_QUOTES, 'UTF-8'));

	$mail->addAddress($empfaengerEmail, $empfaengerNamen); # TO
	// $mail->addCC($buchungsperson); # CC
	$mail->addBCC($bcc_empfang); # BCC
	$mail->Subject = $betreff;
	
	# Attachment
	
	$pfad_lageplan = WB_PATH."/media/lageplan/";
	$dokument_lageplan = $lageplan;
	
    $mail->AddAttachment($pfad.$dokument); # Rechnung als PDF

    if($id_ort > 1) {
        $mail->AddAttachment($pfad_lageplan . $dokument_lageplan); # Lageplan
    }
	$mail->AddAttachment($kb_file_name);   # ICS-File

    foreach($attachments as $attachment)
    {
        $mail->AddAttachment($attachment);
    }
		
	$html = stripslashes($body);
	$mail->Body = $bestaetigung_e_mail_body;
	$text = str_replace("<br/>","\n", $html);
	$text = html_entity_decode(strip_tags($html));
	# Mehrfache Leerzeichen entfernen	
	$text = preg_replace('/\s+/', ' ', $text);
	$mail->AltBody = $text;

	$mail->Send();
	
	$start = $buchungsdatum." ".$zeit_von;
	$datetime = new DateTime($start);
	$datetime->modify("+{$angebot_dauer} minutes");
	$end = $datetime->format('Y-m-d H:i:s');

	if($id_ort == 1)
	{	
		$kosten_total = $kosten + $kosten_hausbesuch;
	}
	else
	{
		$kosten_hausbesuch = 0;
		$kosten_total = $kosten;
	}	

	# Browser Ausgabe

    echo ' 
        <div align="center">	
        <table>
            <tr><td colspan="2">Grüezi '.$anrede.' '.$vname.' '.$nname.'<br><br>Herzlichsten Dank für Ihre Buchung!<br><br>Wir haben Ihnen ein E-Mail mit der Bestätigung und dem Einzahlungsschein zugestellt.<br><br>Mit freundlichen Grüssen<br>Ruggero Zehnder</td>
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
}
else
{
	
# 1. EIngabe der Buchungsdaten mittles eForm
# Beim Starten sieht der Kunde diese Eingabe-Maske des Formular Termin Buchung

	echo "
	<div align='center'>
	Ihre Daten werden vertraulich behandelt!
	<br>".$mussfeld." = diese Angaben sind erforderlich
	<p>&nbsp;</p>";

	if($error === true)
	{
		echo "<table>
		<tr>
		<td><font color='#FF0000'>Sie haben nicht alle erforderlichen Daten angegeben!
		<br>
		Bitte kontrollieren Sie auch den Captacha Code ganz unten!</font>
		</td>
		</tr>
		</table>
		<br>";
	}

	echo $action;

	# ANMELDUNGSFORMULAR

	// echo "<table id='myTableBuchenEdit'>";
    echo "<table width='50%'>";

	# Anrede - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Anrede</b> ".$mussfeld."
	<br>
	<select style='width: 160px;' name='anrede' value='".htmlentities($_POST['anrede'])."'";

	if(isset($errorFelder['anrede']) OR $anrede_err > "")
	{ 
		echo "class='select_error'>";
	} 
	else
	{
		echo " >";
	}	

	echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

	$selectedValue = htmlentities($_POST['anrede']);
	$myArray = array("Frau","Herr");

	foreach($myArray AS $element)
	{
		if($selectedValue == $element)
		{
			echo "<option value=".$element." selected>".$element."</option>\n";
		}
		else
		{
			echo "<option value=".$element.">".$element."</option>\n";
		}
	}

	echo "</select>";
	echo $anrede_err."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";
	
	# Vornamen - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Vornamen</b> ".$mussfeld."
	<br>
	<input type='text' name='vname' value='".htmlentities($_POST['vname'])."'";
	if(isset($errorFelder['vname']) OR $vname_err > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $vname_err."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";
	
	# Nachnamen - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Nachnamen</b> ".$mussfeld."
	<br>
	<input type='text' name='nname' value='".htmlentities($_POST['nname'])."'";
	if(isset($errorFelder['kontakt_nname']) OR $nname_err > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >";
	}

	echo $nname_err."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";

	# E-Mail - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>E-Mail</b> ".$mussfeld."
	<br>
	<input type='text' name='email' value='".htmlentities($_POST['email'])."'";
	if(isset($errorFelder['email']) OR $email_err > "")
	{
		echo "class='input_error'>";
	}
	else
	{
		echo " >";
	}
	echo $email_err."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Strasse | Hausnummer - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Strasse | Hausnummer</b> ".$mussfeld."
	<br>
	<input type='text' name='strasse' value='".htmlentities($_POST['strasse'])."'";
	if(isset($errorFelder['strasse']) OR $strasse_err > "")
	{
		echo " class='input_error'>\n";
	}
	else
	{
		echo " >";
	}
	echo $strasse_err."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Postleitzahl - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Postleitzahl</b> ".$mussfeld."
	<br>
	<input style='width: 160px;' type='text' name='plz' value='".htmlentities($_POST['plz'])."'";
	if(isset($errorFelder['plz']) OR $plz_err > "")
	{
		echo "class='input_error'>";
	}
	else
	{
		echo " >";
	}
	echo $plz_err."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Ort - Pflichtfeld   ------------------------------------------------------------------------------------                    

	echo "
	<tr>
	<td><b>Wohnort</b> ".$mussfeld."
	<br>
	<input type='text' name='ort' value='".htmlentities($_POST['ort'])."'";
	if(isset($errorFelder['ort']) OR $ort_err > "")
	{
		echo "class='input_error'>\n";
	}
	else
	{
		echo " >";
	}
	echo $ort_err."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Telefon ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Telefon</b> ".$mussfeld."
	<br>
	<input style='width: 160px;' type='text' name='telefon' value='".htmlentities($_POST['telefon'])."'";
	if(isset($errorFelder['telefon']) OR $telefon_err > "")
	{
		echo "class='input_error'>";
	}
	else
	{
		echo " >";
	}	
	echo $telefon_err."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";
	
	# Angebot  ------------------------------------------------------------------------------------	
	
	$sql = "SELECT * FROM tbl_angebot WHERE `aktiv` = 'ja' ORDER BY 1;";
	$result = mysqli_query($mysqli,$sql);

	echo "<tr><td><table>";

	while ($field = $result -> fetch_array(MYSQLI_ASSOC))
	{

		echo "
		    <tr>
		        <td valign='top' rowspan='5'><b>".$field['id_angebot'].".&nbsp;Angebot&nbsp;&nbsp;&nbsp;</b></td>
		    </tr>
			<tr>
			    <td colspan='2'><b>".$field['bezeichnung']."</b></td>
			</tr>
			<tr>
			    <td>Dauer&nbsp;</td>
			    <td>".$field['dauer_minuten']." Minuten</td>
			</tr>
			<tr>
			    <td valign='top'>Beschreibung&nbsp;</td>
			    <td>".nl2br($field['beschreibung'])."</td>
			</tr>
			<tr>
			    <td>Kosten[CHF]&nbsp; </td>
			    <td align='right'><b>".number_format($field['kosten'],2)."</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>";
	}
	echo "</table></td></tr>";	
	
	echo "
	<tr>
	
	<td><b>Angebot</b>".$mussfeld."
	<br>";

	$sql = "SELECT * FROM `tbl_angebot` WHERE `aktiv` = 'ja' ORDER BY `dauer_minuten`";
	$query_fields = $database->query($sql);

	$selected_id = isset($_POST['id_angebot']) ? $_POST['id_angebot'] : '';

	echo "<select style='width: 160px;' name='id_angebot'>
	      <option value=''>Bitte wählen</option>";

	while ($field = $query_fields->fetchRow(MYSQLI_ASSOC))
	{
		# Auswahl prüfen
		$auswahl = ($field['id_angebot'] == $selected_id) ? "selected" : "";

		echo "<option value='".$field['id_angebot']."' $auswahl>Angebot ".$field['id_angebot']."</option>";
	}
	echo "</select>
	".$id_angebot_err;

	echo "</td></tr>";		
	
	echo "<tr><td>&nbsp;</td></tr>";

# ort der Massage ------------------------------------------------------------------------------------------
	
	echo "
	<td><b>Durchführungsort der Massage</b>".$mussfeld."
	<br>";

	$sql = "SELECT * FROM `tbl_ort` WHERE `aktiv` = 'ja' ORDER BY `ort`";
	$query_fields = $database->query($sql);

	// Hol den aktuellen Wert von POST
	$selected_id = isset($_POST['id_ort']) ? $_POST['id_ort'] : '';

	echo "<select style='width: 160px;' name='id_ort'>
	      <option value=''>Bitte wählen</option>";

	while ($field = $query_fields->fetchRow(MYSQLI_ASSOC))
	{
		// Auswahl prüfen
		$auswahl = ($field['id_ort'] == $selected_id) ? "selected" : "";

		echo "<option value='".$field['id_ort']."' $auswahl>".$field['ort']."</option>";
	}
	echo "</select>
	".$id_massageort_err;

	echo "</td></tr>";		
	
	echo "<tr><td>&nbsp;</td></tr>";	
	
# buchungsdatum ------------------------------------------------------------------------------------------
	
	echo "
	<tr>
	<td><b>Buchungssdatum</b> ".$mussfeld."
	<br>
	<input style='width: 160px;' id='arrival' type='date' name='buchungsdatum' value='".htmlentities($_POST['buchungsdatum'])."'>
	".$buchungsdatum_err." ".$buchung_ueberschneidung_err." ".$buchungs_err."</td>
	</tr>
	<tr><td>&nbsp;</td></tr>";
	
# Uhrzeit ----------------------------------------------------------------------------------------
	
	echo "
	<tr>
	<td><b>Uhrzeit</b> ".$mussfeld."
	<br>";

    $sql = "SELECT * FROM `tbl_zeit` WHERE `aktiv` = 'ja' ORDER BY `zeit`";
    $query_fields = $database->query($sql);

    # Hol den aktuellen Wert von POST
    $selected_id = isset($_POST['zeit_von']) ? $_POST['zeit_von'] : '';

    echo "<select style='width: 160px;' name='zeit_von'>
          <option value=''>Bitte wählen</option>";

    while ($field = $query_fields->fetchRow(MYSQLI_ASSOC))
    {
        // Auswahl prüfen
        $auswahl = ($field['zeit'] == $selected_id) ? "selected" : "";

        echo "<option value='".$field['zeit']."' $auswahl>".substr($field['zeit'], 0, 5)."</option>";
        // date('Y-m-d', strtotime($start))
    }
    echo "</select>
    ".$zeit_von_err."
	</td></tr>
	<tr><td>&nbsp;</td></tr>";

	# MITTEILUNG ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td style='vertical-align: text-top;'><b>Mitteilung</b><br>";

	$mitteilung = trim($_POST['mitteilung'] ?? ''); // Trim entfernt Leerzeichen und Zeilenumbrüche

	echo "<textarea maxlength='" . ($max_laenge * 25) . "' 
		name='mitteilung'{$textarea_class}>"
		. htmlentities($bemerkung, ENT_QUOTES) .
		"</textarea>
		".$bemerkung_err."
		</td>
		</tr>";

	echo "<tr><td>&nbsp;</td></tr>";

# Datenspeicherung -------------------------------------------------------------------------------

    echo "
	<tr>
		<td><b>Datenspeicherung</b><br>
		Einverständnis, dass Adressdaten für die Buchung gespeichert werden!
		<br>Diese Informationen werden vertraulich behandelt
		<br>und nicht weitergegeben.
		</td>
	</tr>
	
	<tr>
	<td>
	
	<table>
	<tr>
	<td nowrap><br>";
	
    $selectedValue = $_POST['datenspeicherung'];
    $myArray = array("ja","nein");

    foreach($myArray AS $element)
    {
        if($selectedValue == $element)
        {
            echo "<input type='radio' name='datenspeicherung' value='".$element."' checked>".$element;
        }
        else
        {
            echo "<input type='radio' name='datenspeicherung' value='".$element."'>".$element;
        }
    }

    echo "<td>
	</tr>
	</table>
	".$datenspeicherung_err."
	</td>
	</tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";
	
	# Captcha ----------------------------------------------------------------------------------------

	echo "<tr><td><b>Pr&uuml;fziffer</b> ".$mussfeld."</td></tr>";

	echo "<tr>
								<td style='text-align: center;'>
								<img src=\"".WB_URL."/include/captcha_reduziert/captcha.php?RELOAD=\" 
								alt=\"Captcha\" title=\"Klicken, um das Captcha neu zu laden\" 
								onclick=\"this.src+=1;document.getElementById('captcha_code').value='';\" 	
								width=\"140\" height=\"40\" />
								<br>
								Um einen neuen Code zu generieren, bitte auf die Grafik klicken.
								</td>
							</tr>			
			<tr>
			<td style='text-align: center;'>
					".$mussfeld." Bitte Ergebnis eintragen
		<div style='text-align: center;'>
			<input type='text' name='captcha_code' id='captcha_code' style='width: 100px;'";
	if(isset($errorFelder['captcha_code']))					
	{
		echo "class='bg_error'>";
	}
	else
	{
		echo " >";
	}	

	echo "</div>".$captachcheck."</td></tr>";	

	# Formular senden -------------------------------------------------------------------------------------

	echo "<tr><td style='text-align: center;'>
	<br>
	<input class='myButtonGross' type='submit' name='submit_insert' value='senden'></td></tr>";

	echo "</table>";
	echo "</form>";
}

// echo "<pre>".$raum_sql."</pre>";
// echo var_dump($_POST);

echo "</div>
<p>&nbsp;</p>
<p>&nbsp;</p>";


