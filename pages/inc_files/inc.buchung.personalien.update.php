<?php

$id_buchung = $_POST['id_buchung'];
$id_angebot = $_POST['id_angebot'];
$id_ort     = $_POST['id_ort'];
$id_events  = $_POST['id_events'];

$anrede     = $_POST['anrede'];
$vname      = $_POST['vname'];
$nname      = $_POST['nname'];
$email      = $_POST['email'];
$strasse    = $_POST['strasse'];
$plz        = $_POST['plz'];
$ort        = $_POST['ort'];
$telefon    = $_POST['telefon'];
$mitteilung = $_POST['mitteilung'];

# SQL-Statement
$sql = "
	UPDATE `tbl_buchung` SET
	`anrede`           = '$anrede',
	`vname`            = '$vname',
	`nname`            = '$nname',
	`email`            = '$email',
	`strasse`          = '$strasse',
	`plz`              = '$plz',
	`ort`              = '$ort',
	`telefon`          = '$telefon',
	`mitteilung`       = '$mitteilung'
	WHERE `id_buchung` = '$id_buchung';";

$database->query($sql);

// echo "SQL<br><pre>".$sql."</pre>";
//	echo var_dump($_POST);

$filenamen = basename(__FILE__);

echo $action;

echo "<div align='center'>

	<table id='myTable'>

	<tr>
	<td style='text-align: center;'>Der Datensatz<br>ID&nbsp;".$id_buchung."
	<br>wurde erfolgreich angepasst!<br></td>
	</tr>

	<tr>
		<td colspan='2' style='text-align: center;'>".$filenamen."</td>
	</tr>

	<tr>
	<td style='text-align: center;'>zurück zum Datensatz<br><br>  
	<input type ='submit' name ='submit_edit' value='zurück zum Datensatz ".$id_buchung."' class='myButtonGross'>
	<input type ='hidden' name ='submit_auswahl' value='".$id_buchung."'>
	</td>
	</tr>

	<tr>
	<td style='text-align: center;'>
	<input 
	type='button' onClick=\"parent.location='".WB_URL."/pages/buchen/edit.php'\" 
	value='zurück zur Übersicht' 
	class='myButtonGross'>
	</tr>
	
	</table>
	<p>&nbsp;</p>";