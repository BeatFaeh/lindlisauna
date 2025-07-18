<?php

# POST Variablen
$id_buchung     = $_POST['id_buchung'];
$id_events      = $_POST['id_events'];
$id_angebot     = $_POST['id_angebot'];
$id_angebot_alt = $_POST['id_angebot_alt'];
$id_ort         = $_POST['id_ort'];
$id_ort_alt     = $_POST['id_ort_alt'];
$zeit_von       = $_POST['zeit_von'];
$zeit_von_alt   = $_POST['zeit_von_alt'];
$start          = $_POST['start'];
$start_alt      = $_POST['start_alt'];

$abbruch = "NEIN";

$update_abschluss = "
        <table>
        <tr>
            <td style='text-align: center;'><br>" . $filenamen . "</td>
        </tr>
    
        <tr>
            <td  style='text-align: center;'><br>  
            <input type ='submit' name ='submit_edit' value='zurück zum Datensatz " . $id_buchung . "' class='myButtonGross'>
            <input type ='hidden' name ='submit_auswahl' value='" . $id_buchung . "'>
            </td>
        </tr>
    
        <tr>
            <td  style='text-align: center;'>
            <input 
            type='button' onClick=\"parent.location='" . WB_URL . "/pages/buchen/edit.php'\" 
            value='zurück zur Übersicht' 
            class='myButtonGross'>
        </tr>
        </table></div><p>&nbsp;</p>";

$filenamen = basename(__FILE__);

echo $action;

echo "<div align='center'>";

if($id_angebot <> $id_angebot_alt OR $id_ort <> $id_ort_alt OR $zeit_von <> $zeit_von_alt OR $start <> $start_alt)
{
    if($start <> $start_alt OR $zeit_von <> $zeit_von_alt)
    {
        # Buchungstermin

        $sql_angebot_dauer = "SELECT dauer_minuten FROM tbl_angebot WHERE id_angebot = '$id_angebot';";
        $result            = $database->query($sql_angebot_dauer)->fetchArray();
        $angebot_dauer     = $result['dauer_minuten'];

        # DATUM + ZEIT_VON ERMITTELN

        $start = $start." ".$zeit_von;
        $datetime = new DateTime($start);
        $start = $datetime->format('Y-m-d H:i:s');

        # DATUM + ZEIT_ENDE (DAUER) ERMITTELN

        $datetime->modify("+{$angebot_dauer} minutes");
        $end = $datetime->format('Y-m-d H:i:s');

        # ZEIT_END 15 MINUTEN (PAUES) ADDIEREN

        $datetime = new DateTime($end);
        $datetime->modify("+15 minutes");
        $end_pause = $datetime->format('Y-m-d H:i:s');

        # PRUEFEN, OB DER ZEITRAUM GEBUCHT WERDEN KANN
        $sql_ueberschneidung = "SELECT * FROM events WHERE (end > '$start' AND start < '$end_pause') AND id_buchung <> 0;";
        $query_fields = $database->query($sql_ueberschneidung );

        if($query_fields->numRows() > 0)
        {
            $abbruch = "JA";
            echo "<div style='margin-top: 4px;color: #FF0000;'>Dieser Zeitraum ist bereits gebucht</div>
            <table>
                <tr>
                    <td><br><br>  
                    <input type ='submit' name ='submit_edit' value='zurück zum Datensatz " . $id_buchung . "' class='myButtonGross'>
                    <input type ='hidden' name ='submit_auswahl' value='" . $id_buchung . "'>
                    </td>
                </tr>
            </table>
            <p>&nbsp;</p>";
        }
        elseif($query_fields->numRows() == 0)
        {
            $sql_1 = "UPDATE `events` 
            SET start        = '$start',
            end              = '$end'
            WHERE id_buchung = '$id_buchung';";
            $database->query($sql_1);

            $sql_2 = "UPDATE `events` 
            SET start = '$end',
            end      = '$end_pause'
            WHERE pause   = '$id_buchung';";
            $database->query($sql_2);

            echo "
            <table id='myTableBuchenEdit'>
                <tr>
                    <td colspan='2' style='background-color: #efedf8;'><b>Reservation</b></td>
                </tr>
                <tr>
                    <th>Beginn</th>
                    <td align='right'>".datumswandler_ger_zeit($start)."</td>
                </tr>
                    <tr>
                    <th>Ende</th>
                <td align='right'>".datumswandler_ger_zeit($end)."</td>
                </tr>
            </table>
            <p>&nbsp;</p>";

        }
    }
    if ($id_ort <> $id_ort_alt AND $abbruch <> "JA")
    {
        # Massageort

        # UPDATE id_ort in events
        $sql_events = "UPDATE events 
        SET id_ort = '$id_ort' 
        WHERE id_buchung = '$id_buchung';";
        $database->query($sql_events );

        # KOSTEN ORT ERMITTELN

        $sql = "SELECT * FROM tbl_ort WHERE id_ort = '$id_ort';";
        $result = $database->query($sql)->fetchArray();
        $kosten_ort = $result['kosten'];
        $ort = $result['ort'];

        # KOSTEN ANGEBOT ERMITTELN

        $sql = "SELECT * FROM tbl_angebot WHERE id_angebot = '$id_angebot';";
        $result = $database->query($sql)->fetchArray();
        $kosten_angebot = $result['kosten'];

        # KOSTEN TOTAL ERMITTELN

        $kosten_total = $kosten_ort + $kosten_angebot;

        # UPADTE KOSTEN in tbl_buchung

        $sql = "UPDATE tbl_buchung 
        SET kosten_angebot    = '$kosten_angebot',
        kosten_hausbesuch = '$kosten_ort',
        kosten_total      = '$kosten_total'
        WHERE id_buchung  = '$id_buchung';";
        $database->query($sql);

        # UPADTE ORT in Tabelle events

        $sql = "UPDATE events 
        SET location  = '$ort',
        id_ort = '$id_ort'
        WHERE id_buchung  = '$id_buchung';";
        $database->query($sql);

        echo "
        <table id='myTableBuchenEdit'>
            <tr>
                <td colspan='2' style='background-color: #efedf8;'><b>Massageort</b></td>
            </tr>
            <tr>
                <th>Ort</th>
                <td>" . $ort . "</td>
            </tr>
            <tr>
                <th>Hausbesuch</th>
                <td align='right'>CHF ".number_format($kosten_ort,2)."</td>
            </tr>
            <tr>
                <th>Kosten Angebot</th>
                <td align='right'>CHF ".number_format($kosten_angebot,2)."</td>
            </tr>
            <tr>
                <th>Kosten Total</th>
                <td align='right'>CHF ".number_format( $kosten_total,2)."</td>
            </tr>
        </table>
        <p>&nbsp;</p>";
    }

    if($id_angebot <> $id_angebot_alt AND $abbruch <> "JA")
    {
        # KOSTEN ORT

        $sql        = "SELECT * FROM tbl_ort WHERE id_ort = '$id_ort';";
        $result     = $database->query($sql)->fetchArray();
        $kosten_ort = $result['kosten'];
        $ort        = $result['ort'];

        # ANGEBOT KOSTEN DAUER

        $sql_angebot_dauer    = "SELECT * FROM tbl_angebot WHERE id_angebot = '$id_angebot';";
        $result               = $database->query($sql_angebot_dauer)->fetchArray();
        $dauer_angebot        = $result['dauer_minuten'];
        $bezeichnung_angebot  = $result['bezeichnung'];
        $beschreibung_angebot = $result['beschreibung'];
        $kosten_angebot       = $result['kosten'];

        # UPADTE KOSTEN in tbl_buchung

        $kosten_total = $kosten_ort + $kosten_angebot;

        $sql_3 = "UPDATE tbl_buchung 
        SET id_angebot    = '$id_angebot',
        kosten_angebot    = '$kosten_angebot',            
        kosten_hausbesuch = '$kosten_ort',
        kosten_total      = '$kosten_total'
        WHERE id_buchung  = '$id_buchung';";
        $database->query($sql_3);

        echo "
        <table id='myTableBuchenEdit'>
            <tr>
                <td colspan='2' style='background-color: #efedf8;'><b>Angebot</b></td>
            </tr>
            <tr>
                <th>Dauer [min.]</th>
                <td>" . $dauer_angebot . "</td>
            </tr>
            <tr>
                <th>Bezeichnung</th>
                <td>" . $bezeichnung_angebot . "</td>
            </tr>
            <tr>
                <th>Beschreibung</th>
                <td>" . $beschreibung_angebot . "</td>
            </tr>
           <tr>
                <th>Kosten Angebot</th>
                <td align='right'>CHF ".number_format($kosten_angebot,2)."</td>
            </tr>
        </table>";
    }
    if($abbruch <> "JA")
    {

        # ALTER RECHNUNG LOESCHEN -------------------------------------------

        $sql = "SELECT * FROM `vw_buchung` WHERE `id_buchung` = '$id_buchung '";
        $result       = $database->query($sql)->fetchArray();
        $pdf_rechnung = $result['pdf_rechnung'];

        #Pfad und Datei
        $dateipfad = WB_PATH."/media/rechnungen/".$pdf_rechnung;

        # Pruefen, ob die Datei existiert
        if (file_exists($dateipfad))
        {
            # Datei löschen
            unlink($dateipfad);
        }

        # RECHUNG ERSTELLEN -------------------------------------------------
        $rechung_ersetzen = "JA";
        include WB_PATH."/pages/inc_files/inc.rechnung.erstellen.php";

        # QRC-ERSTELLEN -----------------------------------------------------

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

        # E-MAIL VERSAND ----------------------------------------------------

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



        echo "Der Datensatz <b>ID&nbsp;" . $id_buchung . "</b> wurde erfolgreich angepasst!<br><br>";
        echo $update_abschluss;
    }

}
else
{
    echo "Der Datensatz <b>ID&nbsp;".$id_buchung."</b> wurde nicht angepasst!<br>";
    echo $update_abschluss;
}

/*
echo "SQL 1<br><pre>".$sql_1."</pre>";
echo "SQL 2<br><pre>".$sql_2."</pre>";
echo "SQL 3<br><pre>".$sql_3."</pre>";
*/

// echo var_dump($_POST);