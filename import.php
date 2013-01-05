<?

require_once( "functions.php" );

$fh = fopen( "typeids.csv", "r" );
$i=0;
echo "Importing<br/>";
while ( !feof($fh) ) {
	$line = fgets($fh);
	$ld = explode( ",", $line );
	$iname = preg_replace("/\"/","", $ld[1]);
	$iname = preg_replace("/\\\/","", $iname);
	$iname = preg_replace("/'/","", $iname);
#	echo "Importing ".$iname." with id ".$ld[0]."<br/>";
	$insert = "INSERT INTO items (module_id, name) VALUES (".$ld[0].", '".$iname."')";
	$rval = RunQuery($insert);
//	$i++;
//	if ( $i == 10 ) exit;

}
echo "Done<br/>";

?>
