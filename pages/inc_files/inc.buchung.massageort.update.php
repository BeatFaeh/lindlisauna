<?php


$id_buchung = $_POST['id_buchung'];
$id_angebot = $_POST['id_angebot'];
$id_ort     = $_POST['id_ort'];
$id_events  = $_POST['id_events'];


# NEIN a. Terminkollision pruefen --------------------------------------------------------------------------
# NEIN b. Neues ICS-File erstellen -------------------------------------------------------------------------
# NEIN c. Update Tabelle Buchung tbl_buchung.id_angebot ----------------------------------------------------
# d. Update Tabelle events Termin ---------------------------------------------------------------------
# NEIN e. Update Tabelle events Pause (15’) ----------------------------------------------------------------
# f. Alte Rechnung PDF loeschen in media/rechnungen ---------------------------------------------------
# g. Neue Rechnung erstellen Ablage in media/rechnungen -----------------------------------------------
# h. E-Mail-Versand > Versand automatisch? ------------------------------------------------------------


// echo "SQL<br><pre>".$sql."</pre>";
//	echo var_dump($_POST);

$filenamen = basename(__FILE__);

echo $action;

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
	<p>&nbsp;</p>";