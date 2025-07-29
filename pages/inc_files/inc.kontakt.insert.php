<?php

header('Content-Type: text/html; charset=UTF-8');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

# --------------------------------------------------------------------------------------
# Deklaration der Variablen
# --------------------------------------------------------------------------------------

$bg_rubrik = "style='background-color:#5293bf; line-height: 110%; padding: 5px; color:#FFFFFF; width=110%;'";
$txt_right = "style='text-align: right;'";
$action = "<form action='" .htmlspecialchars($_SERVER['PHP_SELF'])."' method='POST'>\n";
$email_font = "<font color='#333' face='Verdana,Geneva,Arial' size='2'>";
$mussfeld = "<font color='#FF0000'>(&#8727;)</font>";

$table = " style='border-collapse: collapse;border: 1px solid #999;border-spacing:0;border-width:0;padding:5px;'";
$table_td = " style='font-family:Verdana;font-size: 16px;vertical-align: top;border: 1px solid #999;padding: 5px;background-color: #FFFFFF;'";	
$table_td_1 = " style='font-family:Verdana;font-size: 16px;vertical-align: top;border: 1px solid #999;padding: 5px;background-color: #003d99;color:#ffffff;'";

$max_laenge = 30;
$zeichen_suchen   = "[at]";
$zeichen_ersetzen = "@";

$tagesdatum     = date("d.m.Y");

# --------------------------------------------------------------------------------------
# Gueltige Eingabe pruefen
# --------------------------------------------------------------------------------------

/*
Regex

https://www.php-einfach.de/experte/php-sicherheit/daten-validieren/
https://www.php-einfach.de/php-tutorial/regulaere-ausdruecke/

https://www.php-einfach.de/experte/php-sicherheit/daten-validieren/
https://www.php-einfach.de/php-tutorial/regulaere-ausdruecke/
*/

$zahlenmuster    = "/^['0-9 ]*$/";
$textmuster     = "/^['-.äöüÄÖÜéèêa-zA-Z ]*$/";
$textzahlmuster = "/^['-.äöüÄÖÜéèêa-zA-Z0-9 ]*$/";

# --------------------------------------------------------------------------------------
# Leere Eingabe pruefen
# --------------------------------------------------------------------------------------

$errorFelder = array();
$error = null;
$felder = array("captcha_code");

echo "<div style='text-align: center;'>";


if(isset($_POST['submit_insert']))
{
	# --------------------------------------------------------------------------------------
	# Leere Eingabe pruefen
	# --------------------------------------------------------------------------------------

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
		$captachcheck = "<br><font color='#FF0000'>der Captacha Code stimmt nicht</font>";
	}	


	# kontakt_anrede

	$kontakt_anredeErr = NULL;
	if(strlen($_POST['kontakt_anrede']) == 0)
	{
		$error = true;
		$kontakt_anredeErr = "<font color='#FF0000'>bitte bestimmen Sie die Anrede</font>";
	}

	# kontakt_vname

	$kontakt_vnameErr = NULL;
	if (!preg_match($textmuster,$_POST['kontakt_vname']) OR empty($_POST['kontakt_vname']))		
	{
		$error = true;
		$kontakt_vnameErr = "<font color='#FF0000'>der Vornamen ist zu kurz oder beinhaltet Sonderzeichen</font>";
	}

	# kontakt_nname

	$kontakt_nnameErr = NULL;
	if (!preg_match($textmuster,$_POST['kontakt_nname']) OR strlen($_POST['kontakt_nname']) < 2)
	{
		$error = true;
		$kontakt_nnameErr = "<font color='#FF0000'>der Nachnamen ist zu kurz oder beinhaltet Sonderzeichen</font>";
	}

	# kontakt_email

	$kontakt_email = str_replace($zeichen_suchen,$zeichen_ersetzen,$_POST['kontakt_email']);
	$kontakt_emailErr = NULL;
	if(empty($kontakt_email) OR !filter_var($kontakt_email, FILTER_VALIDATE_EMAIL))
	{
		$error = true;
		$kontakt_emailErr = "<font color='#FF0000'>bitte die E-Mail Adresse korrekt eingeben</font>";
	}

	# kontakt_adresse

	$kontakt_adresseErr = NULL;
	if (!preg_match($textzahlmuster,$_POST['kontakt_adresse']) OR empty($_POST['kontakt_adresse']))
	{
		$error = true;
		$kontakt_adresseErr = "<font color='#FF0000'>die Adresse ist zu kurz oder beinhaltet Sonderzeichen</font>";
	}		

	# kontakt_plz

	$kontakt_plzErr = NULL;
	if (!preg_match($zahlenmuster,$_POST['kontakt_plz']) OR empty($_POST['kontakt_plz']))
	{
		$error = true;
		$kontakt_plzErr = "<font color='#FF0000'>die Postleitzahl ist zu kurz oder beinhaltet Sonderzeichen</font>";
	}		

	# kontakt_ort

	$kontakt_ortErr = NULL;
	if (!preg_match($textmuster,$_POST['kontakt_ort']) OR empty($_POST['kontakt_ort']))
	{
		$error = true;
		$kontakt_ortErr = "<font color='#FF0000'>der Ort ist zu kurz oder beinhaltet Sonderzeichen</font>";
	}

	# kontakt_land

	$kontakt_landErr = NULL;
	if(strlen($_POST['kontakt_land']) == 0)
	{
		$error = true;
		$kontakt_landErr = "<font color='#FF0000'>bitte wählen Sie das Land aus</font>";
	}	

	# kontakt_telefon

	$kontakt_telefonErr = NULL;
	if (!preg_match("/^\+?([0-9\/ -]+)$/",$_POST['kontakt_telefon']) OR empty($_POST['kontakt_telefon']))
	{
		$error = true;
		$kontakt_telefonErr = "<font color='#FF0000'>die Telefonnummer ist zu kurz oder beinhaltet Sonderzeichen</font>";
	}	

	# kontakt_grund

	$kontakt_grundErr = NULL;
	if(strlen($_POST['kontakt_grund']) == 0)
	{
		$error = true;
		$kontakt_grundErr = "<font color='#FF0000'>bitte geben Sie einen Grund an</font>";
	}	
	
	# kontakt_mitteilung	

	$kontakt_mitteilungErr = NULL;
	if(empty($_POST['kontakt_mitteilung']))
	{
		$error = true;
		$kontakt_mitteilungErr = "<font color='#FF0000'>bitte Ihre Mitteilung eintragen</font>";
	}		
	elseif(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST['kontakt_mitteilung']))
	{
		$error = true;
		$kontakt_mitteilungErr = "<font color='#FF0000'>bei der Mitteilung bitte keine Internetadresse eintragen</font>";
	}		
}

# --------------------------------------------------------------------------------------
# Sind alle EIngaben korrekt, werden die Daten in die DB eingelesen 
# --------------------------------------------------------------------------------------

if($error === false)
{

	$ipadresse = $_SERVER['REMOTE_ADDR'];

	# POST Variablen übergeben

	$kontakt_anrede     = $_POST['kontakt_anrede'];
	$kontakt_vname      = htmlentities($_POST['kontakt_vname'], ENT_QUOTES, 'UTF-8');
	$kontakt_nname      = htmlentities($_POST['kontakt_nname'], ENT_QUOTES, 'UTF-8');
	$kontakt_email      = $_POST['kontakt_email'];
	$kontakt_adresse    = htmlentities($_POST['kontakt_adresse'], ENT_QUOTES, 'UTF-8');
	$kontakt_plz        = $_POST['kontakt_plz'];
	$kontakt_ort        = htmlentities($_POST['kontakt_ort'], ENT_QUOTES, 'UTF-8');
	$kontakt_land       = $_POST['kontakt_land'];
	$kontakt_telefon    = $_POST['kontakt_telefon'];
	$kontakt_grund      = htmlentities(trim($_POST['kontakt_grund']), ENT_QUOTES, 'UTF-8');
	$kontakt_mitteilung = htmlentities($_POST['kontakt_mitteilung'], ENT_QUOTES, 'UTF-8');

	$empfaenger = str_replace($zeichen_suchen,$zeichen_ersetzen,$kontakt_email);

	# die Daten in die Tabelle tbl_kontakt eintragen

	$datum_eintrag = date("Y-m-d");

	$sql = "INSERT INTO `tbl_kontakt`
	(	
	`kontakt_id`,
	`kontakt_eintrag`,
	`kontakt_anrede`,
	`kontakt_nname`,
	`kontakt_vname`,
	`kontakt_email`,
	`kontakt_adresse`,
	`kontakt_plz`,
	`kontakt_ort`,
	`kontakt_land`,
	`kontakt_telefon`,
	`kontakt_grund`,
	`kontakt_mitteilung`,
	`ipadresse`
	)
	VALUES
	(
	NULL,
	'$datum_eintrag',
	'$kontakt_anrede',
	'$kontakt_nname',
	'$kontakt_vname',
	'$kontakt_email',
	'$kontakt_adresse',
	'$kontakt_plz',
	'$kontakt_ort',
	'$kontakt_land',
	'$kontakt_telefon',
	'$kontakt_grund',
	'$kontakt_mitteilung',
	'$ipadresse'
	)";
	$mysqli->query($sql);

	# Body zusammenbauen 	

	$body =	
		"
	<div style=\"width:600px;float:left;\">

	<div style=\"padding-top:10px;padding-bottom:10px;width:700px;text-align:center;background-color:#003d99;font-family:Verdana;font-size:16px;color:#ffffff;\"> 
		<br>
		<img src='".WB_URL."/pages/lindlisauna_logo.png' width='15%'>
		<br>
		<br>
		Kontaktformular
		<br><br>
	</div>

	<div style=\"width:600px;float:left;background-color:#FFFFFF;padding:10px;font-family:Verdana;font-size:16px;\">

		<br><b>Kontaktbestätigung ".$kontakt_vname." ".$kontakt_nname." ".$tagesdatum."</b>
		<br>
		<br>Grüezi ".$kontakt_vname." ".$kontakt_nname."<br>
		<br>Vielen Dank für Ihr E-Mail!
		<br>Wir melden uns in Kürze wieder bei Ihnen.
		<br>
		<br>Freundliche Grüsse
		<br>Team Lindlisauna
		<br>
		<br>info@lindlisauna.ch
		<br>www.lindlisauna.ch
		 
		<br>
		<br>


			<table ".$table.">

		<tr>
			<td".$table_td_1.">Anrede</td>
			<td".$table_td.">".$kontakt_anrede."</td>
		</tr>
        <tr>
            <td".$table_td_1.">Vorname</td>
            <td".$table_td.">".$kontakt_vname."</td>
        </tr>
        <tr>
            <td".$table_td_1.">Nachname</td>
            <td".$table_td.">".$kontakt_nname."</td>
        </tr>
        <tr>
            <td".$table_td_1.">E-Mail</td>
            <td".$table_td.">".$kontakt_email."</td></tr>
        <tr>
            <td".$table_td_1.">Adresse</td>
            <td".$table_td.">".$kontakt_adresse."</td></tr>
        <tr>
            <td".$table_td_1.">PLZ</td>
            <td".$table_td.">".$kontakt_plz."</td></tr>
        <tr>
            <td".$table_td_1.">Ort</td>
            <td".$table_td.">".$kontakt_ort."</td></tr>
        <tr>
            <td".$table_td_1.">Land</td>
            <td".$table_td.">".$kontakt_land."</td></tr>
        <tr>
            <td".$table_td_1.">Telefon</td>
            <td".$table_td.">".$kontakt_telefon."</td></tr>
        <tr>
            <td".$table_td_1.">Kontaktgrund</td>
            <td".$table_td.">".$kontakt_grund."</td></tr>
        <tr>
            <td".$table_td_1.">IP-Adresse</td>
            <td".$table_td.">".$ipadresse."</td>
        </tr>
			
		</table>

		<br>
		<br>
		<table>
		<tr>
            <td ".$table_td_1.">Mitteilung</td>
            <td ".$table_td.">".nl2br($kontakt_mitteilung)."</td>
        </tr>
		</table>

	</div>
	</div>";	

	# E-Mail	

	$cc_empfang = $senderEmail;
	$bcc_empfang = "beat@faeh.sh";

	$body = str_replace($zeichen_suchen,$zeichen_ersetzen,$body);
	$empfaenger = str_replace($zeichen_suchen,$zeichen_ersetzen,$kontakt_email);

	# Betreff
	$betreff       = "Kontaktformular www.lindlisauna.ch";;

	# Absender-E-Mail (Kunde)
	$absenderEmail = $empfaenger;
	# Name des Absenders (Kunde)
	$absenderName  = $kontakt_vname." ".$kontakt_nname;

	# Empfaenger
	$empfaengerEmail = 'info@lindlisauna.ch';
	$empfaengerNamen = 'Kontaktformular www.lindlisauna.ch';

	$bcc_empfang   = "beat@faeh.sh";

	# E-Mail wird versendet
	$mail  = new wbmailer();

	$mail->isSMTP();
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

	$mail->addAddress($empfaengerEmail, $empfaengerNamen);
	$mail->addBCC($bcc_empfang);
	$mail->Subject = $betreff;
	$html = stripslashes($body);
	$mail->Body = $html;
	$text = str_replace("<br/>","\n", $html);
	$text = html_entity_decode(strip_tags($html));
	# Mehrfache Leerzeichen entfernen	
	$text = preg_replace('/\s+/', ' ', $text);
	$mail->AltBody = $text;

	$mail->Send();

	echo "<div align='center'><table>
	<tr><td >".$body."</td><tr/>
	</table></div>";

}
else
{
	# Beim Starten sieht der Kunde diese Eingabe-Maske

	echo "
	<div align='center'>
	Ihre Daten werden vertraulich behandelt!
	<br>".$mussfeld." = diese Angaben sind erforderlich
	<br><br>";

	if($error === true)
	{
		echo "<table>
		<tr>
		<td><font color='#FF0000'>Sie haben nicht alle erforderlichen Daten angegeben!</font>
		</td>
		</tr>
		</table>
		<br>";
	}

	echo $action;

	# Kontaktformular

	echo "<table>";


	# Anrede - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Anrede</b> ".$mussfeld."
	<br>
	<select name='kontakt_anrede' value='".htmlentities($_POST['kontakt_anrede'])."'";

	if(isset($errorFelder['kontakt_anrede']) OR $kontakt_anredeErr > "")
	{ 
		echo "class='select_error'>";
	} 
	else
	{
		echo " >";
	}	

	echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

	$selectedValue = htmlentities($_POST['kontakt_anrede']);
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
	echo $kontakt_anredeErr."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";
	
	# Vornamen - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Vornamen</b> ".$mussfeld."
	<br>
	<input type='text' name='kontakt_vname' value='".htmlentities($_POST['kontakt_vname'])."'";
	if(isset($errorFelder['kontakt_vname']) OR $kontakt_vnameErr > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $kontakt_vnameErr."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";
	
	# Nachnamen - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Nachnamen</b> ".$mussfeld."
	<br>
	<input type='text' name='kontakt_nname' value='".htmlentities($_POST['kontakt_nname'])."'";
	if(isset($errorFelder['kontakt_nname']) OR $kontakt_nnameErr > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >";
	}

	echo $kontakt_nnameErr."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";

	# E-Mail - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>E-Mail</b> ".$mussfeld."
	<br>
	<input type='text' name='kontakt_email' value='".htmlentities($_POST['kontakt_email'])."'";
	if(isset($errorFelder['kontakt_email']) OR $kontakt_emailErr > "")
	{
		echo "class='input_error'>";
	}
	else
	{
		echo " >";
	}
	echo $kontakt_emailErr."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Strasse | Hausnummer - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Strasse | Hausnummer</b> ".$mussfeld."
	<br>
	<input type='text' name='kontakt_adresse' value='".htmlentities($_POST['kontakt_adresse'])."'";
	if(isset($errorFelder['kontakt_adresse']) OR $kontakt_adresseErr > "")
	{
		echo " class='input_error'>\n";
	}
	else
	{
		echo " >";
	}
	echo $kontakt_adresseErr."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Postleitzahl - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Postleitzahl</b> ".$mussfeld."
	<br>
	<input type='text' name='kontakt_plz' value='".htmlentities($_POST['kontakt_plz'])."'";
	if(isset($errorFelder['kontakt_plz']) OR $kontakt_plzErr > "")
	{
		echo "class='input_error'>";
	}
	else
	{
		echo " >";
	}
	echo $kontakt_plzErr."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Ort - Pflichtfeld   ------------------------------------------------------------------------------------                    

	echo "
	<tr>
	<td><b>Ort</b> ".$mussfeld."
	<br>
	<input type='text' name='kontakt_ort' value='".htmlentities($_POST['kontakt_ort'])."'";
	if(isset($errorFelder['kontakt_ort']) OR $kontakt_ortErr > "")
	{
		echo "class='input_error'>\n";
	}
	else
	{
		echo " >";
	}
	echo $kontakt_ortErr."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Land - Pflichtfeld ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Land</b> ".$mussfeld."
	<br>
	<select name='kontakt_land' value='".htmlentities($_POST['kontakt_land'])."'";
	if(isset($errorFelder['kontakt_land']) OR $kontakt_landErr > "")
	{ 
		echo "class='select_error'>";
	} 
	else
	{
		echo " >";
	}

	echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

	$selectedValue = htmlentities($_POST['kontakt_land']);
	$myArray = array("Schweiz","Deutschland","Österreich","Italien","Frankreich","andere");


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
	echo $kontakt_landErr."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";

	# Telefon ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td><b>Telefon</b> ".$mussfeld."
	<br>
	<input type='text' name='kontakt_telefon' value='".htmlentities($_POST['kontakt_telefon'])."'";
	if(isset($errorFelder['kontakt_telefon']) OR $kontakt_telefonErr > "")
	{
		echo "class='input_error'>";
	}
	else
	{
		echo " >";
	}	
	echo $kontakt_telefonErr."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";
	
	# Kontaktgrund  ------------------------------------------------------------------------------------	
	
	echo "
	<tr>
	<td><b>Kontaktgrund</b> ".$mussfeld."
	<br>
	<select name='kontakt_grund' value='".htmlentities($_POST['kontakt_grund'])."'";
	if(isset($errorFelder['kontakt_grund']) OR $kontakt_grundErr > "")
	{ 
		echo "class='select_error'>";
	} 
	else
	{
		echo " >";
	}

	echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

	$selectedValue = htmlentities($_POST['kontakt_grund']);
	$myArray = array(
		"allgemeine Frage",
		"Kritik",
		"Mitarbeit",
		"Reservation Sauna",
		"Reservation Massageraum",
        "Reservation Seminarraum",
		"Nachfrage zu Preisen / Angeboten",
		"Sonstiges"
	);

	foreach($myArray as $element)
	{
		$isSelected = ($selectedValue == $element) ? " selected" : "";
		echo "<option value=\"".htmlentities($element, ENT_QUOTES)."\"$isSelected>".htmlentities($element)."</option>\n";
	}

	echo "</select>";
	echo $kontakt_grundErr."</td></tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";
	
	# MITTEILUNG ------------------------------------------------------------------------------------

	echo "
	<tr>
	<td style='vertical-align: text-top;'><b>Mitteilung</b> ".$mussfeld."<br>\n";

	$textarea_class = (isset($errorFelder['kontakt_mitteilung']) || $kontakt_mitteilungErr != "")
		? " class='textarea_error'"
		: "";

	$mitteilung = trim($_POST['kontakt_mitteilung'] ?? ''); // Trim entfernt Leerzeichen und Zeilenumbrüche

	echo "<textarea maxlength='" . ($max_laenge * 25) . "' 
		name='kontakt_mitteilung'{$textarea_class}>"
		. htmlentities($mitteilung, ENT_QUOTES) .
		"</textarea>";

	echo $kontakt_mitteilungErr . "</td></tr>";
	

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
		<input type='text' name='captcha_code' id='captcha_code' style='text-align: center;width: 100px;'";
	if(isset($errorFelder['captcha_code']))					
	{
		echo "class='bg_error'>\n";
	}
	else
	{
		echo " >\n";
	}	

	echo "</div></td></tr>";	

	# Formular senden -------------------------------------------------------------------------------------

	echo "<tr><td style='text-align: center;'>
	<br>
	<input class='myButtonGross' type='submit' name='submit_insert' value='senden'></td></tr>";

	echo "</table>";
	echo "</form>";
}
echo "</div>
<p>&nbsp;</p>";

/*
echo "SQL<br><pre>".$sql."</pre>";
echo "<br>mitteilungErr".$kontakt_mitteilungErr."<br>";
echo var_dump($_POST);
*/