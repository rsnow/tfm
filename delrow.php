<?
require_once('functions.php');
header("Location: http://" . $addy . "");

$id=$_GET['id'];
echo "id=[" . $id . "]<br/>";
$query = "DELETE FROM ships WHERE id='".$id."'";
RunQuery($query);
//ShowFleet();
//include('index.php');

?>
