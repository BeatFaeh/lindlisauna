<?php

$id_buchung = $_POST['id_buchung'];
$id_angebot = $_POST['id_angebot'];
$id_ort     = $_POST['id_ort'];
$id_events  = $_POST['id_events'];

$zeit_von   = $_POST['zeit_von'];
$start      = $_POST['start'];

$sql_angebot_dauer = "SELECT dauer_minuten FROM tbl_angebot WHERE id_angebot = '$id_angebot';";
$result = $database->query($sql_angebot_dauer)->fetchArray();
$angebot_dauer = $result['dauer_minuten'];

$start = $start." ".$zeit_von.":00";
$datetime = new DateTime($start);
$datetime->modify("+{$angebot_dauer} minutes");
$end = $datetime->format('Y-m-d H:i:s');

$datetime = new DateTime($end);
$datetime->modify("+15 minutes");
$end_pause = $datetime->format('Y-m-d H:i:s');

echo $action;

/*
echo "<br>id_buchung = ".$id_buchung;
echo "<br>id_angebot = ".$id_angebot;
echo "<br>id_ort     = ".$id_ort;
echo "<br>id_events  = ".$id_events;
echo "<br>zeit_von   = ".$zeit_von;
*/
echo "<br> Angebot Dauer = ".$angebot_dauer;
echo "<br>Start = ".datumswandler_ger_zeit($start);
echo "<br>Ende  = ".datumswandler_ger_zeit($end);

# Pruefen, ob der Termin frei ist
# AND id_buchung <> 0 bedeutet, dass an Feitertagen gebucht werden kann
$sql_ueberschneidung = "SELECT * FROM events WHERE (end > '$start' AND start < '$end_pause') AND id_buchung <> 0;";
$query_fields = $database->query($sql_ueberschneidung );

if($query_fields->numRows() > 0)
{
    # das gewaehlte Zeitfenster ist nicht buchbar
    echo "<div align='center'>
    <table id='myTable'>
        <tr>
            <td>
            Dieser Termin ist berfeits gebucht!
            <br>
            Bitte prüfen Sie den Kalender und wählen Sie ein neues Datum!
            </td>
        </tr>
        <tr>
            <td align='center'>zurück zum Datensatz<br><br>  
            <input type ='submit' name ='submit_edit' value='zurück zum Datensatz ".$id_buchung."' class='myButtonGross'>
            <br>
            <input type ='hidden' name ='submit_auswahl' value='".$id_buchung."'>
            </td>
        </tr>
    </table>
    </div>
    <p>&nbsp;</p>";
}
else
{
    # das gewaehlte Zeitfenster ist buchbar
    # SQL-Statement

    # a. Terminkollision pruefen --------------------------------------------------------------------------


    $sql_1 = "UPDATE `events` SET
        `start` = '$start',
        `end` = '$end'
        WHERE `id_buchung`   = '$id_buchung';";
    $database->query($sql_1);

    echo "SQL<br><pre>".$sql_1."</pre>";

    # der Termin ist gebucht, nun muessen noch die 15' Pause in den Kalender eingetragen werden

    $sql = "UPDATE `events` SET
        `start` = '$end',
        `end`   = '$end_pause'
        WHERE `pause`   = '$id_buchung';";
    $database->query($sql);

    # b. Neues ICS-File erstellen -------------------------------------------------------------------------
    # c. Update Tabelle Buchung tbl_buchung.id_angebot ----------------------------------------------------
    # d. Update Tabelle events Termin ---------------------------------------------------------------------
    # e. Update Tabelle events Pause (15’) ----------------------------------------------------------------
    # f. Alte Rechnung PDF löschen in media/rechnungen ----------------------------------------------------
    # g. Neue Rechnung erstellen Ablage in media/rechnungen -----------------------------------------------
    # h. E-Mail-Versand > Versand automatisch? ------------------------------------------------------------




// echo "SQL<br><pre>".$sql."</pre>";
// echo var_dump($_POST);

$filenamen = basename(__FILE__);


echo "<div align='center'>

	<table id='myTable'>

        <tr>
            <td align='center'>Der Datensatz<br>ID&nbsp;".$id_buchung."
            <br>wurde erfolgreich angepasst!<br></td>
        </tr>
    
        <tr>
            <td colspan='2' align='center'>".$filenamen."</td>
        </tr>
    
        <tr>
            <td align='center'>zurück zum Datensatz<br><br>  
            <input type ='submit' name ='submit_edit' value='zurück zum Datensatz ".$id_buchung."' class='myButtonGross'>
            <input type ='hidden' name ='submit_auswahl' value='".$id_buchung."'>
            </td>
        </tr>
    
        <tr>
            <td align='center'>
            <input 
            type='button' onClick=\"parent.location='".WB_URL."/pages/buchen/edit.php'\" 
            value='zurück zur Übersicht' 
            class='myButtonGross'>
        </tr>
	
	</table>
	</div>
	<p>&nbsp;</p>";

}