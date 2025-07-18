<?php

$action               = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$pdf_ordner_reduziert = "/media/rechnungen/";
$pdf_ordner           = WB_PATH.$pdf_ordner_reduziert;
$handle               = opendir ($pdf_ordner);
$landing_page = "pages/buchen/terminuebersicht.php";
$go_back   = "<html><head><title></title>
		<meta http-equiv='refresh' content='1; URL=".WB_URL."/".$landing_page."'></head>";

echo $action;

echo "<div style='text-align: center;'>";

$filter = $_POST['filter'];

if (isset($_POST['filter']))
{

    if ($_POST['filter'] <> "Bitte eine Auswahl treffen")
    {

        echo "Filter = ".$_POST['filter'];

        echo "<br><br><i>Für Sortierung bitte in die betreffende Kopfzeile klicken</i>";

        echo "<table <table class='sortierbar' id='myTableNormal'>>
        <thead>
        <tr>
        <th>#Datum</th>
        <th>Datum</th>
        <th>PDF Dokument</th>
        <th>Buchungs-ID</th>
        </tr>
        </thead>
        <tbody>";

        if ($handle = opendir($pdf_ordner)) 
		{       
			while (false !== ($datei = readdir($handle))) 
			{
                if ($datei != "." and $datei != "..")
                {
                    $pdf_link = WB_URL . $pdf_ordner_reduziert;
					$id_extract = substr($datei, strripos($datei, '_') + 1, strlen($datei));
                    $array = explode("_", $datei);
					// 20250513_rechnun
					$jahr_monat = substr($datei, 0, 4)."-".substr($datei, 4, 2);
						
					if ($jahr_monat == $filter) 
					{
                        echo "<tr>

                         <td>" . substr($datei, 0, 8) . "</td>
                         <td>" . substr($datei, 6, 2) . "." . substr($datei, 4, 2) . "." . substr($datei, 0, 4) . "</td>
                         <td><a href='" . $pdf_link . $datei . "' target='_blank'>" . $datei . "</a></td>
                         <td align='right'>" . str_replace('.pdf', '', $id_extract) . "</td>
                         </td>
                         
                        </tr>";
                    }
                }
            }
            echo "</tbody>
              </table>
              <p>&nbsp;</p>
              </div>";
            closedir($handle);
        }
		
		echo"<div style='text-align: center;'><table>
			<tr>
		<td>
		<input type='button' 
		onClick=\"parent.location='".WB_URL."/pages/buchen/edit.php'\" value='zurück zur Übersicht'  
		class='myButtonGross'>
		</td>
	</tr></table></div>";
		
    }
    else
    {

        echo "Bitte wählen Sie einen Monat aus!";
        echo $go_back;

    }
}	
else
{
    # das Jahr-Monat ausgeben
    $sql = "SELECT jahr_monat FROM `vw_buchung` GROUP BY jahr_monat;";
    $query_fields = $database->query($sql);

    # Ausgabe der Combobox mit den ermittelten Datumsangaben
    echo $action;

    echo "Bitte wählen Sie ein Reportingjahr<br>
         <table>
		 <tr><td>
		 <select name='filter' class='input_select'>
         <option>Bitte eine Auswahl treffen</option>";

    while ($field = $query_fields->fetchRow(MYSQLI_ASSOC)) {
        if ($field['jahr_monat'] == $_POST['filter']) {
            $auswahl = " selected";
        } else {
            $auswahl = " ";
        }
        echo "<option value='" . $field['jahr_monat'] . "' " . $auswahl . ">" . $field['jahr_monat'] . "</option>\n";
    }
    echo "</select>
		<tr><td>&nbsp;<td></tr>	
          </td></tr>
          <tr><td style='text-align: center;'><input type='submit' name='submit_filter' value='Anzeigen'class='myButtonKlein'></td></tr>
          </table>";
}
echo "<p>&nbsp;</p>";