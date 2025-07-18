<?php

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$action  = "<form action='" .htmlspecialchars($_SERVER['PHP_SELF'])."' method='POST'>";
$go_back = "<html><head><title></title><meta http-equiv='refresh' content='1; URL=".WB_URL."/pages/farben.php'></head>";

$error = null;

if(isset($_POST['submit_insert']))
{
	$error = false;
	
	if(empty(trim($_POST['title'])))
	{
		$error = true;
		$error_title = "<font color='#FF0000'>bitte den Titel eintragen</font>";
	}


	if(empty(trim($_POST['color_code'])))
	{
		$error = true;
		$error_color_code = "<font color='#FF0000'>bitte den Farbcode eintragen</font>";
	}
	
	if(empty(trim($_POST['color'])))
	{
		$error = true;
		$error_color = "<font color='#FF0000'>bitte den Farbnamen eintragen</font>";
	}

}
if($error === false)
{	

	$title      = $_POST['title'];
	$color_code = $_POST['color_code'];
	$color      = $_POST['color'];

	$sql = "INSERT INTO `type`
	(
		`id`,
		`title`,
		`color_code`,
		`color`
	) 
	VALUES 
	(
		NULL,
	'$title',
	'$color_code',
	'$color'
	)";

	$mysqli->query($sql);

	$sql = "SELECT max(id) as max_id FROM `type`;";
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
	<td colspan='2'>Neue Farbe erfassen</td>
	</tr>
	
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>";		
	
	# title
	
	echo "
	<tr>
	<th><b>Titel</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='text' name='title' value='".htmlentities($_POST['title'])."'";
	if(isset($errorFelder['title']) OR $error_title > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $error_title."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";

	# color_code
	
	echo "
	<tr>
	<th><b>Farb Code</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='text' name='color_code' value='".htmlentities($_POST['color_code'])."'";
	if(isset($errorFelder['color_code']) OR $error_color_code > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $errorcolor_code."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";	

	# color
	
	echo "
	<tr>
	<th><b>Farbe</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='text' name='color' value='".htmlentities($_POST['color'])."'";
	if(isset($errorFelder['color']) OR $error_color > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $errorcolor."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";	

	echo "
	<tr>
		<th>Farbauswahl</th>
		<td><a href='https://www.w3schools.com/colors/colors_picker.asp' target='_blank'>Link</a></td>
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