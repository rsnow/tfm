<?
require_once('functions.php');
//header("Location: http://localhost/efm");

$id=$_GET['id'];
//echo "id=[" . $id . "]<br/>";

$ilist = GetItemIDs($id);
$items = explode("," , $ilist);
//	echo "entry[dna]=[".$entry[dna]."]<br/>";
	foreach ( $items as $item ) {
//		if ( $item ) {

			$iname = GetItemName($item);
			$effectid = GetItemEffect($item);
?>
			<form method="post" action="formproc.php" >
			<input type='hidden' name='form[action]' value='modifyitems'>
<?
			echo "<input type='hidden' name='pilotid' value='" . $id . "'>";
//			$effectid = 3;
//			echo "iname=[".$iname."]<br>";
//			echo "effectid=[".$effectid."] ";
//			echo "<img src='http://image.eveonline.com/Type/" . $item . "_32.png' width='24' align='middle'> " . $iname . " ";
			echo "<img src='http://image.eveonline.com/Type/" . $item . "_32.png' width='24' align='top'> ";
			echo "<select name='" . $item . "'>";
			echo "<option value='0'></option>";
			$query = "SELECT * FROM effects";
			$eres = RunQuery($query);
			while ( $eff = sqlite_fetch_array($eres) ) {
				echo "<option value='" . $eff[id] . "' " . ( ( $eff[id] == $effectid ) ? 'selected':'') . ">".$eff[name] . "</option>";
			}
			echo "</select> " . $iname . "<br/>";

}
?>
			<input type='submit' name='Submit' value='Submit' /><br/>
			</form>
<?


//ShowFleet();
//include('index.php');

?>
