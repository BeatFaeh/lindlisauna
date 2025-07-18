<?php

/*
<td style='text-align: center;'><input type='submit' name='submit_delete' value='delete' class='myButtonKlein'
onClick=\"return confirm('Wollen Sie den Datensatz = ".$field['id_angebot']." wirklich l&ouml;schen?')\"></td> 
*/

// 10.05.2025 - Beat Faeh

header('Content-Type: text/html; charset=UTF-8');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$bg_rubrik = "style='background-color:#475c6a; line-height: 110%; padding: 5px; color:#FFFFFF;'";
$landing_page = "pages/angebot/edit.php";
$go_back   = "<html><head><title></title>
		<meta http-equiv='refresh' content='1; URL=".WB_URL."/".$landing_page."'></head>";
$action = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$background_gesperrt = " style='text-align: right;background-color: #D8D8D8;' ";


# EDIT
if(isset($_POST['submit_edit']))
{
	if(isset($_POST['submit_auswahl']))
	{
		$id = $_POST['submit_auswahl'];
		$sql = "SELECT * FROM `tbl_angebot` WHERE `id_angebot` = '$id'";
		$query_fields = $database->query($sql);

		while($field = $query_fields->fetchRow(MYSQLI_ASSOC)) 
		{
			$id_angebot    = $field['id_angebot'];
			$dauer_minuten = $field['dauer_minuten'];
			$bezeichnung   = $field['bezeichnung'];
			$beschreibung  = $field['beschreibung'];
			$kosten        = $field['kosten'];
            $aktiv         = $field['aktiv'];
		}		

		echo $action;

		echo "
	<script>
	function validateDauer() {
		const input = document.getElementById('dauer_minuten');
		const error = document.getElementById('dauer_error');
		const value = input.value.trim();

		if (!/^[1-9]\\d*$/.test(value)) {
			error.textContent = 'Bitte eine positive ganze Zahl eingeben';
			input.style.borderColor = 'red';
		} else {
			error.textContent = '';
			input.style.borderColor = '';
		}
	}

	function validateKosten() {
		const input = document.getElementById('kosten');
		const error = document.getElementById('kosten_error');
		const value = input.value.trim();

		if (!/^\\d+\\.\\d{2}$/.test(value)) {
			error.textContent = 'Bitte eine positive Zahl mit zwei Dezimalstellen eingeben (z.B. 12.34)';
			input.style.borderColor = 'red';
		} else {
			error.textContent = '';
			input.style.borderColor = '';
		}
	}
	</script>
    
    <div align='center'>
    
	<table class='sortierbar' id='myTable'>
	<tr>
		<th>Datensatz-ID</th>
		<td>".$id_angebot."</td>
		<input type='hidden' name='id_angebot' value='".$id_angebot."'>
	</tr>	

	<tr>
		<th>Dauer [min]</th>
		<td>
			<input 
				type='text' 
				name='dauer_minuten' 
				id='dauer_minuten' 
				value='".$dauer_minuten."' 
				oninput='validateDauer()' 
				required>
			<div id='dauer_error' style='color:red; font-size:0.9em;'></div>
		</td>
	</tr>		

	<tr>
		<th>Bezeichnung</th>
		<td><input type='text' name='bezeichnung' value='".$bezeichnung."'></td>
	</tr>

	<tr>
		<th>Beschreibung</th>
		<td><textarea rows='8' name='beschreibung'>".$beschreibung."</textarea></td>
	</tr>

	<tr>
		<th>Kosten [CHF]</th>
		<td>
			<input 
				type='text' 
				name='kosten' 
				id='kosten' 
				value='".$kosten."' 
				oninput='validateKosten()' 
				required>
			<div id='kosten_error' style='color:red; font-size:0.9em;'></div>
		</td>
	</tr>

	<tr>
		<th>sichtbar</th>
		<td>
			<select class='form_input' name='aktiv'>
				<option value=''>Bitte wählen&nbsp;&nbsp;</option>";

        $selectedValue = $aktiv;
        $options = ["ja", "nein"];
        foreach ($options as $option)
        {
            $selected = ($selectedValue === $option) ? " selected" : "";
            echo "<option value=\"{$option}\"{$selected}>{$option}</option>";
        }

        echo"</select>
		</td>
	</tr>

	<tr>
		<td colspan='2' style='text-align: center;'>
			<input type='submit' name='submit_update' value='UPDATE' class='myButtonGross'>
			<br>
			<input type='submit' value='zurück zu zur Übersicht' class='myButtonGross'>
			<input type='hidden' name='filter' value='".$filter."'>
		</td>
	</tr>
	</table>
	</form>
	</div>
	<p>&nbsp;</p>";


	} 
	else
	{ 
		echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!";
		echo $go_back;
	}
}

# UPDATE

elseif(isset($_POST['submit_update']))
{

	# PostVariable

	$id_angebot    = $_POST['id_angebot'];
	$dauer_minuten = $_POST['dauer_minuten'];
	$bezeichnung   = $_POST['bezeichnung'];
	$beschreibung  = $_POST['beschreibung'];
	$kosten        = $_POST['kosten'];
    $aktiv         = $_POST['aktiv'];

	# SQL-Statement
	$sql = "
				UPDATE  `tbl_angebot` SET
						`dauer_minuten` = '$dauer_minuten',
						`bezeichnung`   = '$bezeichnung',
						`beschreibung`  = '$beschreibung',
						`kosten`        = '$kosten',
						`aktiv`         = '$aktiv'
				WHERE   `id_angebot`    = $id_angebot;";

	# UDATE auf der DB ausfuehren
	$database->query($sql);	

	// echo "SQL<br><pre>".$sql."</pre>";
	// echo var_dump($_POST);

	echo $action;

	echo "<div align='center'>

			<table>

			<tr>
			<td colspan='2' style='text-align: center;'>Der Datensatz<br><br><b>ID".$id_angebot."
			<br><br>wurde erfolgreich angepasst!<br></td>
			</tr>

			<tr>
			<td align='center'>zurück zum Datensatz<br> 
			<input type ='submit' name ='submit_edit' value='".$id_angebot."' class='myButtonKlein'>
			<input type ='hidden' name ='submit_auswahl' value='".$id_angebot."'>
			<br>
			</td>
			</tr>

			<tr>
			<td colspan='2'>
			<input 
			type='button' onClick=\"parent.location='".WB_URL."/",$landing_page."'\" 
			value='zurück zur Übersicht' 
			class='myButtonGross'>
			</tr>

			</table>
			</div>
			<p>&nbsp;</p>";

}
elseif(isset($_POST['submit_delete']))
{

	$id_angebot = $_POST['submit_auswahl'];

	if($id_angebot != "")
	{
		$sql = "DELETE FROM `tbl_angebot` WHERE `id_angebot` = '$id_angebot'";

		//	echo "SQL<br><pre>".$sql."</pre>";
		//	echo var_dump($_POST);

		$database->query($sql);

		echo "<table id='myTable'>
              <tr>
                <td style='text-align: center;'>
				Der Datensatz<br>ID = ".$id_angebot."<br>wurde erfolgreich gelöscht!
				</td>
			  </tr>
			   </table>";
		echo $go_back ; 
	}
	else
	{
		echo "<table id='myTable'><tr><td style='text-align: center;'>
					Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!
					</td></tr></table>";
		echo $go_back ; 
	}	
}	
else
{

	echo $action;

	$sql = "SELECT * FROM tbl_angebot ORDER BY 1;";
	$result = mysqli_query($mysqli,$sql);

	// echo "<br>SQL<br><pre>".$sql."</pre>";	

	if(mysqli_num_rows($result) > 0)
	{
		echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>

					<div class='table-scrollable'>

					<table id='myTableK' width='100%'>

					<table>
					<tr>
					<td align='center'>
					Durch Klicken auf einen Namen in der Kopfzeile kann die Tabelle auf- oder absteigend sortiert werden
					</td>
					</tr>
					<table>

					<table class='sortierbar' id='myTableNormal'>
					<thead>
					<tr>

					<th>ID</th>
					<th nowrap>Dauer [min]</th>
					<th>Bezeichnung</th>
					<th>Beschreibung</th>
					<th>sichtbar</th>
					<th nowrap>Kosten [CHF]</th>

					<th>Auswahl</th>
					<th>edit</th>
					<!-- <th>delete</th> -->

					</tr>

					</thead>
					<tbody>";

		while ($field = $result -> fetch_array(MYSQLI_ASSOC))
		{

			echo "<tr>
		<td valign='top'>".$field['id_angebot']."</td>

		<td valign='top' align='right'>".$field['dauer_minuten']."</td>

		<td valign='top'>".$field['bezeichnung']."</td>

		<td>".nl2br($field['beschreibung'])."</td>
		
		<td valign='top'>".$field['aktiv']."</td>

		<td  valign='top' align='right'>".number_format($field['kosten'],2)."</td>

		<td  valign='top'><input type='radio' name='submit_auswahl' value='" .$field['id_angebot']."'></td>

		<td  valign='top'><input type='submit' name='submit_edit' value='edit' class='myButtonKlein'></td> 


		</tr>";
		}
		echo "<tbody>
					</table>
		</div>
					<p>&nbsp;</p>";

	}
	else
	{
		echo "<table>
					<tr>
					<td>Es sind keine Mitteilungen vorhanden!</td>
					<tbody>
					</table>
					<p>&nbsp;</p>";		
	}	
}

echo "</div><tbody>";

