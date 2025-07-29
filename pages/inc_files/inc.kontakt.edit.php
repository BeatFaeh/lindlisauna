<?php

// Datenbankverbindung
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$action  = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$go_back = "<html><head><meta http-equiv='refresh' content='1; URL=" . WB_URL . "/pages/kontakt/edit.php'></head>";

echo "<div align='center'>";

# EDIT
if(isset($_POST['submit_edit']))
{
	# Datensatz anzeigen
	if(isset($_POST['submit_auswahl']))
	{
		$id = $_POST['submit_auswahl'];
		$sql = "SELECT * FROM `tbl_kontakt` WHERE `kontakt_id` = '$id'";
		$query_fields = $database->query($sql);
		
		while($field = $query_fields->fetchRow(MYSQLI_ASSOC)) 
		{
			$kontakt_id         = $field['kontakt_id'];
			$kontakt_eintrag    = $field['kontakt_eintrag'];
			$kontakt_anrede     = $field['kontakt_anrede'];
			$kontakt_nname      = $field['kontakt_nname'];
			$kontakt_vname      = $field['kontakt_vname'];
			$kontakt_email      = $field['kontakt_email'];
			$kontakt_adresse    = $field['kontakt_adresse'];
			$kontakt_plz        = $field['kontakt_plz'];
			$kontakt_ort        = $field['kontakt_ort'];
			$kontakt_land       = $field['kontakt_land'];
			$kontakt_telefon    = $field['kontakt_telefon'];
			$kontakt_grund      = $field['kontakt_grund'];
			$kontakt_mitteilung = $field['kontakt_mitteilung'];
			$kontakt_bemerkung  = $field['kontakt_bemerkung'];
			$kontakt_erinnerung = $field['kontakt_erinnerung'];
			$kontakt_termin     = $field['kontakt_termin'];
			$ipadresse          = $field['ipadresse'];
		}		

	echo $action;
	echo "<table class='sortierbar' id='myTable'>
	<tr>
		<th>Datensatz-ID</th>
		<td>".$kontakt_id."</td>
		<input type='hidden' name='kontakt_id' value='".$kontakt_id."'>
	</tr>			
		
	<tr>
		<th>Erfassungsdatum</th>
		<td>".datumswandler_ger($kontakt_eintrag)."</td>
		<input type='hidden' name='kontakt_eintrag' value='".$kontakt_eintrag."'>
	</tr>

	<tr>
    <th>Anrede</th>
    <td>
        <select class='form_input' name='kontakt_anrede'>
            <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

            $selectedValue = $kontakt_anrede;
            $options = ["Frau", "Herr"];
            foreach ($options as $option)
												{
                $selected = ($selectedValue === $option) ? " selected" : "";
                echo "<option value=\"{$option}\"{$selected}>{$option}</option>";
            }

echo"</select>
    </td>
</tr>


	<tr>
		<th>Vornamen</th>
		<td><input type='text' name='kontakt_vname' value='".$kontakt_vname."'></td>
	</tr>
	
	<tr>
		<th>Nachnamen</th>
		<td><input type='text' name='kontakt_nname' value='".$kontakt_nname."'></td>
	</tr>

	<tr>
		<th>E-Mail</th>
		<td><input type='text' name='kontakt_email' value='".$kontakt_email."'></td>
	</tr>

	<tr>
		<th>Strasse | Hausnummer</th>
		<td><input type='text' name='kontakt_adresse' value='".$kontakt_adresse."'></td>
	</tr>

	<tr>
		<th>PLZ</th>
		<td><input type='text' name='kontakt_plz' value='".$kontakt_plz."'></td>
	</tr>

	<tr>
		<th>Ort</th>
		<td><input type='text' name='kontakt_ort' value='".$kontakt_ort."'></td>
	</tr>

	<tr><th>Land</th>";
	$selectedValue = $kontakt_land;
	$myArray = array(
		"Schweiz",
		"Deutschland",
		"Österreich",
		"Italien",
		"Frankreich",
		"andere"
	);
	echo "<td>
	<select class='form_input' name='kontakt_land' value='".$kontakt_land."'>
	<option value=''>Bitte wählen&nbsp;&nbsp;</option>";

	foreach($myArray as $element)
	{
		$isSelected = ($selectedValue == $element) ? " selected" : "";
		echo "<option value=\"".htmlentities($element, ENT_QUOTES)."\"$isSelected>".htmlentities($element)."</option>\n";
	}
	echo "</select></td></tr>

	<tr>
		<th>Telefon</th>
		<td><input type='text' name='kontakt_telefon' value='".$kontakt_telefon."'></td>
	</tr>

	<tr>
	<th>Kontaktgrund</th>";

	$selectedValue =$kontakt_grund;
	$myArray = array(
		"allgemeine Frage",
		"Terminverschiebung / -absage",
		"Beschwerde / Kritik",
		"Beratung zur Behandlung",
		"Rückmeldung zur Behandlung",
		"Anfrage für Hausbesuch",
		"Nachfrage zu Preisen / Angeboten",
		"Sonstiges"
	);
	echo "<td>
	<select class='form_input' name='kontakt_grund' value='".$kontakt_grund."'>
	<option value=''>Bitte wählen&nbsp;&nbsp;</option>";

	foreach($myArray as $element)
	{
		$isSelected = ($selectedValue == $element) ? " selected" : "";
		echo "<option value=\"".htmlentities($element, ENT_QUOTES)."\"$isSelected>".htmlentities($element)."</option>\n";
	}
	echo "</select></td></tr>

	<tr>
		<th>Mitteilung</th>
		<td><textarea rows='9' name='kontakt_mitteilung'>".$kontakt_mitteilung."</textarea></td>
	</tr>

	<tr><td colspan='2' style='text-align: center;'>Einträge zur Erinnerung</td></tr>
	
	<tr>
		<th>Bemerkung</th>
		<td><textarea rows='9' name='kontakt_bemerkung'>".$kontakt_bemerkung."</textarea></td>
	</tr>		

<tr>
    <th>Erinnerung</th>
    <td>
        <select class='form_input' name='kontakt_erinnerung'>
            <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

            $selectedValue = $kontakt_erinnerung;
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
		<th>Erinnerung Termin</th>
		<td>";
		if(empty($kontakt_termin))
		{
			echo "<input type='text' id='datepicker'  name='kontakt_termin'>";
		}   
		else
		{
			// echo "<input type='text' id='datepicker'  name='kontakt_termin' value='".datumswandler_ger($kontakt_termin)."'>";
            echo "<input style='width: 150px;' id='arrival' type='date' name='kontakt_termin' value='" .$kontakt_termin. "'>";
		}				   
	
	echo "</td>
	</tr>		

	<tr>
		<th>IP-Adresse</th>
		<td>".$ipadresse."</td>
	</tr>		

	<tr>
		<td colspan='2' style='text-align: center;'>&nbsp;</td>
	</tr>
	
	<tr>
		<td colspan='2' style='text-align: center;'>
			<input type='submit' name='submit_update' value='UPDATE' class='myButtonGross'>
			<br>
		<input type='button' onClick=\"parent.location='".WB_URL."/pages/kontakt/edit.php'\" value='zurück zur Übersicht'  class='myButtonGross'>
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
	$kontakt_id         = $_POST['kontakt_id'];
	$kontakt_anrede     = $_POST['kontakt_anrede'];
	$kontakt_nname      = $_POST['kontakt_nname'];
	$kontakt_vname      = $_POST['kontakt_vname'];
	$kontakt_email      = $_POST['kontakt_email'];
	$kontakt_adresse    = $_POST['kontakt_adresse'];
	$kontakt_plz        = $_POST['kontakt_plz'];
	$kontakt_ort        = $_POST['kontakt_ort'];
	$kontakt_land       = $_POST['kontakt_land'];
	$kontakt_telefon    = $_POST['kontakt_telefon'];
	$kontakt_grund      = !empty($_POST['kontakt_grund']) ? "'{$_POST['kontakt_grund']}'" : 'NULL';
	$kontakt_mitteilung = !empty($_POST['kontakt_mitteilung']) ? "'{$_POST['kontakt_mitteilung']}'" : 'NULL';
	$kontakt_bemerkung  = !empty($_POST['kontakt_bemerkung']) ? "'{$_POST['kontakt_bemerkung']}'" : 'NULL';
	$kontakt_erinnerung = !empty($_POST['kontakt_erinnerung']) ? "'{$_POST['kontakt_erinnerung']}'" : 'NULL';
	$kontakt_termin     = !empty($_POST['kontakt_termin']) ? "'{$_POST['kontakt_termin']}'" : 'NULL';

	if(empty($kontakt_termin))
	{
		$kontakt_termin_sql = NULL;
	}
	else
	{	
		$kontakt_termin_sql = $_POST['kontakt_termin'];
	}		
	
	# SQL-Statement
	$sql = "
	UPDATE `tbl_kontakt` SET
	`kontakt_anrede`     = '$kontakt_anrede',
	`kontakt_nname`      = '$kontakt_nname',
	`kontakt_vname`      = '$kontakt_vname',
	`kontakt_email`      = '$kontakt_email',
	`kontakt_adresse`    = '$kontakt_adresse',
	`kontakt_plz`        = '$kontakt_plz',
	`kontakt_ort`        = '$kontakt_ort',
	`kontakt_land`       = '$kontakt_land',
	`kontakt_telefon`    = '$kontakt_telefon',
	`kontakt_grund`      = $kontakt_grund,
	`kontakt_mitteilung` = $kontakt_mitteilung,
	`kontakt_bemerkung`  = $kontakt_bemerkung,
	`kontakt_erinnerung` = $kontakt_erinnerung,
	`kontakt_termin`     = '$kontakt_termin_sql'
	WHERE `kontakt_id`   = '$kontakt_id';";
	
	$database->query($sql);
	
	/*
	echo "SQL<br><pre>".$sql."</pre>";
	echo "<p>&nbsp;</p>Kontakttermin  = ".$kontakt_termin;
	echo "<p>&nbsp;</p>Kontakttermin SQL = ".$kontakt_termin_sql;
	echo "<p>&nbsp;</p>Kontakttermin POST = ".$_POST['kontakt_termin'];
	echo "<p>&nbsp;</p>Kontakttermin eng = ".datumswandler_eng($_POST['kontakt_termin']);	
	echo var_dump($_POST);
	*/
	
	echo $action;

	echo "<div style='text-align: center;'>

	<table id='myTable'>

	<tr>
	<td  colspan='2' style='text-align: center;'>Der Datensatz<br><br><b>ID".$kontakt_id."
	<br>".str_replace("'","",$kontakt_vname)." ".str_replace("'","",$kontakt_nname)."</b>
	<br><br>wurde erfolgreich angepasst!<br></td>
	</tr>

	<tr>
        <td style='text-align: center;'>zurück zum Datensatz<br> 
            <input type ='submit' name ='submit_edit' value='".$kontakt_id."' class='myButtonKlein'>
            <input type ='hidden' name ='submit_auswahl' value='".$kontakt_id."'>
        <br>
        </td>
	</tr>

	<tr>
        <td colspan='2' style='text-align: center;'>
            <input 
            type='button' onClick=\"parent.location='".WB_URL."/pages/kontakt/edit.php'\" 
            value='zurück zur Übersicht' 
            class='myButtonGross'>
        </td>
	</tr>
	</table>
	<p>&nbsp;</p>";
}
# Delete-Funktion
elseif (isset($_POST['submit_delete'])) 
{
	if(isset($_POST['submit_auswahl']))
	{    
		$kontakt_id = intval($_POST['submit_auswahl']);
		$sql = "DELETE FROM `tbl_kontakt` WHERE `kontakt_id` = '$kontakt_id'";
		$mysqli->query($sql);
		echo $go_back;
		echo "<div style='color: red; font-weight: bold;'>Der Datensatz $kontakt_id wurde erfolgreich gelöscht.</div>";
	}
	else
	{
		echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!";
		echo $go_back;		
	}
}
else
{	
	# Filter- & Pagingparameter holen
	$search = isset($_GET['search']) ? trim($_GET['search']) : '';
	$limit = isset($_GET['limit']) && $_GET['limit'] !== 'all' ? intval($_GET['limit']) : null;
	$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
	$offset = ($limit) ? ($page - 1) * $limit : 0;

	// SQL für Zählen + Filter
	$filter_sql = "";
	if (!empty($search)) {
		$escaped = $mysqli->real_escape_string($search);
		$filter_sql = " WHERE 
			kontakt_id LIKE '%$escaped%' OR
			kontakt_anrede LIKE '%$escaped%' OR
			kontakt_vname LIKE '%$escaped%' OR
			kontakt_nname LIKE '%$escaped%' OR
			kontakt_adresse LIKE '%$escaped%' OR
			kontakt_plz LIKE '%$escaped%' OR
			kontakt_ort LIKE '%$escaped%' OR
			kontakt_land LIKE '%$escaped%' OR
			kontakt_telefon LIKE '%$escaped%' OR
			kontakt_email LIKE '%$escaped%' OR
			kontakt_bemerkung LIKE '%$escaped%' OR
			kontakt_mitteilung LIKE '%$escaped%'";
	}

	$count_sql = "SELECT COUNT(*) AS total FROM tbl_kontakt" . $filter_sql;
	$total_result = $mysqli->query($count_sql);
	$total_row = $total_result->fetch_assoc();
	$total_entries = $total_row['total'];
	$total_pages = ($limit) ? ceil($total_entries / $limit) : 1;

	# Hauptabfrage
	$data_sql = "SELECT * FROM tbl_kontakt";
	if ($filter_sql) $data_sql .= $filter_sql;
	$data_sql .= " ORDER BY kontakt_id DESC";
	if ($limit) $data_sql .= " LIMIT $limit OFFSET $offset";

	$result = $mysqli->query($data_sql);

	# HTML-Ausgabe Start

	echo "<!DOCTYPE html>
	<html lang='de'>

	<head>
	<meta charset='UTF-8'>
	<title>Kontaktliste</title>
	<style>
		body { font-family: Arial, sans-serif; background: #FFFFFF; }
		table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
		th, td { border: 1px solid #ccc; padding: 10px; text-align: left; vertical-align: top; }
		th { background-color: #c0c0c0; color: white; }
		.center { text-align: center; }
		.pagination a { margin: 0 5px; padding: 5px 10px; background: #ddd; color: #333; text-decoration: none; border-radius: 4px; }
		.pagination a.active { font-weight: bold; background: #475c6a; color: white; }
		.filter-form { background: white; padding: 15px; border: 1px solid #ccc; border-radius: 10px; }
		.filter-form input[type=text], .filter-form select { padding: 5px; margin-right: 10px; }
		.myButtonKlein { padding: 5px 10px; }

		/* Zebra-Streifen für Tabellenzeilen */
		tbody tr:nth-child(odd) { background-color: #f9f9f9; }
		tbody tr:nth-child(even) { background-color: #ffffff; }
	</style>
	</head>
	<body>

	<h2>Kontaktliste</h2>

	<form method='GET' class='filter-form'>
		<label class='myLabel'>Volltextsuche:
			<input type='text' name='search' value='" . htmlspecialchars($search, ENT_QUOTES) . "'>
		</label>
		<label class='myLabel'>Anzahl:
			<select name='limit'>
				<option value='25' " . ($limit == 25 ? "selected" : "") . ">25</option>
				<option value='50' " . ($limit == 50 ? "selected" : "") . ">50</option>
				<option value='100' " . ($limit == 100 ? "selected" : "") . ">100</option>
				<option value='all' " . (is_null($limit) ? "selected" : "") . ">Alle</option>
			</select>
		</label>
		<input type='submit' value='Anwenden' class='myButtonKlein'>
	</form>";

	if ($result->num_rows > 0) 
	{
		echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>
		<div class='table-scrollable'>
		<table class='sortierbar' id='myTableNormal'>
		<thead>
			<tr>
				<th>ID</th>
				<th>Eintrag</th>
				<th>Kontakt</th>
				<th>Mitteilung</th>
				<th>Erinnerung</th>
				<th>Bemerkung</th>
				<th>Termin</th>
				<th>Auswahl</th>
				<th>edit</th>
				<th>delete</th>
			</tr>
		</thead>
		<tbody>";

		while ($row = $result->fetch_assoc())
		{
			if (empty($row['kontakt_termin'])) 
			{
				$termin = NULL;
			}
			elseif ($row['kontakt_termin'] === '0000-00-00')
			{
				$termin = NULL;
			}
			else
			{
				$termin = datumswandler_ger($row['kontakt_termin']);
			}
			
			if (empty($row['kontakt_erinnerung'])) 
			{
				$erinnerung = NULL;
			}
			else
			{
				$erinnerung = $row['kontakt_erinnerung'];
			}  			
			
			echo "<tr>
				<td>{$row['kontakt_id']}</td>
				<td>".datumswandler_ger($row['kontakt_eintrag'])."</td>
				<td>
					{$row['kontakt_anrede']}<br>
					{$row['kontakt_vname']} {$row['kontakt_nname']}<br>
					{$row['kontakt_adresse']}<br>
					{$row['kontakt_plz']} {$row['kontakt_ort']}<br>
					{$row['kontakt_land']}<br>
					{$row['kontakt_telefon']}<br>
					{$row['kontakt_email']}
				</td>
				<td>".nl2br($row['kontakt_mitteilung'])."</td>
				<td>".$erinnerung."</td>
				<td>".nl2br($row['kontakt_bemerkung'])."</td>
				<td>".$termin."</td>


				<td style='text-align: center;'>
					<input type='radio' name='submit_auswahl' value='{$row['kontakt_id']}'>
				</td>

				<td style='text-align: center;'>
					<input type='submit' name='submit_edit' value='edit' class='myButtonKlein'>
				</td> 

				<td class='center'>
					<input type='submit' name='submit_delete' value='löschen' class='myButtonKlein'
					onclick=\"return confirm('Wirklich löschen: Datensatz {$row['kontakt_id']}?')\">
				</td>
			</tr>";
		}

		echo "</tbody>
		</table>
		</form>
		</div>";
	} else {
		echo "<p>Keine Datensätze gefunden.</p>";
	}

	# PAGINATION
	if ($limit) {
		echo "<div class='pagination center'>";
		if ($page > 1) {
			echo "<a href='?page=" . ($page - 1) . "&limit=$limit&search=" . urlencode($search) . "'>&laquo; Zurück</a>";
		}
		for ($i = 1; $i <= $total_pages; $i++) {
			$active = ($i == $page) ? "active" : "";
			echo "<a class='$active' href='?page=$i&limit=$limit&search=" . urlencode($search) . "'>$i</a>";
		}
		if ($page < $total_pages) {
			echo "<a href='?page=" . ($page + 1) . "&limit=$limit&search=" . urlencode($search) . "'>Weiter &raquo;</a>";
		}
		echo "</div>";
	}

	echo "<p>&nbsp;</p></body></html>";
}
echo "</div>";
?>