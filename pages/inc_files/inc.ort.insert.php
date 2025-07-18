<?php

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$action  = "<form action='" .htmlspecialchars($_SERVER['PHP_SELF'])."' method='POST' enctype='multipart/form-data'>";
$go_back = "<html><head><title></title><meta http-equiv='refresh' content='1; URL=".WB_URL."/pages/ort.php'></head>";

$error = null;

if(isset($_POST['submit_insert']))
{
	$error = false;
	
	if(empty(trim($_POST['ort'])))
	{
		$error = true;
		$error_dauer_minuten = "<font color='#FF0000'>bitte den Ort eintragen</font>";
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

	$chk_upload_file = trim($_FILES["upfile"]["name"]);
	if(empty($chk_upload_file))
	{
		$error = true;
		$err_upload_lageplan = "<font color='#FF0000'>bitte den Lageplan bestimmen, der hochgeladen werden soll</font>";
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
	$ort        = $_POST['ort'];
	$kosten     = $_POST['kosten'];
	$color      = $_POST['color'];
	$color_code = $_POST['color_code'];	

	# physische File Ablage
    $target_dir      = WB_PATH."/media/lageplan";
    $source_file     = $_FILES["upfile"]["name"];
    $target_file     = sonderzeichen($source_file);		
	
	# Eintrag in tbl_ort
	$sql = "INSERT INTO `tbl_ort`
	(
		`id_ort`,
		`ort`,
		`lageplan`,
		`kosten`,
		`color`,
		`color_code`
	) 
	VALUES 
	(
	NULL,
		'$ort',
		'$target_file',
		'$kosten',
		'$color',
		'$color_code'
	)";

	$mysqli->query($sql);
	
	# max-id anfuegen
	
	$sql = "SELECT max(id_ort) as max_id FROM `tbl_ort`;";
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) 
	{
		# Ergebnis auslesen
		$row = $result->fetch_assoc();
		$max_id = $row['max_id'];
	}

    if (!empty($target_file))
    {
        $file_mit_id = $max_id."_".$target_file;
    }
    else
    {
        $file_mit_id = "";
    }

    $sql = "UPDATE `tbl_ort`
	SET `lageplan` = '$file_mit_id'
	WHERE `id_ort` = '$max_id';";
	
	$database->query($sql);	
	
    # Das Dokument abspeichern 
	$target_file_dir = $target_dir."/".$file_mit_id;
    copy($_FILES["upfile"]["tmp_name"],$target_file_dir);
	
	echo "Der Ort<br>ID = ".$max_id."<br>wurde erfolgreich erfasst!";
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
	<td colspan='2'>Neuen Ort erfassen</td>
	</tr>
	
	<tr>
	<td colspan='2'>&nbsp;</td>
	</tr>";		
	
	# ort
	
	echo "
	<tr>
	<th><b>Ort</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='text' name='ort' value='".htmlentities($_POST['ort'])."'";
	if(isset($errorFelder['ort']) OR $error_ort > "")
	{
		echo " class='input_error'>";
	}
	else
	{
		echo " >\n";
	}

	echo $error_ort."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";	
	
	# lageplan
	
	echo "
	<tr>
	<th><b>Lageplan</b> ".$mussfeld."&nbsp;&nbsp;</th>
	<td>
	<input type='file' name='upfile'>
	".$err_upload_lageplan."
	</td>
	</tr>";	

	echo "<tr><td>&nbsp;</td></tr>";	
	
	# kosten
	
	echo "
	<tr>
	<th><b>Kosten (u.a. nur bei Hausbesuch)</b>&nbsp;&nbsp;".$mussfeld."&nbsp;&nbsp;</th>
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

	echo $error_color_code."</td></tr>";

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

	echo $error_color."</td></tr>";

	echo "<tr><td>&nbsp;</td></tr>";	

	echo "
	<tr>
		<th><b>Farbauswahl<b></th>
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
echo "</div><p>&nbsp;</p>";
