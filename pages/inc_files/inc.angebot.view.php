<?php

header('Content-Type: text/html; charset=UTF-8');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$mysqli->set_charset("utf8");

$bg_rubrik = "style='background-color:#475c6a; line-height: 110%; padding: 5px; color:#FFFFFF;'";
$landing_page = "pages/angebot/edit.php";
$go_back   = "<html><head><title></title>
		<meta http-equiv='refresh' content='1; URL=".WB_URL."/".$landing_page."'></head>";
$action = "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
$background_gesperrt = " style='text-align: right;background-color: #D8D8D8;' ";

echo "<div align='center'>";

$sql = "SELECT * FROM tbl_angebot WHERE `aktiv` = 'ja' ORDER BY 1;";
$result = mysqli_query($mysqli,$sql);

echo "<form action='".$_SERVER['PHP_SELF']."' method='POST'>

<div class='table-scrollable'>


<table width='50%'>";

while ($field = $result -> fetch_array(MYSQLI_ASSOC))
{

	echo "
	<tr>
	<td valign='top' rowspan='5'><b>".$field['id_angebot'].".&nbsp;Angebot&nbsp;&nbsp;&nbsp;</b></td></tr>
		<tr><td colspan='2'><b>".$field['bezeichnung']."</b></td></tr>
		<tr><td>Dauer&nbsp;   </td><td>".$field['dauer_minuten']." Minuten</td></tr>
		<tr><td>Beschreibung&nbsp;  </td><td>".nl2br($field['beschreibung'])."</td></tr>
		<tr><td>Kosten[CHF]&nbsp;   </td><td>".number_format($field['kosten'],2)."</td></tr>
		<tr><td>&nbsp;</td></tr>
		";
}
echo "</table>
</div>
<p>&nbsp;</p>";
