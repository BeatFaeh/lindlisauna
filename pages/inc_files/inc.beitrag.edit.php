<?php
echo "<script src='" . WB_URL . "/include/tablesort/tablesort.js' type='text/javascript'></script>";

$action  = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$go_back = "<html><head><meta http-equiv='refresh' content='1; URL=" . WB_URL . "/pages/kontakt/edit.php'></head>";

# Datenbankverbindung
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8mb4");

# kleine Helper-Funktion gegen XSS
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

echo "<div align='center'>";

# EDIT
if(isset($_POST['submit_edit']))
{

    # Datensatz anzeigen
    if(isset($_POST['submit_auswahl']))
    {
        $id = $_POST['submit_auswahl'];
        $sql = "SELECT
                m.`mitgliederbeitrag_id`,            
                m.`kontakt_id`,
                k.`kontakt_vname`,
                k.`kontakt_nname`,
                k.`kontakt_email`,
                m.`jahr`,
                m.`betrag`,
                m.`bezahlt`,
                m.`datum_bezahlt`,
                m.`bemerkung`
                FROM `tbl_mitgliederbeitrag` m
                JOIN `tbl_kontakt` k
                ON k.`kontakt_id` = m.`kontakt_id`
                WHERE m.`mitgliederbeitrag_id` = '$id;'";

        $query_fields = $database->query($sql);

        while($field = $query_fields->fetchRow(MYSQLI_ASSOC))
        {
            $mitgliederbeitrag_id = $field['mitgliederbeitrag_id'];
            $kontakt_id           = $field['kontakt_id'];
            $kontakt_vname        = $field['kontakt_vname'];
            $kontakt_nname        = $field['kontakt_nname'];
            $kontakt_email        = $field['kontakt_email'];
            $betrag               = $field['betrag'];
            $jahr                 = $field['jahr'];
            $bezahlt              = $field['bezahlt'];
            $datum_bezahlt        = $field['datum_bezahlt'];
            $bemerkung            = $field['bemerkung'];
        }

        echo $action;

        echo "<table class='sortierbar' id='myTable'>
	    <tr>
		<th>Mitgliederbeitrag-ID&nbsp;</th>
		<td>".$mitgliederbeitrag_id."</td>
		<input type='hidden' name='kontakt_id' value='".$mitgliederbeitrag_id."'>
	    </tr>
	    
	    <tr>
		<th>Mitglied-ID</th>
		<td>".$kontakt_id."</td>
	    </tr>

	    <tr>
		<th>Vornamen</th>
		<td>".$kontakt_vname."</td>
	    </tr>

	    <tr>
		<th>Nachnamen</th>
		<td>".$kontakt_nname."</td>
	    </tr>	    

	    <tr>
		<th>E-Mail</th>
		<td>".$kontakt_email."</td>
	    </tr>	    

	    <tr>
		<th>Beitragsjahr</th>
		<td>".$jahr."</td>
	    </tr>		    
	    
	    <tr>
		<th>Mitgliederbeitrag&nbsp;</th>
		<td><input type='text' name='betrag' value='".$betrag."'></td>
	    </tr>
		    
	    <tr>
		<th>bezahlt</th>";

        $selectedValue = $bezahlt;
        $myArray = array(
            "ja",
            "nein"
        );

        echo "<td>
	    <select class='form_input' name='bezahlt' value='".$bezahlt."'>
	    <option value=''>Bitte wählen&nbsp;&nbsp;</option>";

        foreach($myArray as $element)
        {
            $isSelected = ($selectedValue == $element) ? " selected" : "";
            echo "<option value=\"".htmlentities($element, ENT_QUOTES)."\"$isSelected>".htmlentities($element)."</option>\n";
        }
        echo "</select></td></tr>	    
	    
	    
	    <tr>
		<th>Datum bezahlt</th>
		<td>";
        if(empty($datum_bezahlt))
        {
            echo "<input type='date' id='datepicker'  name='datum_bezahlt'>";
        }
        else
        {
            echo "<input style='width: 150px;' id='arrival' type='date' name='datum_bezahlt' value='" .$datum_bezahlt. "'>";
        }

        echo "</td>
	    </tr>			    
	    
    	<tr>
	    	<th>Bemerkung</th>
		    <td><textarea rows='9' name='bemerkung'>".$bemerkung."</textarea></td>
	    </tr>			    

        	<tr>
		<td colspan='2' style='text-align: center;'>&nbsp;</td>
	</tr>
	
	<tr>
		<td colspan='2' style='text-align: center;'>
			<input type='submit' name='submit_update' value='UPDATE' class='myButtonGross'>
			<br>
		<input type='button' onClick=\"parent.location='".WB_URL."/pages/kontakt/beitrag.php'\" value='zurück zur Übersicht'  class='myButtonGross'>
		</td>
	</tr>
	</table>
	</form>
	<p>&nbsp;</p>";

    }


}

# UPDATE
elseif(isset($_POST['submit_update']))
{

    /*
    betrag
    bezahlt
    datum_bezahlt
    bemerkung
    */

}
# Delete-Funktion (Achtung: löscht alle Beiträge eines Kontakts)
elseif (isset($_POST['submit_delete'])) {
    if(isset($_POST['submit_auswahl'])) {
        $kontakt_id = intval($_POST['submit_auswahl']);
        $sql = "DELETE FROM `tbl_mitgliederbeitrag` WHERE `kontakt_id` = '$kontakt_id'";
        $mysqli->query($sql);
        echo $go_back;
        echo "<div style='color: red; font-weight: bold;'>Der Datensatz $kontakt_id wurde erfolgreich gelöscht.</div>";
    } else {
        echo "<p>Bitte w&auml;hlen Sie einen Datensatz mittels Radiobutton aus!</p>";
        echo $go_back;
    }
}
else {

    # --- Filter einlesen & validieren ---
    $jahr_default = date('Y');
    $jahr = isset($_GET['jahr']) && preg_match('/^\d{4}$/', $_GET['jahr']) ? $_GET['jahr'] : $jahr_default;

    # bezahlt-Filter: 'alle' | 'ja' | 'nein'
    $bezahlt = isset($_GET['bezahlt']) ? strtolower(trim($_GET['bezahlt'])) : 'alle';
    $bezahlt_allowed = ['alle','ja','nein'];
    if (!in_array($bezahlt, $bezahlt_allowed, true)) { $bezahlt = 'alle'; }

    # --- WHERE dynamisch bauen (streng whitelisted, daher sicher) ---
    $where = [];
    $where[] = "m.`jahr` = '". $mysqli->real_escape_string($jahr) ."'";
    if ($bezahlt !== 'alle') {
        $where[] = "m.`bezahlt` = '". $mysqli->real_escape_string($bezahlt) ."'";
    }
    $where_sql = "WHERE " . implode(" AND ", $where);

    # --- SQL ---
    $data_sql = "
SELECT
    m.`mitgliederbeitrag_id`,
    m.`kontakt_id`,
    k.`kontakt_vname`,
    k.`kontakt_nname`,
    k.`kontakt_email`,
    m.`jahr`,
    m.`betrag`,
    m.`bezahlt`,
    m.`datum_bezahlt`,
    m.`bemerkung`
FROM `tbl_mitgliederbeitrag` m
JOIN `tbl_kontakt` k
  ON k.`kontakt_id` = m.`kontakt_id`
{$where_sql}
ORDER BY k.`kontakt_nname`, k.`kontakt_vname`, m.`jahr` DESC
";

    $result = $mysqli->query($data_sql);
    if (!$result) {
        echo "<div style='color:red;font-weight:bold;'>SQL-Fehler: ".h($mysqli->error)."</div>";
        exit;
    }
    $anzahl = $result->num_rows;

    # --- Filter-Form ---
    echo "<form method='get' action=''>
            <div style='display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:10px;'>
                <label>Jahr:
                    <input type='number' name='jahr' min='2000' max='2100' value='".h($jahr)."' required>
                </label>
                <label>Bezahlt:
                    <select name='bezahlt'>
                        <option value='alle' ".($bezahlt==='alle'?'selected':'').">alle</option>
                        <option value='ja' ".($bezahlt==='ja'?'selected':'').">ja</option>
                        <option value='nein' ".($bezahlt==='nein'?'selected':'').">nein</option>
                    </select>
                </label>
                <button type='submit'>Filtern</button>
                <span style='margin-left:8px; font-weight:bold;'>Anzahl: ".h((string)$anzahl)."</span>
            </div>
          </form>";

    # --- Tabelle ---
    echo "<form action='" . $_SERVER['PHP_SELF'] . "' method='POST'>
		<div class='table-scrollable'>
		<table class='sortierbar' id='myTableNormal'>
		<thead>
			<tr>
				<th>ID</th>
				<th>Mitglied-ID</th>
				<th>Mitglied</th>
				<th>Jahr</th>
				<th>Betrag</th>
				<th>bezahlt</th>
				<th>Datum</th>
                <th>Auswahl</th>
				<th>edit</th>
				<th>delete</th>
			</tr>
		</thead>
		<tbody>";

    if ($anzahl === 0) {
        echo "<tr><td colspan='9'><em>Keine Datensätze für die gewählten Filter gefunden.</em></td></tr>";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
				<td>".h($row['mitgliederbeitrag_id'])."</td>
				<td>".h($row['kontakt_id'])."</td>
				<td>".h($row['kontakt_vname'])." ".h($row['kontakt_nname'])."</td>
				<td>".h($row['jahr'])."</td>
				<td>".h(number_format((float)$row['betrag'], 2, ',', '\''))."</td>
				<td>".h($row['bezahlt'])."</td>
				<td>".h($row['datum_bezahlt'])."</td>

				<td style='text-align: center;'>
					<input type='radio' name='submit_auswahl' value='".h($row['mitgliederbeitrag_id'])."'>
				</td>

				<td style='text-align: center;'>
					<input type='submit' name='submit_edit' value='edit' class='myButtonKlein'>
				</td> 

				<td class='center'>
					<input type='submit' name='submit_delete' value='löschen' class='myButtonKlein'
					onclick=\"return confirm('Wirklich löschen: Datensatz ".h($row['mitgliederbeitrag_id'])."?')\">
				</td>
			</tr>";
        }
    }

    echo "</tbody>
		</table>
		</div>
        </form>
		<p>&nbsp;</p>";
}
