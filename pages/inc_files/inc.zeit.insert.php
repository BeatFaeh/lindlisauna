<?php

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$action  = "<form action='" .htmlspecialchars($_SERVER['PHP_SELF'])."' method='POST'>";
$go_back = "<html><head><title></title><meta http-equiv='refresh' content='1; URL=".WB_URL."/pages/zeit.php'></head>";

$error = null;

if(isset($_POST['submit_insert']))
{
    $error = false;

    $error_zeit = NULL;
    if(empty($_POST['zeit']))
    {
        $error = true;
        $error_zeit = "<div style='margin-top: 4px;color: #FF0000;'>Bitte wählen Sie die Zeit aus</div>";
    }

    $error_aktiv = NULL;
    if(empty($_POST['aktiv']))
    {
        $error = true;
        $error_aktiv = "<div style='margin-top: 4px;color: #FF0000;'>Bitte legen Sie fest, ob die Zeit sichtbar sein soll!</div>";
    }

}
if($error === false)
{
    $zeit  = $_POST['zeit'];
    $aktiv = $_POST['aktiv'];

    # Eintrag in tbl_ort
    $sql = "INSERT INTO `tbl_zeit`
	(
		`id_zeit`,
		`zeit`,
		`aktiv`
	) 
	VALUES 
	(
	NULL,
		'$zeit',
		'$aktiv'
	)";

    $mysqli->query($sql);

    # max-id anfuegen


    echo "Der Eintrag wurde erfolgreich erfasst!
          <br>
          <br>Zeit = ".$zeit."
          <br>sichtbar = ".$aktiv." ";
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
		<td><span style='color: #FF0000;'>Sie haben nicht alle erforderlichen Daten angegeben!</span>
		</td>
		</tr>
		</table>
		<br>";
    }

    echo "<table>

	<tr><td colspan='2'>&nbsp;</td></tr>
    
	<tr>
	    <td colspan='2'>Neue Zeit erfassen</td>
	</tr>
	
	<tr><td colspan='2'>&nbsp;</td></tr>";

# Zeit

    echo "
	<tr>
        <td style='vertical-align: text-top;'><b>Zeit</b>&nbsp;&nbsp;&nbsp;&nbsp;<td>";

        # Zeiten, die noch nicht erfasst sind beim Buchen
        $sql= "SELECT `tbl_zeit_alle`.`zeit` FROM `tbl_zeit_alle` 
               LEFT JOIN `tbl_zeit`
               ON `tbl_zeit_alle`.`zeit` = `tbl_zeit`.`zeit`
               WHERE `tbl_zeit`.`zeit` IS NULL;";
        $query_fields = $database->query($sql);

        # Hol den aktuellen Wert von POST
        $selected_id = isset($_POST['zeit']) ? $_POST['zeit'] : '';

        echo "<select style='width: 160px;' name='zeit' ";

        if(isset($errorFelder['zeit']) OR $error_zeit > "")
        {
            echo "class='select_error'>";
        }
        else
        {
            echo " >";
        }

        echo "<option value=''>Bitte wählen</option>";

              while ($field = $query_fields->fetchRow(MYSQLI_ASSOC))
              {
                  $auswahl = ($field['zeit'] == $selected_id) ? "selected" : "";
                  echo "<option style='text-align: center;' value='".$field['zeit']."' $auswahl>".$field['zeit']."</option>";
              }

        echo "</select>
            ".$error_zeit."
          </td>
	</tr>";

    echo "<tr><td colspan='2'>&nbsp;</td></tr>";

# aktiv

    echo "
    <tr>
        <td style='vertical-align: text-top;'><b>aktiv</b></td>
        <td>
        <select style='width: 160px;' name='aktiv' value='".htmlentities($_POST['aktiv'])."'";

        if(isset($errorFelder['aktiv']) OR $error_aktiv > "")
        {
            echo "class='select_error'>";
        }
        else
        {
            echo " >";
        }

        echo "<option value=''>Bitte wählen&nbsp;&nbsp;</option>";

        $selectedValue = htmlentities($_POST['aktiv']);
        $myArray = array("ja","nein");

        foreach($myArray AS $element)
        {
            if($selectedValue == $element)
            {
                echo "<option value=".$element." selected>".$element."</option>";
            }
            else
            {
                echo "<option value=".$element.">".$element."</option>";
            }
        }

        echo "</select>";
    echo $error_aktiv."</td>
    </tr>";


    echo "<tr><td colspan='2'>&nbsp;</td></tr>";


# Formular Inhalt senden

    echo "<tr><td colspan='2'>&nbsp;</td></tr>
	
	<tr>
        <td colspan='2' style='text-align: center;'>
            <input class='myButtonGross' type='submit' name='submit_insert' value='senden' style='width:50%'>
        </td>
	</tr>
	
	<tr><td colspan='2'>&nbsp;</td></tr>
	</table></form>";
}
echo "</div><p>&nbsp;</p>";


// echo "<pre>".$raum_sql."</pre>";
// echo var_dump($_POST);
