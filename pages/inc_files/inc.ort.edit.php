<?php

/*

<td style='text-align: center;'><input type='submit' name='submit_delete' value='delete' class='myButtonKlein' 
onClick=\"return confirm('Wollen Sie den Datensatz = ".$field['id_ort']." wirklich l&ouml;schen?')\"></td> 

*/

header('Content-Type: text/html; charset=UTF-8');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$bg_rubrik = "style='background-color:#475c6a; line-height: 110%; padding: 5px; color:#FFFFFF;'";
$landing_page = "pages/ort.php";
$go_back      = "<html><head><title></title>
		<meta http-equiv='refresh' content='1; URL=".WB_URL."/".$landing_page."'></head>";
$action = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$background_gesperrt = " style='text-align: right;background-color: #D8D8D8;' ";

echo "<div align='center'>";

# EDIT
if(isset($_POST['submit_edit']))
{
	if(isset($_POST['submit_auswahl']))
	{
		$id_ort = $_POST['submit_auswahl'];
		$sql = "SELECT * FROM `tbl_ort` WHERE `id_ort` = '$id_ort';";
		$query_fields = $database->query($sql);

		while($field = $query_fields->fetchRow(MYSQLI_ASSOC)) 
		{
			$id_ort     = $field['id_ort'];
			$ort        = $field['ort'];
			$lageplan   = $field['lageplan'];
			$kosten     = $field['kosten'];
			$color      = $field['color'];
			$color_code = $field['color_code'];
			$aktiv      = $field['aktiv'];
		}		

		echo $action;

echo "
	<table class='sortierbar' id='myTable'>
	<tr>
		<th>Datensatz-ID</th>
		<td>".$id_ort."</td>
		<input type='hidden' name='id_ort' value='".$id_ort."'>
	</tr>	

	<tr>
		<th>Ort</th>
		<td><input type='text' name='ort' value='".$ort."'></td>
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
		<th>Lageplan</th>
		<td><textarea rows='8' name='lageplan'>".$lageplan."</textarea></td>
	</tr>

	<tr>
		<th>Kosten [CHF]&nbsp;&nbsp;</th>
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
		<th>Farbcode</th>
		<td><input type='text'name='color_code' value='".$color_code."'></td>
	</tr>

	<tr>
		<th>Farbe</th>
		<td><input type='text'name='color' value='".$color."'></td>
	</tr>

	<tr>
		<th>Farbton</th>
		<td style='background-color:".$color_code.";';>&nbsp;</td>
	</tr>

	<tr>
		<th>Farbauswahl</th>
		<td><a href='https://www.w3schools.com/colors/colors_picker.asp' target='_blank'>Link</a></td>
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

	$id_ort     = $_POST['id_ort'];
	$ort        = $_POST['ort'];
	$lageplan   = $_POST['lageplan'];
	$kosten     = $_POST['kosten'];
	$color      = $_POST['color'];
	$color_code = $_POST['color_code'];
	$aktiv      = $_POST['aktiv'];

	# SQL-Statement
	$sql = "
				UPDATE  `tbl_ort` SET
						`ort`        = '$ort',
						`lageplan`   = '$lageplan',
						`kosten`     = '$kosten',
						`color`      = '$color',
	                    `color_code` = '$color_code',
						`aktiv`      = '$aktiv'
				WHERE   `id_ort`     = '$id_ort';";

	# UDATE auf der DB ausfuehren
	$database->query($sql);	

	// echo "SQL<br><pre>".$sql."</pre>";
	// echo var_dump($_POST);

	echo $action;

	echo "<div style='text-align: center;'>

			<table id='myTable'>

			<tr>
			<td colspan='2' style='text-align: center;'>Der Datensatz<br><br><b>ID".$id_ort."
			<br><br>wurde erfolgreich angepasst!<br></td>
			</tr>

			<tr>
			<td style='text-align: center;'>zurück zum Datensatz<br> 
			<input type ='submit' name ='submit_edit' value='".$id_ort."' class='myButtonKlein'>
			<input type ='hidden' name ='submit_auswahl' value='".$id_ort."'>
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
			<p>&nbsp;</p>";

}
elseif(isset($_POST['submit_delete']))
{

	$id_ort = $_POST['submit_auswahl'];

	if($id_ort != "")
	{
		$sql = "DELETE FROM `tbl_ort` WHERE `id_ort` = '$id_ort';";

		//	echo "SQL<br><pre>".$sql."</pre>";
		//	echo var_dump($_POST);

		$database->query($sql);

		echo "<table id='myTable'><tr><td style='text-align: center;'>
					Der Datensatz<br>ID = ".$id_ort."<br>wurde erfolgreich gelöscht!
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
else {
    echo "<div style='display: flex; justify-content: center;'>";

    $sql = "SELECT * FROM tbl_ort ORDER BY 1;";
    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
        echo "<div class='table-scrollable'>";
        echo "<table class='sortierbar' id='myTableNormal' style='margin: 0 auto; border: 1px solid #ccc;'>";

        echo "<thead>
            <tr>
                <th>ID</th>
                <th>Ort</th>
                <th>Farbe</th>
                <th>Farbcode</th>
                <th>Lageplan</th>
                <th>Kosten [CHF]</th>
                <th>Sichtbar</th>
                <th>Auswahl</th>
                <th>Edit</th>
            </tr>
          </thead>
          <tbody>";

        while ($field = $result->fetch_array(MYSQLI_ASSOC)) {
            echo "<tr>
                <td>" . $field['id_ort'] . "</td>
                <td>" . $field['ort'] . "</td>
                <td>" . $field['color'] . "</td>
                <td style='background-color:" . $field['color_code'] . ";'>&nbsp;</td>
                <td><a href='" . WB_URL . "/media/lageplan/" . $field['lageplan'] . "' target='_blank'>" . $field['lageplan'] . "</a></td>
                <td style='text-align: right;'>" . number_format($field['kosten'], 2) . "</td>
                <td style='text-align: center;'>" . $field['aktiv'] . "</td>
                <td style='text-align: center;'><input type='radio' name='submit_auswahl' value='" . $field['id_ort'] . "'></td>
                <td style='text-align: center;'><input type='submit' name='submit_edit' value='edit' class='myButtonKlein'></td>
              </tr>";
        }

        echo "</tbody></table></div></form>";
    } else {
        echo "<div>Keine Daten gefunden.</div>";
    }

    echo "</div><p>&nbsp;</p>";
}


