<?php


/*

<td style='text-align: center;'><input type='submit' name='submit_delete' value='delete' class='myButtonKlein'
onClick=\"return confirm('Wollen Sie den Datensatz = ".$field['id_ort']." wirklich l&ouml;schen?')\"></td>

*/

header('Content-Type: text/html; charset=UTF-8');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$bg_rubrik = "style='background-color:#475c6a; line-height: 110%; padding: 5px; color:#FFFFFF;'";
$landing_page = "pages/zeit.php";
$go_back = "<html><head><title></title>
		<meta http-equiv='refresh' content='1; URL=" . WB_URL . "/" . $landing_page . "'></head>";
$action = "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>";
$background_gesperrt = " style='text-align: right;background-color: #D8D8D8;' ";

echo "<div align='center'>";

# EDIT
if (isset($_POST['submit_edit'])) {
    if (isset($_POST['submit_auswahl'])) {
        $id_zeit = $_POST['submit_auswahl'];

        $sql = "SELECT * FROM `tbl_zeit` WHERE `id_zeit` = '$id_zeit';";
        $query_fields = $database->query($sql);

        while ($field = $query_fields->fetchRow(MYSQLI_ASSOC)) {
            $id_zeit = $field['id_zeit'];
            $zeit = $field['zeit'];
            $aktiv = $field['aktiv'];
        }

        echo $action;

        echo "
	<table id='myTableNormal'>
	<tr>
		<th>Datensatz-ID</th>
		<td>" . $id_zeit . "</td>
		<input type='hidden' name='id_zeit' value='" . $id_zeit . "'>
	</tr>	

<!-- ZEIT -->

	<tr>
		<th>Zeit</th>
		<td>".$zeit."</td>
	</tr>

<!-- AKTIV --> 

	<tr>
		<th>sichtbar</th>
		<td style='width: 160px;'>
			<select class='form_input' name='aktiv'>
				<option value=''>Bitte wählen&nbsp;&nbsp;</option>";

        $selectedValue = $aktiv;
        $options = ["ja", "nein"];

        foreach ($options as $option) {
            $selected = ($selectedValue === $option) ? " selected" : "";
            echo "<option value=\"{$option}\"{$selected}>{$option}</option>";
        }

        echo "</select>
		</td>
	</tr>


	<tr>
		<td colspan='2' style='text-align: center;'>
			<input type='submit' name='submit_update' value='UPDATE' class='myButtonGross'>
			<br>
			<input type='submit' value='zurück zu zur Übersicht' class='myButtonGross'>
			<input type='hidden' name='filter' value='" . $filter . "'>
		</td>
	</tr>
	</table>
	</form>
	<p>&nbsp;</p>";


    } else {
        echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!";
        echo $go_back;
    }
} # UPDATE

elseif (isset($_POST['submit_update'])) {

    # PostVariable

    $id_zeit = $_POST['id_zeit'];
    $aktiv   = $_POST['aktiv'];

    # SQL-Statement
    $sql = "UPDATE  `tbl_zeit` SET
			`aktiv`   = '$aktiv'
			WHERE   `id_zeit` = '$id_zeit';";
    # UDATE auf der DB ausfuehren
    $database->query($sql);

    // echo "SQL<br><pre>".$sql."</pre>";
    // echo var_dump($_POST);

    echo $action;

    echo "<div style='text-align: center;'>

			<table id='myTable'>

			<tr>
			<td colspan='2' style='text-align: center;'>Der Datensatz<br><br><b>ID" . $id_zeit . "
			<br><br>wurde erfolgreich angepasst!<br></td>
			</tr>

			<tr>
			<td style='text-align: center;'>zurück zum Datensatz<br> 
			<input type ='submit' name ='submit_edit' value='" . $id_zeit . "' class='myButtonKlein'>
			<input type ='hidden' name ='submit_auswahl' value='" . $id_zeit. "'>
			<br>
			</td>
			</tr>

			<tr>
			<td colspan='2'>
			<input 
			type='button' onClick=\"parent.location='" . WB_URL . "/", $landing_page . "'\" 
			value='zurück zur Übersicht' 
			class='myButtonGross'>
			</tr>

			</table>
			<p>&nbsp;</p>";
} else {

    echo $action;

    $sql = "SELECT * FROM tbl_zeit ORDER BY `zeit`;";
    $result = mysqli_query($mysqli, $sql);

    // echo "<br>SQL<br><pre>".$sql."</pre>";

    if (mysqli_num_rows($result) > 0) {
        echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>


                    <div align='center'>Durch klicken auf einen Namen in der Kopfzeile,<br>kann die Tabelle auf- oder absteigend sortiert werden</div>

					<div class='table-scrollable'>
					
					<table id='myTableK' width='100%'>

						<table class='sortierbar' id='myTableNormal'>
					<thead>
					<tr>

					<th>ID</th>
					<th>Zeit</th>
					<th>sichtbar</th>

					<th>Auswahl</th>
					<th>edit</th>
					<!-- <th>delete</th> -->

					</tr>

					</thead>
					<tbody>";

        while ($field = $result->fetch_array(MYSQLI_ASSOC)) {

        echo "<tr>
            <td style='vertical-align: text-top;'>" . $field['id_zeit'] . "</td>
            <td style='vertical-align: text-top;'>" . $field['zeit'] . "</td>
            <td style='vertical-align: text-top; text-align: center;'>" . $field['aktiv'] . "</td>
            <td style='text-align: center;'><input type='radio' name='submit_auswahl' value='" . $field['id_zeit'] . "'></td>
            <td style='text-align: center;'><input type='submit' name='submit_edit' value='edit' class='myButtonKlein'></td> 
		</tr>";
        }
        echo "<tbody>
					</table>
		</div>
					<p>&nbsp;</p>";
    } else {
        echo "<table class='sortierbar' id='myTable'>
					<tr>
					<td style='vertical-align: text-top;'>Es sind keine Zeiten vorhanden!</td>
					<tbody>
					</table>
					<p>&nbsp;</p>";
    }
}

echo "</div>
<tbody>";


