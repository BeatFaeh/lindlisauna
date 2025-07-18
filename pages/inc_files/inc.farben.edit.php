<?php

header('Content-Type: text/html; charset=UTF-8');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$bg_rubrik    = "style='background-color:#475c6a; line-height: 110%; padding: 5px; color:#FFFFFF;'";
$landing_page = "/pages/farben.php";
$go_back      = "<html><head><title></title>
		         <meta http-equiv='refresh' content='1; URL=".WB_URL."/".$landing_page."'></head>";
$action       = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";

# EDIT
if(isset($_POST['submit_edit']))
{
	if(isset($_POST['submit_auswahl']))
	{
		$id = $_POST['submit_auswahl'];
		$sql = "SELECT * FROM `type` WHERE `id` = '$id'";
		$query_fields = $database->query($sql);

		while($field = $query_fields->fetchRow(MYSQLI_ASSOC)) 
		{
			$id         = $field['id'];
			$title      = $field['title'];
			$color_code = $field['color_code'];
			$color      = $field['color'];
		}		

		echo $action;

		echo "

	<div style='text-align: center;'>
	<table class='sortierbar' id='myTable'>
	<tr>
		<th>Datensatz-ID</th>
		<td>".$id."</td>
		<input type='hidden' name='id' value='".$id."'>
	</tr>	

	<tr>
		<th>Bezeichnung</th>
		<td><input type='text' name='title' value='".$title."'></td>
	</tr>

	<tr>
		<th>Code</th>
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

	<tr><td colspan='2'>&nbsp;</td></tr>

	<tr>
		<td colspan='2' style='text-align: center;'>
			<input type='submit' name='submit_update' value='UPDATE' class='myButtonGross'>
			&nbsp;
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

	$id         = $_POST['id'];
	$title      = $_POST['title'];
	$color_code = $_POST['color_code'];
	$color      = $_POST['color'];

	# SQL-Statement
	$sql = "UPDATE  `type` SET
			`title`      = '$title',
			`color_code` = '$color_code',
			`color`      = '$color'
			WHERE `id`   = $id;";

	# UDATE auf der DB ausfuehren
	$database->query($sql);	

	// echo "SQL<br><pre>".$sql."</pre>";
	// echo var_dump($_POST);

	echo $action;

	echo "<div style='text-align: center;'>

			<table id='myTable'>

			<tr>
			<td colspan='2' style='text-align: center;'>Der Datensatz<br><br><b>ID".$id."
			<br><br>wurde erfolgreich angepasst!<br></td>
			</tr>

			<tr>
			<td style='text-align: center;'>zurück zum Datensatz<br> 
			<input type ='submit' name ='submit_edit' value='".$id."' class='myButtonKlein'>
			<input type ='hidden' name ='submit_auswahl' value='".$id."'>
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

	$id = $_POST['submit_auswahl'];

	echo "<div align ='center'>";
	
	if($id != "")
	{
		$sql = "DELETE FROM `type` WHERE `id` = '$id'";

		//	echo "SQL<br><pre>".$sql."</pre>";
		//	echo var_dump($_POST);

		$database->query($sql);

		echo "<table id='myTable'><tr><td style='text-align: center;'>
					Der Datensatz<br>ID = ".$id."<br>wurde erfolgreich gelöscht!
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
	
	echo "</div>";
}	
else
{

	echo $action;
	
	echo "<div style='text-align: center;'>";
	
	$sql = "SELECT * FROM type ORDER BY 2;";
	$result = mysqli_query($mysqli,$sql);

	if(mysqli_num_rows($result) > 0)
	{
		echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>
		

		<div class='table-scrollable'>

		<table id='myTableK' width='100%'>

		<table>
		<tr>
		<td style='text-align: center;'>
		Durch klicken auf einen Namen in der Kopfzeile kann die Tabelle auf- oder absteigend sortiert werden
		</td>
		</tr>
		<table>

		<table class='sortierbar' id='myTableNormal'>
		<thead>
		<tr>

		<th>ID</th>
		<th>Titel</th>
		<th>Code</th>
		<th>Name</th>
		<th>Farbe</th>

		<th>Auswahl</th>
		<th>edit</th>
		<th>delete</th>

		</tr>

		</thead>
		<tbody>";

		while ($field = $result -> fetch_array(MYSQLI_ASSOC))
		{

			echo "<tr>
		<td style='vertical-align: text-top;'>".$field['id']."</td>

		<td style='vertical-align: text-top;'>".$field['title']."</td>
		<td style='vertical-align: text-top;' style='text-align: center;'>".$field['color_code']."</td>

		<td style='vertical-align: text-top;'>".$field['color']."</td>

		<td style='vertical-align: text-top;' style='background-color:".$field['color_code'].";'>&nbsp;</td>

		<td style='text-align: center;'>
			<input type='radio' name='submit_auswahl' value='" .$field['id']."'>
		</td>

		<td style='text-align: center;'>
			<input type='submit' name='submit_edit' value='edit' class='myButtonKlein'>
		</td> 

		<td style='text-align: center;'>
			<input 
			type='submit' 
			name='submit_delete' 
			value='delete' 
			class='myButtonKlein' 
			onClick=\"return confirm('Wollen Sie den Datensatz = ".$field['id']." wirklich l&ouml;schen?')\">
		</td> 

		</tr>";
		}
		echo "<tbody>
					</table>
		</div>
					<p>&nbsp;</p>";
	}
	else
	{
		echo "<table class='sortierbar' id='myTable'>
					<tr>
					<td style='vertical-align: text-top;'>Es sind keine Mitteilungen vorhanden!</td>
					<tbody>
					</table>
					<p>&nbsp;</p>";		
	}	
}

echo "</table>
			</div>
			</div>
			<tbody>";

