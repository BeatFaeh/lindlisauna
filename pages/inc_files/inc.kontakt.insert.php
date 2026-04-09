<?php

header('Content-Type: text/html; charset=UTF-8');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
// $mysqli->set_charset("utf8");
$mysqli->set_charset('utf8mb4');
$mysqli->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

# --------------------------------------------------------------------------------------
# Hilfsfunktion für sichere HTML-Ausgabe
# --------------------------------------------------------------------------------------
function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

# --------------------------------------------------------------------------------------
# Deklaration der Variablen
# --------------------------------------------------------------------------------------

$bg_rubrik = "style='background-color:#5293bf; line-height: 110%; padding: 5px; color:#FFFFFF; width=110%;'";
$txt_right = "style='text-align: right;'";
$action = "<form action='" . h($_SERVER['PHP_SELF'] ?? '') . "' method='POST'>\n";
$email_font = "<font color='#333' face='Verdana,Geneva,Arial' size='2'>";
$mussfeld = "<font color='#FF0000'>(&#8727;)</font>";

$table = " style='border-collapse: collapse;border: 1px solid #999;border-spacing:0;border-width:0;padding:5px;'";
$table_td = " style='font-family:Verdana;font-size: 16px;vertical-align: top;border: 1px solid #999;padding: 5px;background-color: #FFFFFF;'";
$table_td_1 = " style='font-family:Verdana;font-size: 16px;vertical-align: top;border: 1px solid #999;padding: 5px;background-color: #003d99;color:#ffffff;'";

$max_laenge = 30;
$zeichen_suchen   = "[at]";
$zeichen_ersetzen = "@";

$tagesdatum = date("d.m.Y");

# --------------------------------------------------------------------------------------
# POST-Werte initialisieren
# --------------------------------------------------------------------------------------

$kontakt_grund      = trim($_POST['kontakt_grund'] ?? '');
$kontakt_anrede     = trim($_POST['kontakt_anrede'] ?? '');
$kontakt_vname      = trim($_POST['kontakt_vname'] ?? '');
$kontakt_nname      = trim($_POST['kontakt_nname'] ?? '');
$kontakt_email_post = trim($_POST['kontakt_email'] ?? '');
$kontakt_adresse    = trim($_POST['kontakt_adresse'] ?? '');
$kontakt_plz        = trim($_POST['kontakt_plz'] ?? '');
$kontakt_ort        = trim($_POST['kontakt_ort'] ?? '');
$kontakt_land       = trim($_POST['kontakt_land'] ?? '');
$kontakt_telefon    = trim($_POST['kontakt_telefon'] ?? '');
$rsvs_mitglied      = trim($_POST['rsvs_mitglied'] ?? '');
$kontakt_mitteilung = trim($_POST['kontakt_mitteilung'] ?? '');
$captcha_code       = trim($_POST['captcha_code'] ?? '');

# --------------------------------------------------------------------------------------
# Gültige Eingabe prüfen
# --------------------------------------------------------------------------------------

$zahlenmuster    = "/^['0-9 ]*$/";
$textmuster      = "/^['-.äöüÄÖÜéèêa-zA-Z ]*$/";
$textzahlmuster  = "/^['-.äöüÄÖÜéèêa-zA-Z0-9 ]*$/";

# --------------------------------------------------------------------------------------
# Fehler- und Statusvariablen initialisieren
# --------------------------------------------------------------------------------------

$errorFelder = array();
$error = null;
$felder = array("captcha_code");

$captachcheck = '';

$kontakt_grundErr = '';
$kontakt_anredeErr = '';
$kontakt_vnameErr = '';
$kontakt_nnameErr = '';
$kontakt_emailErr = '';
$kontakt_adresseErr = '';
$kontakt_plzErr = '';
$kontakt_ortErr = '';
$kontakt_landErr = '';
$kontakt_telefonErr = '';
$rsvs_mitglieddErr = '';
$kontakt_mitteilungErr = '';

echo "<div style='text-align: center;'>";

if (isset($_POST['submit_insert'])) {
    # --------------------------------------------------------------------------------------
    # Leere Eingabe prüfen
    # --------------------------------------------------------------------------------------

    $error = false;

    foreach ($felder as $feld) {
        if (empty($_POST[$feld] ?? '')) {
            $error = true;
            $errorFelder[$feld] = true;
        }
    }

    # Captcha prüfen
    # NICHT angepasst, nur vorhandene Variablen werden weiterverwendet
    if (empty($_SESSION['captcha_code']) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0) {
        $captachcheck = "<br><font color='#FF0000'>der Captacha Code stimmt nicht</font>";
    }

    # kontakt_grund
    if (strlen($kontakt_grund) == 0) {
        $error = true;
        $kontakt_grundErr = "<font color='#FF0000'>bitte geben Sie einen Grund an</font>";
        $errorFelder['kontakt_grund'] = true;
    }

    # kontakt_anrede
    if (strlen($kontakt_anrede) == 0) {
        $error = true;
        $kontakt_anredeErr = "<font color='#FF0000'>bitte bestimmen Sie die Anrede</font>";
        $errorFelder['kontakt_anrede'] = true;
    }

    # kontakt_vname
    if (!preg_match($textmuster, $kontakt_vname) || empty($kontakt_vname)) {
        $error = true;
        $kontakt_vnameErr = "<font color='#FF0000'>der Vornamen ist zu kurz oder beinhaltet Sonderzeichen</font>";
        $errorFelder['kontakt_vname'] = true;
    }

    # kontakt_nname
    if (!preg_match($textmuster, $kontakt_nname) || strlen($kontakt_nname) < 2) {
        $error = true;
        $kontakt_nnameErr = "<font color='#FF0000'>der Nachnamen ist zu kurz oder beinhaltet Sonderzeichen</font>";
        $errorFelder['kontakt_nname'] = true;
    }

    # kontakt_email
    $kontakt_email = str_replace($zeichen_suchen, $zeichen_ersetzen, $kontakt_email_post);
    if (empty($kontakt_email) || !filter_var($kontakt_email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $kontakt_emailErr = "<font color='#FF0000'>bitte die E-Mail Adresse korrekt eingeben</font>";
        $errorFelder['kontakt_email'] = true;
    }

    # kontakt_adresse
    if (!preg_match($textzahlmuster, $kontakt_adresse) || empty($kontakt_adresse)) {
        $error = true;
        $kontakt_adresseErr = "<font color='#FF0000'>die Adresse ist zu kurz oder beinhaltet Sonderzeichen</font>";
        $errorFelder['kontakt_adresse'] = true;
    }

    # kontakt_plz
    if (!preg_match($zahlenmuster, $kontakt_plz) || empty($kontakt_plz)) {
        $error = true;
        $kontakt_plzErr = "<font color='#FF0000'>die Postleitzahl ist zu kurz oder beinhaltet Sonderzeichen</font>";
        $errorFelder['kontakt_plz'] = true;
    }

    # kontakt_ort
    if (!preg_match($textmuster, $kontakt_ort) || empty($kontakt_ort)) {
        $error = true;
        $kontakt_ortErr = "<font color='#FF0000'>der Ort ist zu kurz oder beinhaltet Sonderzeichen</font>";
        $errorFelder['kontakt_ort'] = true;
    }

    # kontakt_land
    if (strlen($kontakt_land) == 0) {
        $error = true;
        $kontakt_landErr = "<font color='#FF0000'>bitte wählen Sie das Land aus</font>";
        $errorFelder['kontakt_land'] = true;
    }

    # kontakt_telefon
    if (!preg_match("/^\+?([0-9\/ -]+)$/", $kontakt_telefon) || empty($kontakt_telefon)) {
        $error = true;
        $kontakt_telefonErr = "<font color='#FF0000'>die Telefonnummer ist zu kurz oder beinhaltet Sonderzeichen</font>";
        $errorFelder['kontakt_telefon'] = true;
    }

    # rsvs_mitglied
    if (strlen($rsvs_mitglied) == 0) {
        $error = true;
        $rsvs_mitglieddErr = "<font color='#FF0000'>bitte die Frage beantworten</font>";
        $errorFelder['rsvs_mitglied'] = true;
    }

    # kontakt_mitteilung
    if ($kontakt_grund != 'Antrag Mitgliedschaft' && $kontakt_grund != 'Anmeldung Newsletter') {
        if (empty($kontakt_mitteilung)) {
            $error = true;
            $kontakt_mitteilungErr = "<font color='#FF0000'>bitte Ihre Mitteilung eintragen</font>";
            $errorFelder['kontakt_mitteilung'] = true;
        } elseif (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $kontakt_mitteilung)) {
            $error = true;
            $kontakt_mitteilungErr = "<font color='#FF0000'>bei der Mitteilung bitte keine Internetadresse eintragen</font>";
            $errorFelder['kontakt_mitteilung'] = true;
        }
    }
}

# --------------------------------------------------------------------------------------
# Sind alle Eingaben korrekt, werden die Daten in die DB eingelesen
# --------------------------------------------------------------------------------------

if ($error === false) {
    $ipadresse = $_SERVER['REMOTE_ADDR'] ?? '';

    $kontakt_anrede_db     = $kontakt_anrede;
    $kontakt_vname_db      = htmlentities($kontakt_vname, ENT_QUOTES, 'UTF-8');
    $kontakt_nname_db      = htmlentities($kontakt_nname, ENT_QUOTES, 'UTF-8');
    $kontakt_email_db      = $kontakt_email_post;
    $kontakt_adresse_db    = htmlentities($kontakt_adresse, ENT_QUOTES, 'UTF-8');
    $kontakt_plz_db        = $kontakt_plz;
    $kontakt_ort_db        = htmlentities($kontakt_ort, ENT_QUOTES, 'UTF-8');
    $kontakt_land_db       = $kontakt_land;
    $kontakt_telefon_db    = $kontakt_telefon;
    $kontakt_grund_db      = htmlentities($kontakt_grund, ENT_QUOTES, 'UTF-8');
    $rsvs_mitglied_db      = htmlentities($rsvs_mitglied, ENT_QUOTES, 'UTF-8');
    $kontakt_mitteilung_db = htmlentities($kontakt_mitteilung, ENT_QUOTES, 'UTF-8');

    $kontakt_email_db = str_replace($zeichen_suchen, $zeichen_ersetzen, $kontakt_email_db);
    $empfaenger = str_replace($zeichen_suchen, $zeichen_ersetzen, $kontakt_email_db);

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
    `rsvs_mitglied`,
    `kontakt_mitteilung`,
    `ipadresse`
    )
    VALUES
    (
    NULL,
    '$datum_eintrag',
    '$kontakt_anrede_db',
    '$kontakt_nname_db',
    '$kontakt_vname_db',
    '$kontakt_email_db',
    '$kontakt_adresse_db',
    '$kontakt_plz_db',
    '$kontakt_ort_db',
    '$kontakt_land_db',
    '$kontakt_telefon_db',
    '$kontakt_grund_db',
    '$rsvs_mitglied_db',
    '$kontakt_mitteilung_db',
    '$ipadresse'
    )";
    $mysqli->query($sql);

    $max_kontakt_id = $mysqli->insert_id;

    # Body zusammenbauen
    $kontakbestaetigung = html_entity_decode(
        "Kontaktbestätigung ID = " . $max_kontakt_id . " - " . $kontakt_grund_db . " " . $kontakt_anrede_db . " " . $kontakt_vname_db . " " . $kontakt_nname_db . " - " . $tagesdatum,
        ENT_QUOTES,
        'UTF-8'
    );

    $body = "<div style=\"width:600px;float:left;\">
    
        <div style=\"padding-top:25px;padding-bottom:25px;width:700px;text-align:center;background-color:#FFFFFF;font-family:Verdana;font-size:16px;color:#003d99;\"> 
        <br>
            </div>
    
        <div style=\"width:600px;float:left;background-color:#FFFFFF;padding:10px;font-family:Verdana;font-size:16px;color:#003d99;\">
    
            <br><b>" . $kontakbestaetigung . "</b>
            <br>
            <br>Grüezi " . $kontakt_vname_db . " " . $kontakt_nname_db . "<br>";

    if ($kontakt_grund === "Antrag Mitgliedschaft") {
        $body .= "<br>Vielen Dank für deine Mitgliedschaft!
            <br>Ich heisse dich im Namen des Vorstands der Lindlisauna herzlichst willkommen.
            <br>
            <br>Im Anhang sende ich dir den Einzahlungsschein des Mitgliederbeitrags.
            <br>Der Mitglieder Betrag ist CHF 50.00.";
    } else {
        $body .= "Vielen Dank für Ihr E-Mail!
                 <br>
                 <br>Wir melden uns in Kürze wieder bei Ihnen.";
    }

    $body .= "<br>
            <br>
            <br>Freundliche Grüsse
            <br>Team Lindlisauna
            <br>
            <br>info@lindlisauna.ch
            <br>www.lindlisauna.ch
            <br>
            <br>
            <img src='" . WB_URL . "/pages/lindlisauna_logo_klein.png' width='75'> 
            <br>
            <br>
    
            <table " . $table . ">
    
            <tr>
                <td" . $table_td_1 . ">Kontakt-ID</td>
                <td" . $table_td . ">" . $max_kontakt_id . "</td>
            </tr>
            
            <tr>
                <td" . $table_td_1 . ">Anrede</td>
                <td" . $table_td . ">" . h($kontakt_anrede_db) . "</td>
            </tr>
            <tr>
                <td" . $table_td_1 . ">Vorname</td>
                <td" . $table_td . ">" . $kontakt_vname_db . "</td>
            </tr>
            <tr>
                <td" . $table_td_1 . ">Nachname</td>
                <td" . $table_td . ">" . $kontakt_nname_db . "</td>
            </tr>
            <tr>
                <td" . $table_td_1 . ">E-Mail</td>
                <td" . $table_td . ">" . h($kontakt_email_db) . "</td></tr>
            <tr>
                <td" . $table_td_1 . ">Adresse</td>
                <td" . $table_td . ">" . $kontakt_adresse_db . "</td></tr>
            <tr>
                <td" . $table_td_1 . ">PLZ</td>
                <td" . $table_td . ">" . h($kontakt_plz_db) . "</td></tr>
            <tr>
                <td" . $table_td_1 . ">Ort</td>
                <td" . $table_td . ">" . $kontakt_ort_db . "</td></tr>
            <tr>
                <td" . $table_td_1 . ">Land</td>
                <td" . $table_td . ">" . h($kontakt_land_db) . "</td></tr>
            <tr>
                <td" . $table_td_1 . ">Telefon</td>
                <td" . $table_td . ">" . h($kontakt_telefon_db) . "</td>
             </tr>
            <tr>
                <td" . $table_td_1 . ">Kontaktgrund</td>
                <td" . $table_td . ">" . $kontakt_grund_db . "</td>
            </tr>
            <tr>
                <td" . $table_td_1 . ">Mitglied RhySauna Verein Schaffhausen?</td>
                <td" . $table_td . ">" . $rsvs_mitglied_db . "</td>
            </tr>        
            <tr>
                <td" . $table_td_1 . ">IP-Adresse</td>
                <td" . $table_td . ">" . h($ipadresse) . "</td>
            </tr>
                
            </table>
    
            <br>
            <br>
            <table>
            <tr>
                <td " . $table_td_1 . ">Mitteilung</td>
                <td " . $table_td . ">" . nl2br($kontakt_mitteilung_db) . "</td>
            </tr>
            </table>
    
        </div>
        </div>";

    # Absender-E-Mail (Kunde)
    $body = str_replace($zeichen_suchen, $zeichen_ersetzen, $body);
    $absenderEmail = str_replace($zeichen_suchen, $zeichen_ersetzen, $kontakt_email_db);
    $absenderName  = $kontakt_vname_db . " " . $kontakt_nname_db;

    $bcc_empfang = "beat@faeh.sh";

    # E-Mail wird versendet
    $mail = new wbmailer();

    $mail->isSMTP();
    $mail->Host       = WBMAILER_SMTP_HOST;
    $mail->SMTPAuth   = WBMAILER_SMTP_AUTH;
    $mail->Username   = WBMAILER_SMTP_USERNAME;
    $mail->Password   = WBMAILER_SMTP_PASSWORD;
    $mail->SMTPSecure = WBMAILER_SMTP_SECURE;
    $mail->Port       = WBMAILER_SMTP_PORT;

    $mail->SetLanguage("de");
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';

    # From
    $mail->setFrom('info@lindlisauna.ch', 'Kontaktformular www.lindlisauna.ch');

    # Antworten sollen an den Nutzer gehen
    $mail->addReplyTo($absenderEmail, html_entity_decode($absenderName, ENT_QUOTES, 'UTF-8'));

    # Empfaenger: Verein
    $mail->addAddress('info@lindlisauna.ch', 'Kontaktformular www.lindlisauna.ch');

    # Kopie an den Absender schicken
    $mail->addCC($absenderEmail);

    # BCC
    $mail->addBCC('beat@faeh.sh');

    $mail->Subject = $kontakbestaetigung;

    if ($kontakt_grund === "Antrag Mitgliedschaft") {
        $rechnung = WB_PATH . "/media/attachments/rechnung_mitgliedschaft.pdf";
        if (file_exists($rechnung)) {
            $mail->AddAttachment($rechnung);
        }
    }

    $html = stripslashes($body);
    $mail->Body = $html;
    $text = str_replace("<br/>", "\n", $html);
    $text = html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\s+/', ' ', $text);
    $mail->AltBody = $text;

    $mail->Send();

    echo "<div align='center'><table>
    <tr><td>" . $body . "</td></tr>
    </table></div>";
} else {
    # Beim Starten sieht der Kunde diese Eingabe-Maske

    echo "
    <div align='center'>
    Ihre Daten werden vertraulich behandelt!
    <br>" . $mussfeld . " = diese Angaben sind erforderlich
    <br><br>";

    if ($error === true) {
        echo "<table>
        <tr>
        <td><font color='#FF0000'>Sie haben nicht alle erforderlichen Daten angegeben!</font></td>
        </tr>
        </table>
        <br>";
    }

    echo $action;

    # Kontaktformular
    echo "<table>";

    # Kontaktgrund
    echo "
    <tr>
    <td><b>Kontaktgrund</b> " . $mussfeld . "
    <br>
    <select name='kontakt_grund'";

    if (isset($errorFelder['kontakt_grund']) || $kontakt_grundErr !== '') {
        echo " class='select_error'>";
    } else {
        echo ">";
    }

    echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

    $selectedValue = $kontakt_grund;
    // "Antrag Mitgliedschaft",
    $myArray = array(
        "Anmeldung Newsletter",
        "allgemeine Frage",
        "Kritik",
        "Mitarbeit",
        "Reservation Sauna",
        "Reservation Massageraum",
        "Reservation Seminarraum",
        "Nachfrage zu Preisen / Angeboten",
        "Sonstiges"
    );

    foreach ($myArray as $element) {
        $isSelected = ($selectedValue == $element) ? " selected" : "";
        echo "<option value=\"" . h($element) . "\"$isSelected>" . h($element) . "</option>\n";
    }

    echo "</select>";
    echo $kontakt_grundErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Anrede
    echo "
    <tr>
    <td><b>Anrede</b> " . $mussfeld . "
    <br>
    <select name='kontakt_anrede'";

    if (isset($errorFelder['kontakt_anrede']) || $kontakt_anredeErr !== '') {
        echo " class='select_error'>";
    } else {
        echo ">";
    }

    echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

    $selectedValue = $kontakt_anrede;
    $myArray = array("Frau", "Herr");

    foreach ($myArray as $element) {
        if ($selectedValue == $element) {
            echo "<option value=\"" . h($element) . "\" selected>" . h($element) . "</option>\n";
        } else {
            echo "<option value=\"" . h($element) . "\">" . h($element) . "</option>\n";
        }
    }

    echo "</select>";
    echo $kontakt_anredeErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Vornamen
    echo "
    <tr>
    <td><b>Vornamen</b> " . $mussfeld . "
    <br>
    <input type='text' name='kontakt_vname' value='" . h($kontakt_vname) . "'";

    if (isset($errorFelder['kontakt_vname']) || $kontakt_vnameErr !== '') {
        echo " class='input_error'>";
    } else {
        echo ">";
    }

    echo $kontakt_vnameErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Nachnamen
    echo "
    <tr>
    <td><b>Nachnamen</b> " . $mussfeld . "
    <br>
    <input type='text' name='kontakt_nname' value='" . h($kontakt_nname) . "'";

    if (isset($errorFelder['kontakt_nname']) || $kontakt_nnameErr !== '') {
        echo " class='input_error'>";
    } else {
        echo ">";
    }

    echo $kontakt_nnameErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # E-Mail
    echo "
    <tr>
    <td><b>E-Mail</b> " . $mussfeld . "
    <br>
    <input type='text' name='kontakt_email' value='" . h($kontakt_email_post) . "'";

    if (isset($errorFelder['kontakt_email']) || $kontakt_emailErr !== '') {
        echo " class='input_error'>";
    } else {
        echo ">";
    }

    echo $kontakt_emailErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Strasse | Hausnummer
    echo "
    <tr>
    <td><b>Strasse | Hausnummer</b> " . $mussfeld . "
    <br>
    <input type='text' name='kontakt_adresse' value='" . h($kontakt_adresse) . "'";

    if (isset($errorFelder['kontakt_adresse']) || $kontakt_adresseErr !== '') {
        echo " class='input_error'>";
    } else {
        echo ">";
    }

    echo $kontakt_adresseErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Postleitzahl
    echo "
    <tr>
    <td><b>Postleitzahl</b> " . $mussfeld . "
    <br>
    <input type='text' name='kontakt_plz' value='" . h($kontakt_plz) . "'";

    if (isset($errorFelder['kontakt_plz']) || $kontakt_plzErr !== '') {
        echo " class='input_error'>";
    } else {
        echo ">";
    }

    echo $kontakt_plzErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Ort
    echo "
    <tr>
    <td><b>Ort</b> " . $mussfeld . "
    <br>
    <input type='text' name='kontakt_ort' value='" . h($kontakt_ort) . "'";

    if (isset($errorFelder['kontakt_ort']) || $kontakt_ortErr !== '') {
        echo " class='input_error'>";
    } else {
        echo ">";
    }

    echo $kontakt_ortErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Land
    echo "
    <tr>
    <td><b>Land</b> " . $mussfeld . "
    <br>
    <select name='kontakt_land'";

    if (isset($errorFelder['kontakt_land']) || $kontakt_landErr !== '') {
        echo " class='select_error'>";
    } else {
        echo ">";
    }

    echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

    $selectedValue = $kontakt_land;
    $myArray = array("Schweiz", "Deutschland", "Österreich", "Italien", "Frankreich", "andere");

    foreach ($myArray as $element) {
        if ($selectedValue == $element) {
            echo "<option value=\"" . h($element) . "\" selected>" . h($element) . "</option>\n";
        } else {
            echo "<option value=\"" . h($element) . "\">" . h($element) . "</option>\n";
        }
    }

    echo "</select>";
    echo $kontakt_landErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Telefon
    echo "
    <tr>
    <td><b>Telefon</b> " . $mussfeld . "
    <br>
    <input type='text' name='kontakt_telefon' value='" . h($kontakt_telefon) . "'";

    if (isset($errorFelder['kontakt_telefon']) || $kontakt_telefonErr !== '') {
        echo " class='input_error'>";
    } else {
        echo ">";
    }

    echo $kontakt_telefonErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Mitglieder RSVS
    echo "
    <tr>
    <td><b>Mitglied RhySauna Verein Schaffhausen?</b> " . $mussfeld . "
    <br>
    <select name='rsvs_mitglied'";

    if (isset($errorFelder['rsvs_mitglied']) || $rsvs_mitglieddErr !== '') {
        echo " class='select_error'>";
    } else {
        echo ">";
    }

    echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>\n";

    $selectedValue = $rsvs_mitglied;
    $myArray = array("ja", "nein");

    foreach ($myArray as $element) {
        $isSelected = ($selectedValue == $element) ? " selected" : "";
        echo "<option value=\"" . h($element) . "\"$isSelected>" . h($element) . "</option>\n";
    }

    echo "</select>";
    echo $rsvs_mitglieddErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Mitteilung
    echo "
    <tr>
    <td style='vertical-align: text-top;'><b>Mitteilung</b> " . $mussfeld . "<br>\n";

    $textarea_class = (isset($errorFelder['kontakt_mitteilung']) || $kontakt_mitteilungErr != "")
        ? " class='textarea_error'"
        : "";

    $mitteilung = $kontakt_mitteilung;

    echo "<textarea maxlength='" . ($max_laenge * 25) . "'
        name='kontakt_mitteilung'{$textarea_class}>"
        . h($mitteilung) .
        "</textarea>";

    echo $kontakt_mitteilungErr . "</td></tr>";

    echo "<tr><td>&nbsp;</td></tr>";

    # Captcha
    echo "<tr><td><b>Pr&uuml;fziffer</b> " . $mussfeld . "</td></tr>";

    echo "<tr>
        <td style='text-align: center;'>
        <img src=\"" . WB_URL . "/include/captcha_reduziert/captcha.php?RELOAD=\"
        alt=\"Captcha\" title=\"Klicken, um das Captcha neu zu laden\"
        onclick=\"this.src+=1;document.getElementById('captcha_code').value='';\"
        width=\"140\" height=\"40\" />
        <br>
        Um einen neuen Code zu generieren, bitte auf die Grafik klicken.
        </td>
    </tr>
    <tr>
    <td style='text-align: center;'>
            " . $mussfeld . " Bitte Ergebnis eintragen
            <div style='text-align: center;'>
    <input type='text' name='captcha_code' id='captcha_code' style='text-align: center;width: 100px;'";

    if (isset($errorFelder['captcha_code'])) {
        echo " class='bg_error'>";
    } else {
        echo ">";
    }

    echo "</div></td></tr>";

    # Formular senden
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