<?php

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$action  = "<form action='" .htmlspecialchars($_SERVER['PHP_SELF'])."' method='POST'>";
$go_back = "<html><head><title></title><meta http-equiv='refresh' content='1; URL=".WB_URL."/pages/angebot/edit.php'></head>";

$error = null;

echo "<div align='center'>";

if(isset($_POST['submit_insert']))
{
	$error = false;
	
	if(empty(trim($_POST['dauer_minuten'])))
	{
		$error = true;
		$error_dauer_minuten = "<font color='#FF0000'>bitte die Dauer in Minuten eintragen</font>";
	}
	elseif (!filter_var($_POST['dauer_minuten'], FILTER_VALIDATE_INT) || $_POST['dauer_minuten'] <= 0) 
	{
		$error = true;
		$error_dauer_minuten = "<font color='#FF0000'>bitte nur positive ganze Zahlen eintragen</font>";
	}

	if(empty(trim($_POST['bezeichnung'])))
	{
		$error = true;
		$error_bezeichnung = "<font color='#FF0000'>bitte die Bezeichung eintragen</font>";
	}
	
	if (empty(trim($_POST['kosten']))) 
	{
		$error = true;
		$error_kosten = "<font color='#FF0000'>bitte die Kosten eintragen</font>";
	} 
	elseif (!preg_match('/^\d+\.\d{2}$/', $_POST['kosten'])) 
	{
		$error = true;
		$error_kosten = "<font color='#FF0000'>bitte eine positive Zahl mit zwei Dezimalstellen eintragen (z. B. 75.50)</font>";
	}


	if(empty(trim($_POST['beschreibung'])))
	{
		$error = true;
		$error_beschreibung = "<font color='#FF0000'>bitte das Angebot beschreiben</font>";
	}
}
if($error === false)
{	
	$dauer_minuten = $_POST['dauer_minuten'];
	$bezeichnung   = $_POST['bezeichnung'];
	$beschreibung  = $_POST['beschreibung'];
	$kosten        = $_POST['kosten'];

	$sql = "INSERT INTO `tbl_angebot`
	(
		`id_angebot`,
		`dauer_minuten`,
		`bezeichnung`,
		`beschreibung`,
		`kosten`
	) 
	VALUES 
	(
		NULL,
	'$dauer_minuten',
	'$bezeichnung',
	'$beschreibung',
	'$kosten'
	)";

	$mysqli->query($sql);

	$sql = "SELECT max(id_angebot) as max_id FROM `tbl_angebot`;";
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) 
	{
		# Ergebnis auslesen
		$row = $result->fetch_assoc();
		$max_id = $row['max_id'];
	}

	echo "Das Angebot<br>ID = ".$max_id."<br>wurde erfolgreich erfasst!";
	echo $go_back;

	/*
	echo "SQL<br><pre>".$sql."</pre>";	
	echo var_dump($_POST);
	*/
}
else
{	
	echo $action;
	
	
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
	
	echo "<table>

	<tr>
	<td colspan='2'>Neues Angebot erfassen</td>
	</tr>
	
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>";		
	
	# dauer_minuten
	
	echo "
	<tr>
	<th><b>Dauer in Minuten</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='text' name='dauer_minuten' value='".htmlentities($_POST['dauer_minuten'])."'";
	if(isset($errorFelder['dauer_minuten']) OR $error_dauer_minuten > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $error_dauer_minuten."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";

	# bezeichnung
	
	echo "
	<tr>
	<th><b>Bezeichnung</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='text' name='bezeichnung' value='".htmlentities($_POST['bezeichnung'])."'";
	if(isset($errorFelder['dauer_minuten']) OR $error_bezeichnung > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $error_bezeichnung."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";	

	# kosten
	
	echo "
	<tr>
	<th><b>Kosten</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='text' name='kosten' value='".htmlentities($_POST['kosten'])."'";
	if(isset($errorFelder['kosten']) OR $error_kosten > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $error_kosten."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";	
	
	# beschreibung
	
	echo "
	<tr>
	<th style='vertical-align: text-top;'><b>Beschreibung</b></th>
	<td>
	<textarea name='beschreibung' rows='10' cols='40'>".htmlentities(trim($_POST['beschreibung'] ?? ''), ENT_QUOTES)."</textarea>
	".$error_beschreibung."
	</td>
	</tr>";

# Formular Inhalt senden

	echo "<tr><td colspan='2'>&nbsp;</td></tr>
	
	<tr>
	<td colspan='2'style='text-align: center;'>
	<input class='myButtonGross' type='submit' name='submit_insert' value='senden' style='width:50%'>
	</td>
	</tr>
	<tr><td colspan='2'>&nbsp;</td></tr>
	</table></form>";
}
echo "</div>";