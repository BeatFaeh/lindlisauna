<?php

# Datenbankverbindung
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

# nebst den Eintraegen in tbl_buchung und events muss auch die PDF Datei gelöscht werden!
$id_buchung = intval($_POST['submit_auswahl']);

$sql = "SELECT * FROM `vw_buchung` WHERE `id_buchung` = '$id_buchung '";
$query_fields = $database->query($sql);

while($field = $query_fields->fetchRow(MYSQLI_ASSOC)) 
{
	$pdf_rechnung = $field['pdf_rechnung'];
	$email        = $field['email'];
	$vname        = $field['vname'];
	$nname        = $field['nname'];
	$start        = $field['nname'];
	$end          = $field['nname'];
	$anrede       = $field['anrede'];
}		

# Body erstellen

$body = '
<div style=\"width:600px;float:left;\">
<div style=\"padding-top:10px;padding-bottom:10px;width:700px;text-align:center;background-color:#F2F2F2;font-family:Verdana;font-size:16px;\"> 
<br>Kontaktformular | www.buche-deine-ruhe.ch | info@buche-deine-ruhe.ch
<p>&nbsp</p>
</div>
<div style=\"width:600px;float:left;background-color:#FFFFFF;padding:10px;font-family:Verdana;font-size:16px;\">

<p>&nbsp</p>	

Grüezi '.$anrede.' '.$vname.' '.$nname.'

<p>&nbsp</p>

Leider mussten wir den Termin <b>Fusszonenreflexmassage Buchungsnummer '.$id_buchung.'</b> löschen!

<p>&nbsp</p>

Bitte nehmen Sie mit uns Kontakt auf oder buchen Sie einen neuen Termin!

<p>&nbsp</p>

Bitte verzeihen Sie die Umstände und herzlichsten Dank für Ihr Verständnis!

<p>&nbsp</p>

Freundliche Grüsse
<br>
Ruggero Zehnder';


#Pfad und Datei
$dateipfad = WB_PATH."/media/rechnungen/".$pdf_rechnung;

# Pruefen, ob die Datei existiert
if (file_exists($dateipfad)) 
{
    # Datei löschen
	unlink($dateipfad);
}	

$sql = "DELETE FROM `events` WHERE `id_buchung` = '$id_buchung'";
$mysqli->query($sql);

$sql = "DELETE FROM `events` WHERE `pause` = '$id_buchung'";
$mysqli->query($sql);

$sql = "DELETE FROM `tbl_buchung` WHERE `id_buchung` = '$id_buchung'";
$mysqli->query($sql);


# E-Mail Versand an Buchungspoerson und Ruggero senden

echo "Die Buchung<br>".$id_buchung."<br>und die PDF-Rechnung<br>".$pdf_rechnung."<br>wurden erfolgreich gelöscht!";

echo $go_back;

$bcc_empfang = "beat@faeh.sh";

$body = str_replace($zeichen_suchen,$zeichen_ersetzen,$body);
$buchungsperson = str_replace($zeichen_suchen,$zeichen_ersetzen,$email);

# Betreff
$betreff = "Termin Löschung www.buche-deine-ruhe.ch";;

# Absender-E-Mail (Kunde)
$absenderEmail = $email;
# Name des Absenders (Kunde)
$absenderName  = $vname." ".$nname;

# Empfaenger
$empfaengerEmail = 'info@buche-deine-ruhe.ch';	
$empfaengerNamen = 'Kontaktformular www.buche-deine-ruhe.ch';

$bcc_empfang   = "beat@faeh.sh";

# E-Mail wird versendet
$mail  = new wbmailer();

$mail->isSMTP();                            # Set mailer to use SMTP

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
$mail->AddAttachment($pfad.$dokument); # Rechnung als PDF
$mail->AddAttachment($kb_file_name);   # ICS-File

foreach($attachments as $attachment)
{
	$mail->AddAttachment($attachment);
}

$html = stripslashes($body);
$mail->Body = $html;
$text = str_replace("<br/>","\n", $html);
$text = html_entity_decode(strip_tags($html));
# Mehrfache Leerzeichen entfernen	
$text = preg_replace('/\s+/', ' ', $text);
$mail->AltBody = $text;

$mail->Send();